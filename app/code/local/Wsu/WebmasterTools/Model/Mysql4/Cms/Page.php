<?php
class Wsu_WebmasterTools_Model_Mysql4_Cms_Page extends Mage_Core_Model_Mysql4_Abstract {
    const XML_PATH_FILTER_PAGES = 'webmastertools/sitemaper/filter_pages';
    const XML_PATH_HOME_PAGE = 'web/default/cms_home_page';
    protected function _construct() {
        $this->_init('cms/page', 'page_id');
        $this->_homePage = Mage::getStoreConfig(self::XML_PATH_HOME_PAGE);
    }
    public function getCollection($storeId) {
        $pages       = array();
        $filterPages = Mage::getStoreConfig(self::XML_PATH_FILTER_PAGES, $storeId);
        $filterPages = explode(',', $filterPages);
        $read        = $this->_getReadAdapter();
        $select      = $read->select()->from(array(
            'main_table' => $this->getMainTable()
        ), array(
            $this->getIdFieldName(),
            'identifier AS url'
        ))->join(array(
            'store_table' => $this->getTable('cms/page_store')
        ), 'main_table.page_id=store_table.page_id', array())->where('main_table.identifier NOT IN(?)', $filterPages)->where('main_table.exclude_from_sitemap=0')->where('main_table.is_active=1')->where('store_table.store_id IN(?)', array(
            0,
            $storeId
        ));
        $query       = $read->query($select);
        while ($row = $query->fetch()) {
            if ($row['url'] == $this->_homePage) {
                $row['url'] = '';
            }
            $page                  = $this->_prepareObject($row);
            $pages[$page->getId()] = $page;
        }
        return $pages;
    }
    protected function _prepareObject(array $data) {
        $object = new Varien_Object();
        $object->setId($data[$this->getIdFieldName()]);
        $object->setUrl($data['url']);
        return $object;
    }
}