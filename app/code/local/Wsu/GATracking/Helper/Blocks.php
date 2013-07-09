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
 * @package     Wsu_GATracking
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * GATracking data helper
 *
 * @category   Mage
 * @package    Wsu_GATracking
 */
class Wsu_GATracking_Helper_Blocks extends Mage_Core_Helper_Abstract{
    public function getGaPath($store = null)
    {
		list($protocal, $home_url) = preg_split('/\/\//',Mage::helper('core/url')->getHomeUrl());
		$GAcode = Mage::getStoreConfig(Wsu_GATracking_Helper_Data::XML_PATH_ACCOUNT);//'UA-41835019-1';//
		return '//images.wsu.edu/javascripts/tracking/bootstrap_v3.js?gacode='.$GAcode.'&amp;loading=element_v2&amp;domainName='.trim($home_url,'/');
    }
}
