<?php
/**
 * WebmasterTools data helper
 *
 * @category   Mage
 * @package    Wsu_WebmasterTools
 */
class Wsu_WebmasterTools_Helper_Blocks extends Mage_Core_Helper_Abstract{
    public function getGaPath($store = null)  {
		$accountId = Mage::getStoreConfig(Wsu_WebmasterTools_Helper_Data::XML_PATH_ACCOUNT);
		$googlesiteverifi = Mage::getStoreConfig(Wsu_WebmasterTools_Helper_Data::XML_PATH_GAVERIFI);
		$msvalidate = Mage::getStoreConfig(Wsu_WebmasterTools_Helper_Data::XML_PATH_MSVALIDATE);
		
		$fb_admin = Mage::getStoreConfig(Wsu_WebmasterTools_Helper_Data::XML_PATH_FBADMIN);
		list($protocal, $home_url) = preg_split('/\/\//',Mage::helper('core/url')->getHomeUrl());
		
		$html = "<!-- Webmaster tools -->";
		if ( !Mage::helper('core/cookie')->isUserNotAllowSaveCookie() && $accountId!="" ){
			$html .= "<script type='text/javascript' src='//images.wsu.edu/javascripts/tracking/bootstrap_v3.js?gacode=$accountId&amp;loading=element_v2&amp;domainName=$home_url'  id='tracker_agent'></script>";
		}
		
		if ($googlesiteverifi!=""){$html .= "<meta name='google-site-verification' content='$googlesiteverifi' />";}//<!--J5p8Yx8Li1yaN41fhtvl1zzJVoApq1t67l1WdFNff4c-->
		if ($msvalidate!=""){$html .= "<meta name='msvalidate.01' content='$msvalidate' />";}
		
		if ($fb_admin!=""){
			$fb_admin = Mage::getStoreConfig(Wsu_WebmasterTools_Helper_Data::XML_PATH_FBADMIN);
			
			$fb_og_image = Mage::getStoreConfig(Wsu_WebmasterTools_Helper_Data::XML_PATH_FBOG_IMAGE);//needs to proccess //'http://adultpleasures.xxx/skin/frontend/default/pleasures_v2/images/facebook_pleasures.jpg';
			$fb_og_site_name = Mage::getStoreConfig(Wsu_WebmasterTools_Helper_Data::XML_PATH_FBOG_SITENAME);
			$fb_type = Mage::getStoreConfig(Wsu_WebmasterTools_Helper_Data::XML_PATH_FBOG_TYPE);//needs to proccess 
			$fb_title = Mage::getStoreConfig(Wsu_WebmasterTools_Helper_Data::XML_PATH_FBOG_TITLE);//needs to proccess 
			
			
			$layout = Mage::getSingleton('core/layout');
			$title = ($fb_title!="") ? $fb_title : htmlspecialchars($layout ->getBlock('head')->getTitle());
			$description = htmlspecialchars($layout ->getBlock('head')->getDescription());
		
			$html .= "<meta property='fb:admins' content='$fb_admin' />";
			$html .= "<meta property='og:title' content='$title' />";
			if($fb_type!="")$html .= "<meta property='og:type' content='$fb_type' />";
			$html .= "<meta property='og:url' content='http://$home_url' />";
			if($fb_og_image!="")$html .= "<meta property='og:image' content='$fb_og_image' />";
			if($fb_og_site_name!="")$html .= "<meta property='og:site_name' content='$fb_og_site_name' />";
			if($description!="")$html .= "<meta property='og:description' content='$description' />";
		}

	$product = Mage::registry('current_product');
		// Don't show Previous and Next if product is not in any category
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
			
			
			 if($_prev_prod != NULL) $html .="<link rel='prev' title='" . $_prev_prod->getName() . "' href='".   $_prev_prod->getUrlPath() . "' />";
			 if($_next_prod != NULL) $html .="<link rel='next' title='" . $_next_prod->getName() . "' href='".   $_next_prod->getUrlPath() . "' />";
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
				if ($linkPrev) $html .='<link rel="prev" href="' . $prevUrl . '" />';
				if ($linkNext) $html .='<link rel="next" href="' . $nextUrl . '" />';
			}
	}

		$html .= "<!-- ENDOF:: Webmaster tools -->";
		return $html;		
		
		list($protocal, $home_url) = preg_split('/\/\//',Mage::helper('core/url')->getHomeUrl());
		$GAcode = Mage::getStoreConfig(Wsu_WebmasterTools_Helper_Data::XML_PATH_ACCOUNT);//'UA-41835019-1';//
		return '//images.wsu.edu/javascripts/tracking/bootstrap_v3.js?gacode='.$GAcode.'&amp;loading=element_v2&amp;domainName='.trim($home_url,'/');
    }
}
