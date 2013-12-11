<?php
class Wsu_WebmasterTools_IndexController
	extends Mage_Adminhtml_Controller_Action {
	public function indexAction() {
		try {
			$id = $this->getRequest()->getParam('sitemap_id');
			$obj = Mage::getModel('webmastertools/submit');
			$msg = $obj->submit($id);
			Mage::getSingleton('adminhtml/session')->addSuccess($msg);
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirectReferer();
	}

} 
