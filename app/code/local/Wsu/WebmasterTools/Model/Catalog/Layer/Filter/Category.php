<?php
class Wsu_WebmasterTools_Model_Catalog_Layer_Filter_Category extends Mage_Catalog_Model_Layer_Filter_Category {

    protected function _getCategoryByName($filter) {
        return Mage::getModel('webmastertools/catalog_category')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->loadByAttribute('name', $filter);
    }

    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock) {
        $filter = $request->getParam($this->getRequestVar());
        if (is_null($filter)) {
            return parent::apply($request, $filterBlock);
        }
        if (!is_numeric($filter)) {
            if (Mage::registry('current_category')) {
                $collection = Mage::getModel('catalog/category')->getCollection()
                        ->addAttributeToFilter('parent_id', Mage::registry('current_category')->getId())
                        ->addAttributeToFilter('is_active', 1)
                        ->addAttributeToSelect('name')
                        ->addAttributeToFilter('name', $filter);
                $this->_appliedCategory = $collection->getFirstItem();
                if (!$this->_appliedCategory->getProductCollection()->count()) {
                    $this->_appliedCategory = $this->_getCategoryByName($filter);
                }
            } else {
                $this->_appliedCategory = $this->_getCategoryByName($filter);
            }

            if ($this->_appliedCategory) {
                $this->_categoryId = $filter = $this->_appliedCategory->getId();
            }
        } else {
            $this->_categoryId = $filter;
            $this->_appliedCategory = Mage::getModel('catalog/category')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load($filter);
        }

        if ($this->_isValidCategory($this->_appliedCategory)) {
            $this->getLayer()->getProductCollection()
                    ->addCategoryFilter($this->_appliedCategory);

            $this->getLayer()->getState()->addFilter(
                    $this->_createItem($this->_appliedCategory->getName(), $filter)
            );
        }

        return $this;
    }

    protected function _isValidCategory($category) {
        return ($category instanceof Varien_Object && $category->getId());
    }

}
