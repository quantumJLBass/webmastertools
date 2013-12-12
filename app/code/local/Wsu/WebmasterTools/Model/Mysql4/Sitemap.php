<?php
class Wsu_WebmasterTools_Model_Mysql4_Sitemap extends Mage_Sitemap_Model_Mysql4_Sitemap {
    protected function _construct() {
        $this->_init('webmastertools/sitemap', 'sitemap_id');
    }
}
