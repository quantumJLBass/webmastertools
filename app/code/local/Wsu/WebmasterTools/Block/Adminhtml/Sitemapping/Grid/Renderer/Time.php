<?php
class Wsu_WebmasterTools_Block_Adminhtml_Sitemapping_Grid_Renderer_Time extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {
    public function render(Varien_Object $row) {
        $time =  date('Y-m-d H:i:s', strtotime($row->getSitemapTime()) + Mage::getSingleton('core/date')->getGmtOffset());
        return $time;
    }

}
