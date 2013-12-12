<?php
class Wsu_WebmasterTools_Model_System_Config_Source_Sitemaper_Sortorder {

    public function toOptionArray() {
        return array(
            array('value'=>'position', 'label'=>Mage::helper('wsu_webmastertools')->__('Position')),
            array('value'=>'name', 'label'=>Mage::helper('wsu_webmastertools')->__('Name')),
        );
    }

}
