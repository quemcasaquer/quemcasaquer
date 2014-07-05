<?php
class Magestore_Affiliateplus_Block_Payment_Miniform extends Mage_Core_Block_Template
{
	public function _prepareLayout(){
		parent::_prepareLayout();
		$this->setTemplate('affiliateplus/payment/miniform.phtml');
		return $this;
    }
    
    public function getAccount(){
    	return Mage::getSingleton('affiliateplus/session')->getAccount();
    }
    
    public function getBalance(){
    	return round(Mage::app()->getStore()->convertPrice($this->getAccount()->getBalance()),2);
    }
    
    public function getFormatedBalance(){
    	return Mage::helper('core')->currency($this->getAccount()->getBalance());
    }
    
    public function getFormActionUrl(){
    	return $this->getUrl('affiliateplus/index/requestPayment');
    }
}