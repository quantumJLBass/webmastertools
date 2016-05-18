<?php
/**
 * WebmasterTools data helper
 *
 * @category   Mage
 * @package    Wsu_WebmasterTools
 */
class Wsu_WebmasterTools_Helper_Data extends Mage_Core_Helper_Abstract {
    /**
     * Config paths for using throughout the code
     */
    const XML_PATH_ACTIVE  = 'wsu_webmastertools/analytics/active';
    const XML_PATH_ACCOUNT = 'wsu_webmastertools/analytics/account';
    const XML_PATH_SITEGACODE = 'wsu_webmastertools/analytics/site_ga_code';
	const XML_PATH_GAVERIFI = 'wsu_webmastertools/analytics/ga_verifi';
	const XML_PATH_MSVALIDATE = 'wsu_webmastertools/analytics/msvalidate';
	const XML_PATH_MS_APIKEY = 'wsu_webmastertools/analytics/ms_apikey';
	const XML_PATH_FBADMIN = 'wsu_webmastertools/analytics/fb_admin';
	const XML_PATH_FBOG_IMAGE = 'wsu_webmastertools/analytics/fb_og_iamge';
	const XML_PATH_FBOG_SITENAME = 'wsu_webmastertools/analytics/fb_og_sitename';
	const XML_PATH_FBOG_TYPE = 'wsu_webmastertools/analytics/fb_og_type';
	const XML_PATH_FBOG_TITLE = 'wsu_webmastertools/analytics/fb_og_title';

    /**
     * Whether GA is ready to use
     *
     * @param mixed $store
     * @return bool
     */
    public function isWebmasterToolsAvailable($store = null) {
        $accountId = Mage::getStoreConfig(self::XML_PATH_ACCOUNT, $store);
        return $accountId && Mage::getStoreConfigFlag(self::XML_PATH_ACTIVE, $store);
    }
	
	public function isSubmissionEnabled() {
		return Mage::getStoreConfig('wsu_webmastertools/sitemapsubmission/enabled');
	}
	public function isAutoSubmit() {
		return Mage::getStoreConfig('wsu_webmastertools/sitemapsubmission/autosubmit');
	}
	
	public function getYahooKey() {
		return Mage::getStoreConfig('wsu_webmastertools/sitemapsubmission/yahoo_key');
	}
    public function getDateForFilename() {
        return Mage::getSingleton('core/date')->date('Y-m-d_H-i-s');
    }
    public function getSitemapUrl() {
        return Mage::getUrl('sitemap');
    }
	
    public function getConfig($path,$store = null,$default = null) {
        $value = trim(Mage::getStoreConfig("wsu_webmastertools/$path", $store));
        return (!isset($value) || $value == '')? $default : $value ;
    }
	
    public function getNextPrev(){
        $product = Mage::registry('current_product');
            // Don't show Previous and Next if product is not in any category
        $links = false;
        if($product ){
            $_product= Mage::getModel('catalog/product')->load($product->getId());
            if($_product && $_product->getCategoryIds()){
                $cat_ids = $_product->getCategoryIds(); // get all categories where the product is located
                $cat = Mage::getModel('catalog/category')->load( $cat_ids[0] ); // load first category, you should enhance this, it works for me
                
                $order = Mage::getStoreConfig('catalog/frontend/default_sort_by');
                $direction = 'asc'; // asc or desc
                
                $category_products = $cat->getProductCollection()->addAttributeToSort($order, $direction);
                $category_products->addAttributeToFilter('status',1); // 1 or 2
                $category_products->addAttributeToFilter('visibility',4); // 1.2.3.4
                
                $cat_prod_ids = $category_products->getAllIds(); // get all products from the category
                $_product_id = $_product->getId();
                
                $_pos = array_search($_product_id, $cat_prod_ids); // get position of current product
                $_next_pos = $_pos+1;
                $_prev_pos = $_pos-1;
                
                // get the next product url
                if( isset($cat_prod_ids[$_next_pos]) ) {
                    $_next_prod = Mage::getModel('catalog/product')->load( $cat_prod_ids[$_next_pos] );
                } else {
                    $_next_prod = Mage::getModel('catalog/product')->load( reset($cat_prod_ids) );
                }
                // get the previous product url
                if( isset($cat_prod_ids[$_prev_pos]) ) {
                    $_prev_prod = Mage::getModel('catalog/product')->load( $cat_prod_ids[$_prev_pos] );
                } else {
                    $_prev_prod = Mage::getModel('catalog/product')->load( end($cat_prod_ids) );
                }
                $links["prevUrl"] = $_prev_prod->getUrlPath();
                $links["nextUrl"] = $_next_prod->getUrlPath();
                $links["prevTitle"] = $_prev_prod->getName();
                $links["nextTitle"] = $_next_prod->getName();
            }
        }else{
                $category = Mage::registry('current_category');
                if($category){
                    $prodCol = $category->getProductCollection()->addAttributeToFilter('status', 1)->addAttributeToFilter('visibility', array('in' => array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG, Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)));
                    $layout = Mage::getSingleton('core/layout');
                    $tool = $layout ->createBlock('page/html_pager')->setLimit($layout ->createBlock('catalog/product_list_toolbar')->getLimit())->setCollection($prodCol);
                    $linkPrev = false;
                    $linkNext = false;
                    if ($tool->getCollection()->getSelectCountSql()) {
                        if ($tool->getLastPageNum() > 1) {
                            if (!$tool->isFirstPage()) {
                                $linkPrev = true;
                                if ($tool->getCurrentPage() == 2) {
                                    $url = explode('?', $tool->getPreviousPageUrl());
                                    $prevUrl = @$url[0];
                                }
                                else {
                                    $prevUrl = $tool->getPreviousPageUrl();
                                }
                            }
                            if (!$tool->isLastPage()) {
                                $linkNext = true;
                                $nextUrl = $tool->getNextPageUrl();
                            }
                        }
                    }
                    $links["prevUrl"] = $prevUrl;
                    $links["nextUrl"] = $nextUrl;
                }
        }
        return $links;
    }	
    
    public function buildEcDetails($product){
        //$_product= Mage::getModel('catalog/product')->load($product->getId());
        $ec="{
                data:{
                    type:'addProduct',
                    data:{
                        id:'".$product->getSku()."',
                        name:'".$product->getName()."',
                        brand:'WSU',
                        category:'".$product->getCategory()->getParentCategory()."',
                        price:'".$product->getFinalPrice()."'
                    }
                },
                action:{
                    type:'detail'
                }
            }";
            return $ec;
    }
    public function getEcObject()
    {
        $product = Mage::registry('current_product');
        if($product){
            $ec=$this->buildEcDetails($product);
        }else{
            $ec=true;
        }
        return $ec;
    }
    
    
    
}
