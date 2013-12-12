<?php
class Wsu_WebmasterTools_Block_Adminhtml_Sitemapping_Grid_Renderer_Link extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {
    
    public function render(Varien_Object $row) {
        $fileName = preg_replace('/^\//', '', $row->getSitemapPath() . $row->getSitemapFilename());
        $url = $this->htmlEscape(Mage::app()->getStore($row->getStoreId())->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK) . $fileName);

        $code = (Mage::app()->getStore($row->getStoreId())->getCode() == 'default') ? '' : Mage::app()->getStore($row->getStoreId())->getCode() . DS;

        if (file_exists(BP . DS . $code . $fileName)) {
            return sprintf('<a href="%1$s">%1$s</a>', $url);
        }
        return $url;
    }
    
}
