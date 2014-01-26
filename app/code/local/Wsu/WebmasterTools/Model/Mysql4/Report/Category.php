<?php
class Wsu_WebmasterTools_Model_Mysql4_Report_Category extends Mage_Core_Model_Mysql4_Abstract {
    protected function _construct() {        
        $this->_init('wsu_webmastertools/report_category', 'entity_id');
    }           
}