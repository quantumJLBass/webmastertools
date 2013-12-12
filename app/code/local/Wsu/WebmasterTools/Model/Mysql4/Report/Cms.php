<?php
class Wsu_WebmasterTools_Model_Mysql4_Report_Cms extends Mage_Core_Model_Mysql4_Abstract {
    
    protected function _construct()  {        
        $this->_init('webmastertools/report_cms', 'page_id');
    }           
    
}