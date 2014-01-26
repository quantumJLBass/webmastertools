<?php
class Wsu_WebmasterTools_Block_Catalog_Products extends Mage_Core_Block_Template {
    const XML_PATH_SORT_ORDER = 'wsu_webmastertools/sitemaper/sort_order';
    public function getCollection() {
        $collection = Mage::getModel('catalog/product')->getCollection();
        /* @var $collection Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection */
        $collection->addAttributeToSelect('name')->addAttributeToSelect('url_key')->addStoreFilter()->addUrlRewrite($this->getCategory()->getId())->setOrder(Mage::getStoreConfig(self::XML_PATH_SORT_ORDER), 'ASC');
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        $collection->addCategoryFilter($this->getCategory());
        //$collection->load(true);
        return $collection;
    }
    public function getItemUrl($product) {
        if (version_compare(Mage::getVersion(), '1.2', '<')) {
            return $product->getProductUrl($product);
        }
        $url = '';
        if ((string) Mage::getConfig()->getModuleConfig('Wsu_WebmasterTools')->active == 'true')
            $url = Mage::helper('wsu_webmastertools')->getCanonicalUrl($product);
        return $url ? $url : Mage::helper('catalog/product')->getProductUrl($product);
    }
}
