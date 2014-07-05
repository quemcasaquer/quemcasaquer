<?php
class Magestore_Affiliateplus_Block_Payment_Paypal extends Magestore_Affiliateplus_Block_Payment_Form
{
	public function _prepareLayout(){
		parent::_prepareLayout();
		$this->setTemplate('affiliateplus/payment/paypal.phtml');
		return $this;
    }
    
    public function getAcount(){
    	return Mage::getSingleton('affiliateplus/session')->getAccount();
    }
}