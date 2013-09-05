<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Wsu_WebmasterTools
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * WebmasterTools data helper
 *
 * @category   Mage
 * @package    Wsu_WebmasterTools
 */
class Wsu_WebmasterTools_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Config paths for using throughout the code
     */
    const XML_PATH_ACTIVE  = 'webmastertools/analytics/active';
    const XML_PATH_ACCOUNT = 'webmastertools/analytics/account';
	const XML_PATH_GAVERIFI = 'webmastertools/analytics/ga_verifi';
	const XML_PATH_MSVALIDATE = 'webmastertools/analytics/msvalidate';
	const XML_PATH_MS_APIKEY = 'webmastertools/analytics/ms_apikey';
	const XML_PATH_FBADMIN = 'webmastertools/analytics/fb_admin';
	const XML_PATH_FBOG_IMAGE = 'webmastertools/analytics/fb_og_iamge';
	const XML_PATH_FBOG_SITENAME = 'webmastertools/analytics/fb_og_sitename';
	const XML_PATH_FBOG_TYPE = 'webmastertools/analytics/fb_og_type';
	const XML_PATH_FBOG_TITLE = 'webmastertools/analytics/fb_og_title';

    /**
     * Whether GA is ready to use
     *
     * @param mixed $store
     * @return bool
     */
    public function isWebmasterToolsAvailable($store = null)
    {
        $accountId = Mage::getStoreConfig(self::XML_PATH_ACCOUNT, $store);
        return $accountId && Mage::getStoreConfigFlag(self::XML_PATH_ACTIVE, $store);
    }
}
