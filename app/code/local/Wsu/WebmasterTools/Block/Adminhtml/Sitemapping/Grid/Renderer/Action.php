<?php

class Wsu_WebmasterTools_Block_Adminhtml_Sitemapping_Grid_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action {
    public function render(Varien_Object $row) {
        $this->getColumn()->setActions(array(
		array(
            'url'     => $this->getUrl('*/sitemapping/generate', array('sitemap_id' => $row->getSitemapId())),
            'caption' => Mage::helper('wsu_webmastertools')->__('Generate'),
        ),
		array(
            'url'     => $this->getUrl('*/sitemapping/submit', array("sitemap_id" => $row->getSitemapId())),
            'caption' => Mage::helper('adminhtml')->__('Submit Sitemap'),
        )
		));
		
		
		
        return parent::render($row);
    }
}
