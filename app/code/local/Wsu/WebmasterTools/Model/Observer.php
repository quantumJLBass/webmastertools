<?php
/**
 * Google Analytics module observer
 *
 * @category   Mage
 * @package    Wsu_WebmasterTools
 */
class Wsu_WebmasterTools_Model_Observer {
    /**
     * Whether the google checkout inclusion link was rendered by this observer instance
     * @var bool
     */
    protected $_isGoogleCheckoutLinkAdded = false;
    const XML_PATH_ROUTES = 'global/custom_urls';
    const XML_PATH_FRONT_NAME = 'web/custom_urls/%s_url';
    public function handleInitRouters(Varien_Event_Observer $observer) {
        /** @var $frontController Mage_Core_Controller_Varien_Front */
        $frontController = $observer->getFront();
        $frontController->addRouter('customurls', new Wsu_WebmasterTools_Controller_Router());
    }
    public function handleGetUrl(Varien_Event_Observer $observer) {
        $params          = $observer->getParams();
        $availableRoutes = $this->getAvailableRoutes();
        $routeToMatch    = trim($params->routePath, '/');
        if (substr_count($routeToMatch, '/') === 1) {
            $routeToMatch .= '/index';
        }
        foreach ($availableRoutes as $availableRoute) {
            if ((string) $availableRoute->route == $routeToMatch) {
                if (isset($availableRoute->params)) {
                    $hasChild = false;
                    foreach ($availableRoute->params->children() as $param) {
                        $hasChild = true;
                        if (!isset($params->routeParams[$param->getName()]) || $params->routeParams[$param->getName()] != (string) $param) {
                            continue 2;
                        }
                    }
                    if (!$hasChild && $params->routeParams) {
                        continue;
                    }
                }
                $params->routePath   = null;
                $params->routeParams = array(
                    '_direct' => $this->getUserDefinedRouteFrontName($availableRoute)
                );
                break;
            }
        }
    }
    protected function getAvailableRoutes() {
        return Mage::getConfig()->getNode(self::XML_PATH_ROUTES)->children();
    }
    protected function getUserDefinedRouteFrontName($route) {
        return Mage::getStoreConfig(sprintf(self::XML_PATH_FRONT_NAME, $route->getName()));
    }
    public function addblock(Varien_Event_Observer $observer) {
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
    public function order_success_page_view($observer) {
        $this->setWebmasterToolsOnOrderSuccessPageView($observer);
    }
    /**
     * Add order information into GA block to render on checkout success pages
     *
     * @param Varien_Event_Observer $observer
     */
    public function setWebmasterToolsOnOrderSuccessPageView(Varien_Event_Observer $observer) {
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
    public function injectAnalyticsInGoogleCheckoutLink(Varien_Event_Observer $observer) {
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
        $protocol   = Mage::app()->getStore()->isCurrentlySecure() ? 'https' : 'http';
        $block->setBeforeHtml($beforeHtml . '<script src="' . $protocol . '://checkout.google.com/files/digital/ga_post.js" type="text/javascript"></script>');
        $this->_isGoogleCheckoutLinkAdded = true;
    }
}
