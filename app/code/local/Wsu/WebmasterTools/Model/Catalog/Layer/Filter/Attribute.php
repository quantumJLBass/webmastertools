<?php
class Wsu_WebmasterTools_Model_Catalog_Layer_Filter_Attribute extends Mage_Catalog_Model_Layer_Filter_Attribute {
    protected function _getOptionId($label) {
        if ($source = $this->getAttributeModel()->getSource()){
            return $source->getOptionId($label);
        }
        return false;
    }

    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock) {
        $text = $request->getParam($this->_requestVar);
        if (is_array($text)) {
            return $this;
        }
        $filter = $this->_getOptionId($text);
        if ($filter && $text) {
            if (method_exists($this, '_getResource')){
                $this->_getResource()->applyFilterToCollection($this, $filter);
            } else {
                Mage::getSingleton('catalogindex/attribute')->applyFilterToCollection(
                    $this->getLayer()->getProductCollection(),
                    $this->getAttributeModel(),
                    $filter
                );
            }
            $this->getLayer()->getState()->addFilter($this->_createItem($text, $filter));
            $this->_items = array();
        }
        return $this;
    }
}
