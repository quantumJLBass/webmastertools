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
 * Google Analytics module observer
 *
 * @category   Mage
 * @package    Wsu_WebmasterTools
 */
class Wsu_WebmasterTools_Model_Observer
{
    /**
     * Whether the google checkout inclusion link was rendered by this observer instance
     * @var bool
     */
    protected $_isGoogleCheckoutLinkAdded = false;




	public function addblock(Varien_Event_Observer $observer){
/** @var $_block Mage_Core_Block_Abstract */
        /*Get block instance*/
        //$_block = $observer->getBlock();
        /*get Block type*/
        //$_block->setTemplate('webmastertools/ga.phtml');
		 
		 /*
		//Get current layout state
		$layout = Mage::getSingleton('core/layout');
		$block              = $observer->getBlock();
        $transport          = $observer->getTransport();

		$block = $layout->createBlock(
			'Mage_Core_Block_Template',
			'my_block_name_here',
			array('template' => 'webmastertools/ga.phtml')
		)->getHtml();
		//Release layout stream... lol... sounds fancy
		$html = $transport->getHtml();
        $html = $html . $block;
        $transport->setHtml($html);
*/
			$layout = $observer->getEvent()->getLayout()->getUpdate();
            $layout->addHandle('add_my_block');
            return $this;

		 
	}



    /**
     * Create Google Analytics block for success page view
     *
     * @deprecated after 1.3.2.3 Use setWebmasterToolsOnOrderSuccessPageView() method instead
     * @param Varien_Event_Observer $observer
     */
    public function order_success_page_view($observer)
    {
        $this->setWebmasterToolsOnOrderSuccessPageView($observer);
    }

    /**
     * Add order information into GA block to render on checkout success pages
     *
     * @param Varien_Event_Observer $observer
     */
    public function setWebmasterToolsOnOrderSuccessPageView(Varien_Event_Observer $observer)
    {
        $orderIds = $observer->getEvent()->getOrderIds();
        if (empty($orderIds) || !is_array($orderIds)) {
            return;
        }
        $block = Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('google_analytics');
        if ($block) {
            $block->setOrderIds($orderIds);
        }
    }

    /**
     * Add google analytics tracking to google checkout shortcuts
     *
     * If there is at least one GC button on the page, there should be the script for GA/GC integration included
     * a each shortcut should track submits to GA
     * There should be no tracking if there is no GA available
     * This method assumes that the observer instance is run as a "singleton" (through Mage::getSingleton())
     *
     * @param Varien_Event_Observer $observer
     */
    public function injectAnalyticsInGoogleCheckoutLink(Varien_Event_Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if (!$block || !Mage::helper('webmastertools')->isWebmasterToolsAvailable()) {
            return;
        }

        // make sure to track google checkout "onsubmit"
        $onsubmitJs = $block->getOnsubmitJs();
        $block->setOnsubmitJs($onsubmitJs . ($onsubmitJs ? '; ' : '') . '_gaq.push(function() {var pageTracker = _gaq._getAsyncTracker(); setUrchinInputCode(pageTracker);});');

        // add a link that includes google checkout/analytics script, to the first instance of the link block
        if ($this->_isGoogleCheckoutLinkAdded) {
            return;
        }
        $beforeHtml = $block->getBeforeHtml();
        $protocol = Mage::app()->getStore()->isCurrentlySecure() ? 'https' : 'http';
        $block->setBeforeHtml($beforeHtml . '<script src="' . $protocol
            . '://checkout.google.com/files/digital/ga_post.js" type="text/javascript"></script>'
        );
        $this->_isGoogleCheckoutLinkAdded = true;
    }
}
