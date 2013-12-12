<?php
class Wsu_WebmasterTools_Model_Catalog_Product_Template_Url extends Wsu_WebmasterTools_Model_Catalog_Product_Template_Abstract {
    protected $_useDefault = array();
    protected $_defaultProduct = null;
    public function process() {
        if (!$this->_product instanceof Mage_Catalog_Model_Product) {
            return;
        }
        if (!empty($this->_useDefault) || $this->_product->getStore()->getId() > 0) {
            $this->_defaultProduct = Mage::getModel('catalog/product')->load($this->_product->getId());
        }
        try {
            $string = $this->__compile($this->getTemplate());
        }
        catch (Exception $e) {
        }
        return $string;
    }
    public function setUseDefault($array) {
        $this->_useDefault = (array) $array;
        return $this;
    }
    protected function __compile($template) {
        $vars = $this->__parse($template);
        foreach ($vars as $key => $params) {
            foreach ($params['attributes'] as $n => $attribute) {
                if (in_array($attribute, $this->_useDefault)) {
                    $product =& $this->_defaultProduct;
                } else {
                    $product =& $this->_product;
                }
                $value = '';
                switch ($attribute) {
                    case 'category':
                    case 'categories':
                        break;
                    case 'price':
                        if ($product->getPrice() > 0) {
                            $value = Mage::app()->getStore($this->_product->getStore()->getId())->convertPrice($product->getPrice(), false, false);
                        }
                        break;
                    default:
                        if ($_attr = $product->getResource()->getAttribute($attribute)) {
                            $value = $_attr->getSource()->getOptionText($product->getData($attribute));
                        }
                        if (!$value) {
                            $value = $product->getData($attribute);
                        }
                        if (is_array($value))
                            $value = implode(' ', $value);
                }
                if ($value) {
                    $value = $params['prefix'] . $value . $params['suffix'];
                    break;
                }
            }
            $template = str_replace($key, $value, $template);
        }
        return $template;
    }
}