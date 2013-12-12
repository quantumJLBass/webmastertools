<?php
class Wsu_WebmasterTools_Model_Catalog_Product_Attribute_Source_Meta_Canonical extends Mage_Eav_Model_Entity_Attribute_Source_Abstract {

    public function getAllOptions() {
        $productId = Mage::registry('webmastertools_product_id');
        if (!$this->_options) {
            $this->_options = array(
                array('value' => '', 'label' => Mage::helper('wsu_webmastertools')->__('Use Config')),
            );
            //$storeId = Mage::app()->getRequest()->getParam('store', Mage::app()->getDefaultStoreView()->getId());
            //$storeId = (int) Mage::app()->getRequest()->getParam('store', 0);
            if ($productId!=null) {
                $collection = Mage::getResourceModel('webmastertools/core_url_rewrite_collection')
                                //->addStoreFilter($storeId, false)
                                ->filterAllByProductId($productId)
                                ->groupByUrl()
                                ->sortByLength('ASC');
//                echo $collection->getSelect()->assemble(); exit;
                if ($collection->count()) {
                    foreach ($collection->getItems() as $urlRewrite) {
                        $this->_options[] = array('value' => $urlRewrite->getIdPath(), 'label' => $urlRewrite->getRequestPath());
                    }
                }
            }
        }
        return $this->_options;
    }
}