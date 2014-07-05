<?php

class Magestore_Affiliateplus_Helper_Config extends Mage_Core_Helper_Abstract
{
	public function getGeneralConfig($code, $store = null){
		return Mage::getStoreConfig('affiliateplus/general/'.$code,$store);
	}
	
	public function getPaymentConfig($code, $store = null){
		return Mage::getStoreConfig('affiliateplus/payment/'.$code,$store);
	}
	
	public function getEmailConfig($code, $store = null){
		return Mage::getStoreConfig('affiliateplus/email/'.$code,$store);
	}
	
	public function getSharingConfig($code, $store = null){
		return Mage::getStoreConfig('affiliateplus/sharing/'.$code,$store);
	}
	
	public function getMaterialConfig($code, $store = null){
		return Mage::getStoreConfig('affiliateplus/material/'.$code,$store);
	}
	
	public function disableMaterials(){
		return (Mage::helper('affiliateplus/account')->accountNotLogin() || !$this->getMaterialConfig('enable'));
	}
	
	public function getReferConfig($code, $store = null){
		return Mage::getStoreConfig('affiliateplus/refer/'.$code,$store);
	}
}