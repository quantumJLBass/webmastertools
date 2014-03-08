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
	
	
}
