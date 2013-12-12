<?php
class Wsu_WebmasterTools_Model_Catalog_Product_Template_Title extends Wsu_WebmasterTools_Model_Catalog_Product_Template_Abstract {
    protected $_useDefault = array();
    protected $_defaultProduct = null;
    public function process() {
        if (!$this->_product instanceof Mage_Catalog_Model_Product) {
            return;
        }
        try {
            $string = $this->__compile($this->getTemplate());
        }
        catch (Exception $e) {
        }
        return $string;
    }
    protected function __compile($template) {
        $vars = $this->__parse($template);
        foreach ($vars as $key => $params) {
            foreach ($params['attributes'] as $n => $attribute) {
                $value = '';
                switch ($attribute) {
                    case 'category':
                        $category = $this->_product->getCategory();
                        if ($category) {
                            $value = $this->_product->getCategory()->getName();
                        } else {
                            $categoryItems = $this->_product->getCategoryCollection()->load()->getIterator();
                            $category      = current($categoryItems);
                            if ($category) {
                                $category = Mage::getModel('catalog/category')->load($category->getId());
                                $value    = $category->getName();
                            }
                        }
                        break;
                    case 'categories':
                        $separator = (string) Mage::getStoreConfig('catalog/seo/title_separator');
                        $separator = ' ' . $separator . ' ';
                        $title     = array();
                        $path      = Mage::helper('catalog')->getBreadcrumbPath();
                        foreach ($path as $name => $breadcrumb) {
                            $title[] = $breadcrumb['label'];
                        }
                        array_pop($title);
                        $value = join($separator, array_reverse($title));
                        break;
                    case 'store_view_name':
                        $value = Mage::app()->getStore($this->_product->getStoreId())->getName();
                        break;
                    case 'store_name':
                        $value = Mage::app()->getStore($this->_product->getStoreId())->getGroup()->getName();
                        break;
                    case 'website_name':
                        $value = Mage::app()->getStore($this->_product->getStoreId())->getWebsite()->getName();
                        break;
                    case 'price':
                        if ($this->_product->getTypeId() == 'bundle') {
                            list($_minimalPriceTax, $_maximalPriceTax) = $this->_product->getPriceModel()->getPrices($this->_product);
                            if ($this->_product->getPriceType() == 1) {
                                $_weeeTaxAmount = Mage::helper('weee')->getAmount($this->_product);
                                if ($_weeeTaxAmount && Mage::helper('weee')->typeOfDisplay($this->_product, array(
                                    0,
                                    1,
                                    4
                                ))) {
                                    $_minimalPriceTax += $_weeeTaxAmount;
                                }
                                if ($_weeeTaxAmount && Mage::helper('weee')->typeOfDisplay($this->_product, array(
                                    0,
                                    1,
                                    4
                                ))) {
                                    $_maximalPriceTax += $_weeeTaxAmount;
                                }
                            }
                            if ($_minimalPriceTax <> $_maximalPriceTax)
                                $value = Mage::helper('wsu_webmastertools')->__('form %s to %s', Mage::app()->getStore()->convertPrice($_minimalPriceTax, true, false), Mage::app()->getStore()->convertPrice($_maximalPriceTax, true, false));
                            else
                                $value = Mage::app()->getStore()->convertPrice($_maximalPriceTax, true, false);
                        } elseif ($this->_product->getTypeId() == 'grouped') {
                            if (Mage::helper('tax')->displayPriceIncludingTax())
                                $includingTax = true;
                            else
                                $includingTax = null;
                            $price = Mage::helper('tax')->getPrice($this->_product, $this->_product->getMinimalPrice(), $includingTax);
                            if ($price > 0)
                                $value = Mage::helper('wsu_webmastertools')->__('form %s', Mage::app()->getStore()->convertPrice($price, true, false));
                            else
                                $value = '';
                        } elseif ($this->_product->getPrice() > 0) {
                            $value = Mage::app()->getStore()->convertPrice($this->_product->getPrice(), true, false);
                        } else {
                            $value = '';
                        }
                        break;
                    case 'special_price':
                        if ($this->_product->getData($attribute) > 0) {
                            $value = Mage::app()->getStore()->formatPrice($this->_product->getData($attribute), false);
                        } else {
                            $value = '';
                        }
                        break;
                    default:
                        if ($_attr = $this->_product->getResource()->getAttribute($attribute)) {
                            $value = $_attr->getSource()->getOptionText($this->_product->getData($attribute));
                        }
                        if (!$value) {
                            $value = $this->_product->getData($attribute);
                        }
                        if (is_array($value)) {
                            $value = implode(', ', $value);
                        }
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