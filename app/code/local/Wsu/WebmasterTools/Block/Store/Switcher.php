<?php

class Wsu_WebmasterTools_Block_Store_Switcher extends Mage_Core_Block_Store_Switcher {
    public function __construct() {
        if (!Mage::app()->isSingleStoreMode()){
            parent::__construct();
        }
    }

    public function getStores() {
        return $this->_stores;
    }

    public function getStoreId() {
        return Mage::app()->getStore()->getId();
    }
}