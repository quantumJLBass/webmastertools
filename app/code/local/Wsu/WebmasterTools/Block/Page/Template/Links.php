<?php
class Wsu_WebmasterTools_Block_Page_Template_Links extends Mage_Page_Block_Template_Links {
    public function removeLinkByUrl($url) {
        foreach ($this->_links as $k => $v) {
            if ($v->getUrl() == $url) {
                unset($this->_links[$k]);
            }
        }
        return $this;
    }
}