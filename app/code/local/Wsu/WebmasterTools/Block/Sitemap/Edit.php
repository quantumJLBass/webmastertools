
<?php
class Wsu_WebmasterTools_Block_Sitemap_Edit extends Mage_Adminhtml_Block_Sitemap_Edit {
 	public function __construct() {
    	parent::__construct();
    	// Only put the button here if the extension is enabled
    	if (Mage::helper('webmastertools')->isSubmissionEnabled()) {
	    	$params = array("sitemap_id" => Mage::registry('sitemap_sitemap')->getId());
	    	$url = Mage::helper('adminhtml')->getUrl("webmastertools", $params);
	    	
	    	 $this->_addButton('submit', array(
	            'label'   => Mage::helper('adminhtml')->__('Submit Sitemap'),
	            'onclick' => "window.location.href='".$url."'",
	            'class'   => 'add',
	        ));
    	}
        return $this;
    }
}