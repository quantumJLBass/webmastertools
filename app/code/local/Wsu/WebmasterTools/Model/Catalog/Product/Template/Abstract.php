<?php
abstract class Wsu_WebmasterTools_Model_Catalog_Product_Template_Abstract extends Varien_Object {
    protected $_product = null;
    public function setProduct(Mage_Catalog_Model_Product $product) {
        $this->_product = $product;
        return $this;
    }
    protected function __parse($template) {
        $vars = array();
        preg_match_all('~(\[(.*?)\])~', $template, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            preg_match('~^((?:(.*?)\{(.*?)\}(.*)|[^{}]*))$~', $match[2], $params);
            array_shift($params);
            if (count($params) == 1) {
                $vars[$match[1]]['prefix']     = $vars[$match[1]]['suffix'] = '';
                $vars[$match[1]]['attributes'] = explode('|', $params[0]);
            } else {
                $vars[$match[1]]['prefix']     = $params[1];
                $vars[$match[1]]['suffix']     = $params[3];
                $vars[$match[1]]['attributes'] = explode('|', $params[2]);
            }
        }
        return $vars;
    }
    abstract protected function __compile($template);
}