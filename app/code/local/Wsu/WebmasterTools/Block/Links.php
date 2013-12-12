<?php
class Wsu_WebmasterTools_Block_Links extends Mage_Core_Block_Template {
    const XML_PATH_ADD_LINKS = 'webmastertools/sitemaper/add_links';
    const XML_PATH_SHOW_FOOTER_LINKS = 'webmastertools/sitemaper/show_footer_links';
    protected $_links;
    protected function _prepareLayout() {
        $links = array();
        if (Mage::getStoreConfigFlag(self::XML_PATH_SHOW_FOOTER_LINKS)) {
            $block = $this->getLayout()->getBlock('footer_links');
            if ($block) {
                $footerLinks = $block->getLinks();
                if (count($footerLinks)) {
                    foreach ($footerLinks as $link) {
                        $links[] = $link;
                    }
                }
            }
        }
        $addLinks = array_filter(preg_split('/\r?\n/', Mage::getStoreConfig(self::XML_PATH_ADD_LINKS)));
        if (count($addLinks)) {
            foreach ($addLinks as $link) {
                $_link = explode(',', $link, 2);
                if (count($_link) == 2) {
                    $links[] = new Varien_Object(array(
                        'label' => $_link[1],
                        'url' => Mage::getUrl((string) $_link[0])
                    ));
                }
            }
        }
        /* Leaved for compatibility with v1.0 */
        $xml = Mage::getStoreConfig(self::XML_PATH_ADD_LINKS);
        try {
            $xmlLinks = simplexml_load_string($xml);
        }
        catch (Exception $e) {
        }
        if (!empty($xmlLinks) && count($xmlLinks)) {
            foreach ($xmlLinks as $link) {
                $links[] = new Varien_Object(array(
                    'label' => (string) $link->text,
                    'url' => Mage::getUrl((string) $link->href)
                ));
            }
        }
        $this->setLinks($links);
        return $this;
    }
    public function getItemUrl($item) {
        return Mage::getUrl((string) $item->href);
    }
}
