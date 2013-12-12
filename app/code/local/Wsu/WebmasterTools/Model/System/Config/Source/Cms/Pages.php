<?php
class Wsu_WebmasterTools_Model_System_Config_Source_Cms_Pages {
    protected $_options;

    public function toOptionArray($isMultiselect=false) {
        if (!$this->_options) {
            $storeCode = Mage::app()->getRequest()->getParam('store');
            $collection = Mage::getModel('cms/page')->getCollection();
            $collection->addStoreFilter(Mage::app()->getStore($storeCode)->getId());
            $collection->addFieldToFilter('is_active', array('eq' => 1));
            $this->_options = $collection->loadData()->toOptionArray(false);
        }

        $options = $this->_options;
        if(!$isMultiselect){
            array_unshift($options, array('value'=>'', 'label'=> Mage::helper('adminhtml')->__('--Please Select--')));
        }

        return $options;
    }
}