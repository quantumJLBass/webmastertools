<?php
class Wsu_WebmasterTools_Model_Catalog_Product_Attribute_Source_Meta_Robots extends Mage_Eav_Model_Entity_Attribute_Source_Abstract {
    public function getAllOptions(){
        if (!$this->_options) {
          $this->_options = array(
                array('value' => '', 'label' => 'Use Config'),
                array('value' => 'INDEX, FOLLOW', 'label' => 'INDEX, FOLLOW'),
                array('value' => 'INDEX, NOFOLLOW', 'label' => 'INDEX, NOFOLLOW'),
                array('value' => 'NOINDEX, FOLLOW', 'label' => 'NOINDEX, FOLLOW'),
                array('value' => 'NOINDEX, NOFOLLOW', 'label' => 'NOINDEX, NOFOLLOW'),
                array('value' => 'INDEX, FOLLOW, NOARCHIVE', 'label' => 'INDEX, FOLLOW, NOARCHIVE'),
                array('value' => 'INDEX, NOFOLLOW, NOARCHIVE', 'label' => 'INDEX, NOFOLLOW, NOARCHIVE'),
                array('value' => 'NOINDEX, NOFOLLOW, NOARCHIVE', 'label' => 'NOINDEX, NOFOLLOW, NOARCHIVE'),
          );
        }
        return $this->_options;
    }
}