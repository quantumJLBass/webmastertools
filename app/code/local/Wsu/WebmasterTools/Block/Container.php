<?php
class Wsu_WebmasterTools_Block_Container extends Mage_Core_Block_Template {
    const XML_PATH_SHOW_STORES = 'wsu_webmastertools/sitemaper/show_stores';
    const XML_PATH_SHOW_CATEGORIES = 'wsu_webmastertools/sitemaper/show_categories';
    const XML_PATH_SHOW_PAGES = 'wsu_webmastertools/sitemaper/show_pages';
    const XML_PATH_SHOW_LINKS = 'wsu_webmastertools/sitemaper/show_links';
    protected function _construct() {
        $this->setTitle($this->__('Site Map'));
    }
    protected function _prepareLayout() {
        parent::_prepareLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->getTitle());
    }
    public function showStores() {
        return Mage::getStoreConfigFlag(self::XML_PATH_SHOW_STORES);
    }
    public function showCategories() {
        return Mage::getStoreConfigFlag(self::XML_PATH_SHOW_CATEGORIES);
    }
    public function showPages() {
        return Mage::getStoreConfigFlag(self::XML_PATH_SHOW_PAGES);
    }
    public function showLinks() {
        return Mage::getStoreConfigFlag(self::XML_PATH_SHOW_LINKS);
    }
}
