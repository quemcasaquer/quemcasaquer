<?php
class Magestore_Affiliateplus_BannerController extends Mage_Core_Controller_Front_Action
{
	/**
	 * get Account helper
	 *
	 * @return Magestore_Affiliateplus_Helper_Account
	 */
	protected function _getAccountHelper(){
		return Mage::helper('affiliateplus/account');
	}
	
	/**
	 * get Affiliateplus helper
	 *
	 * @return Magestore_Affiliateplus_Helper_Data
	 */
	protected function _getHelper(){
		return Mage::helper('affiliateplus');
	}
	
	/**
	 * getConfigHelper
	 *
	 * @return Magestore_Affiliateplus_Helper_Config
	 */
	protected function _getConfigHelper(){
		return Mage::helper('affiliateplus/config');
	}
	
	/**
	 * get Core Session
	 *
	 * @return Mage_Core_Model_Session
	 */
	protected function _getCoreSession(){
		return Mage::getSingleton('core/session');
	}
	
    public function listAction(){
    	if ($this->_getAccountHelper()->accountNotLogin()){
    		return $this->_redirect('affiliateplus');
    	}
    	
		$this->loadLayout();
		$this->getLayout()->getBlock('head')->setTitle(Mage::helper('affiliateplus')->__('Banners & Links'));
		$this->renderLayout();
    }
}