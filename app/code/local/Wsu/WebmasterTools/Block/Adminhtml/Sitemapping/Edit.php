<?php
class Wsu_WebmasterTools_Block_Adminhtml_Sitemapping_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {
    public function __construct() {
        $this->_objectId   = 'sitemap_id';
        $this->_blockGroup = 'wsu';
        $this->_controller = 'webmastertools';
        parent::__construct();
        $this->_addButton('generate', array(
            'label' => Mage::helper('wsu_webmastertools')->__('Save & Generate'),
            'onclick' => "$('generate').value=1; editForm.submit();",
            'class' => 'add'
        ));
    	if (Mage::helper('wsu_webmastertools')->isSubmissionEnabled()) {
	    	$params = array("sitemap_id" => Mage::registry('sitemap_sitemap')->getId());
	    	$url = Mage::helper('adminhtml')->getUrl("webmastertools", $params);
	    	
	    	 $this->_addButton('submit', array(
	            'label'   => Mage::helper('adminhtml')->__('Submit Sitemap'),
	            'onclick' => "window.location.href='".$url."'",
	            'class'   => 'add',
	        ));
    	}
    }
    public function getHeaderText() {
        if (Mage::registry('sitemap_sitemap')->getId()) {
            return Mage::helper('wsu_webmastertools')->__('Edit Sitemap');
        } else {
            return Mage::helper('wsu_webmastertools')->__('New Sitemap');
        }
    }
}
