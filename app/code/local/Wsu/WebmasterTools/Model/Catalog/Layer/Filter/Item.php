<?php
class Wsu_WebmasterTools_Model_Catalog_Layer_Filter_Item extends Mage_Catalog_Model_Layer_Filter_Item {
    public function getUrl() {
        $request = Mage::app()->getRequest();
        if ($request->getModuleName() == 'catalogsearch') {
            return parent::getUrl();
        }

        if ($this->getFilter() instanceof Mage_Catalog_Model_Layer_Filter_Category) {
            $category = Mage::getModel('catalog/category')->load($this->getValue());

            $query = array(
                Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
            );

            $suffix = Mage::getStoreConfig('catalog/seo/category_url_suffix');
            $catpart = str_replace($suffix, '', $category->getUrl());
            
            if (preg_match('/\/l\/.+/', Mage::app()->getRequest()->getOriginalPathInfo(), $matches)) $layeredpart = str_replace($suffix, '', $matches[0]); else $layeredpart = '';

            return $catpart . $layeredpart . $suffix;
            
        } else {
            $var = $this->getFilter()->getRequestVar();
            $request = Mage::app()->getRequest();

            $labelValue = strpos($request->getRequestUri(), 'catalogsearch') !== false ? $this->getValue()
                    : $this->getLabel();

            $attribute = $this->getFilter()->getData('attribute_model'); //->getAttributeCode()
            if ($attribute) {
                $value = ($attribute->getAttributeCode() == 'price' || $attribute->getBackendType() == 'decimal')
                        ? $this->getValue() : $labelValue;
            } else {
                $value = $labelValue;
            }
            $query = array(
                $var => $value,
                Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
            );
            return Mage::helper('wsu_webmastertools')->getLayerFilterUrl(array('_current' => true, '_use_rewrite' => true, '_query' => $query));
        }
    }

    public function getRemoveUrl() {
        $request = Mage::app()->getRequest();
        if ($request->getModuleName() == 'catalogsearch') {
            return parent::getRemoveUrl();
        }

        $query = array($this->getFilter()->getRequestVar() => $this->getFilter()->getResetValue());
        $params['_current'] = true;
        $params['_use_rewrite'] = true;
        $params['_query'] = $query;
        $params['_escape'] = true;
        return Mage::helper('wsu_webmastertools')->getLayerFilterUrl($params);
    }

}
