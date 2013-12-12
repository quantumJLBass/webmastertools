<?php
class Wsu_WebmasterTools_Model_Catalog_Product_Attribute_Backend_Urlkey extends Mage_Catalog_Model_Product_Attribute_Backend_Urlkey {
    public function beforeSave($object) {
        $attributeName = $this->getAttribute()->getName();

        $urlKey = $object->getData($attributeName);
        if ($this->_product = Mage::registry('current_product')){
            if ($urlKey == '') {
                $this->_useDefault = (array) Mage::app()->getRequest()->getPost('use_default');

                if (in_array('url_key', $this->_useDefault)){
                    return $this;
                }

                if ($this->_product->getStore()->getId() > 0){
                    $this->_defaultProduct = Mage::getModel('catalog/product')->load($this->_product->getId());
                }

                $urlKeyTemplate = (string) Mage::getStoreConfig('webmastertools/sitemaper/product_url_key', $this->_product->getStore()->getId());
                $template = Mage::getModel('webmastertools/catalog_product_template_url');
                $template->setTemplate($urlKeyTemplate)
                    ->setUseDefault($this->_useDefault)
                    ->setProduct($this->_product);

                $urlKey = $template->process();

                if ($urlKey == '') {
                    $urlKey = $object->getName();
                }
            }
        } else {
            return parent::beforeSave($object);
        }

        /*if ($this->_product->formatUrlKey($urlKey) != $this->_product->getUrlKey()){
            $urlRewrites = Mage::getModel('core/url_rewrite')->getCollection()->filterAllByProductId($this->_product->getId(), true)->load();
            foreach ($urlRewrites as $urlRewrite){
                $urlRewrite->setIsSystem(0)->setOptions('RP')->save();
            }
        }*/

        $object->setData($attributeName, $object->formatUrlKey($urlKey));

        return $this;
    }


}
