<?php
class Wsu_WebmasterTools_Model_Mysql4_Catalog_Category extends Mage_Core_Model_Mysql4_Abstract {
    /**
     * Collection Zend Db select
     *
     * @var Zend_Db_Select
     */
    protected $_select;
    /**
     * Attribute cache
     *
     * @var array
     */
    protected $_attributesCache = array();
    /**
     * Init resource model (catalog/category)
     */
    protected function _construct() {
        $this->_init('catalog/category', 'entity_id');
    }
    /**
     * Get category collection array
     *
     * @return array
     */
    public function getCollection($storeId) {
        $categories = array();
        $store      = Mage::app()->getStore($storeId);
        /* @var $store Mage_Core_Model_Store */
        if (!$store) {
            return false;
        }
        $this->_select = $this->_getWriteAdapter()->select()->from($this->getMainTable())->where($this->getIdFieldName() . '=?', $store->getRootCategoryId());
        $categoryRow   = $this->_getWriteAdapter()->fetchRow($this->_select);
        if (!$categoryRow) {
            return false;
        }
        $urConditions  = array(
            'e.entity_id=ur.category_id',
            $this->_getWriteAdapter()->quoteInto('ur.store_id=?', $store->getId()),
            'ur.product_id IS NULL',
            $this->_getWriteAdapter()->quoteInto('ur.is_system=?', 1)
        );
        $this->_select = $this->_getWriteAdapter()->select()->from(array(
            'e' => $this->getMainTable()
        ), array(
            $this->getIdFieldName()
        ));
        $this->_addFilter($storeId, 'is_active', 0);
        $queryInactive = $this->_getWriteAdapter()->query($this->_select);
        $allRows       = $queryInactive->fetchAll();
        if ($categoryRow['path'] != '') {
            $categoryRow['path'] .= '/';
        }
        $condition = '';
        foreach ($allRows as $row) {
            $condition .= 'path not like \'%/' . $row['entity_id'] . '/%\' AND path not like \'' . $categoryRow['path'] . $row['entity_id'] . '/%\' AND ';
        }
        $condition = substr($condition, 0, count($condition) - 6);
        if ($condition) {
            $condition = ' AND ' . $condition;
        }
        $newRows = array();
        foreach ($allRows as $row) {
            $newRows[] = $row['entity_id'];
        }
        $condition2 = implode(',', $newRows);
        if (strlen($condition2) > 0) {
            $condition2 = ' AND parent_id NOT IN(' . $condition2 . ')';
        } else {
            $condition2 = '';
        }
        $read          = $this->_getReadAdapter();
        $this->_select = $read->select()->from(array(
            'e' => $this->getMainTable()
        ), array(
            $this->getIdFieldName(),
            'path',
            'level'
        ))->joinLeft(array(
            'ur' => $this->getTable('core/url_rewrite')
        ), join(' AND ', $urConditions), array(
            'url' => 'request_path'
        ))->where('e.path LIKE ?' . $condition . $condition2, $categoryRow['path'] . '%')->order('level ASC');
        $excludeAttr   = Mage::getSingleton('catalog/category')->getResource()->getAttribute('exclude_from_sitemap');
        if ($excludeAttr) {
            $this->_select->joinLeft(array(
                'exclude_tbl' => $excludeAttr->getBackend()->getTable()
            ), 'exclude_tbl.entity_id = e.entity_id AND exclude_tbl.attribute_id = ' . $excludeAttr->getAttributeId() . ' AND exclude_tbl.store_id = 0', array())->where('exclude_tbl.value=0 OR exclude_tbl.value IS NULL');
        }
        $this->_addFilter($storeId, 'is_active', 1);
        $query = $read->query($this->_select);
        while ($row = $query->fetch()) {
            $category                       = $this->_prepareCategory($row);
            $categories[$category->getId()] = $category;
        }
        return $categories;
    }
    /**
     * Prepare category
     *
     * @param array $categoryRow
     * @return Varien_Object
     */
    protected function _prepareCategory(array $categoryRow) {
        $category = new Varien_Object();
        $category->setId($categoryRow[$this->getIdFieldName()]);
        $categoryUrl = !empty($categoryRow['url']) ? $categoryRow['url'] : 'catalog/category/view/id/' . $category->getId();
        $category->setUrl($categoryUrl);
        return $category;
    }
    /**
     * Add attribute to filter
     *
     * @param int $storeId
     * @param string $attributeCode
     * @param mixed $value
     * @param string $type
     *
     * @return Zend_Db_Select
     */
    protected function _addFilter($storeId, $attributeCode, $value, $type = '=') {
        if (!isset($this->_attributesCache[$attributeCode])) {
            $attribute                              = Mage::getSingleton('catalog/category')->getResource()->getAttribute($attributeCode);
            $this->_attributesCache[$attributeCode] = array(
                'entity_type_id' => $attribute->getEntityTypeId(),
                'attribute_id' => $attribute->getId(),
                'table' => $attribute->getBackend()->getTable(),
                'is_global' => $attribute->getIsGlobal(),
                'backend_type' => $attribute->getBackendType()
            );
        }
        $attribute = $this->_attributesCache[$attributeCode];
        if (!$this->_select instanceof Zend_Db_Select) {
            return false;
        }
        switch ($type) {
            case '=':
                $conditionRule = '=?';
                break;
            case 'in':
                $conditionRule = ' IN(?)';
                break;
            default:
                return false;
                break;
        }
        if ($attribute['backend_type'] == 'static') {
            $this->_select->where('e.' . $attributeCode . $conditionRule, $value);
        } else {
            $this->_select->join(array(
                't1_' . $attributeCode => $attribute['table']
            ), 'e.entity_id=t1_' . $attributeCode . '.entity_id AND t1_' . $attributeCode . '.store_id=0', array())->where('t1_' . $attributeCode . '.attribute_id=?', $attribute['attribute_id']);
            if ($attribute['is_global']) {
                $this->_select->where('t1_' . $attributeCode . '.value' . $conditionRule, $value);
            } else {
                $this->_select->joinLeft(array(
                    't2_' . $attributeCode => $attribute['table']
                ), $this->_getWriteAdapter()->quoteInto('t1_' . $attributeCode . '.entity_id = t2_' . $attributeCode . '.entity_id AND t1_' . $attributeCode . '.attribute_id = t2_' . $attributeCode . '.attribute_id AND t2_' . $attributeCode . '.store_id=?', $storeId), array())->where('IFNULL(t2_' . $attributeCode . '.value, t1_' . $attributeCode . '.value)' . $conditionRule, $value);
            }
        }
        return $this->_select;
    }
}