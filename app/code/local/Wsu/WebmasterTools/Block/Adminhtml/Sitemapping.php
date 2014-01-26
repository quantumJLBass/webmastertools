<?php
class Wsu_WebmasterTools_Block_Adminhtml_Sitemapping extends Mage_Adminhtml_Block_Widget_Grid_Container {
    public function __construct() {
        $this->_blockGroup = 'wsu_webmastertools';
        $this->_controller = 'adminhtml_sitemapping';
        $this->_headerText = Mage::helper('wsu_webmastertools')->__('Extended Site mapping');
        $this->_addButtonLabel = Mage::helper('wsu_webmastertools')->__('Add Sitemap');
		parent::__construct();
    }
}
