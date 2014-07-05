<?php

class Magestore_Affiliateplus_Model_Payment_Paypal extends Magestore_Affiliateplus_Model_Payment_Abstract
{
	protected $_code = 'paypal';
	
	protected $_formBlockType = 'affiliateplus/payment_paypal';
	
    public function _construct(){
        parent::_construct();
        $this->_init('affiliateplus/payment_paypal');
    }
	
    public function calculateFee(){
    	return $this->getPayment()->getFee();
    }
    
    public function getInfoString(){
		return Mage::helper('affiliateplus/payment')->__('
			Method: %s \n
			Email: %s \n'.
			//Fee: %s \n
			'Transaction Id: %s \n
		',$this->getLabel()
		,$this->getEmail()
		//,$this->getFeePrice(false)
		,$this->getTransactionId());
	}
	
	public function getInfoHtml(){
		$html = Mage::helper('affiliateplus/payment')->__('Method: ');
		$html .= '<strong>'.$this->getLabel().'</strong><br />';
		$html .= Mage::helper('affiliateplus/payment')->__('Email: ');
		$html .= '<strong>'.$this->getEmail().'</strong><br />';
		//$html .= Mage::helper('affiliateplus/payment')->__('Fee: ');
		//$html .= '<strong>'.$this->getFeePrice(true).'</strong><br />';
		$html .= Mage::helper('affiliateplus/payment')->__('Transaction Id: ');
		$html .= '<strong>'.$this->getTransactionId().'</strong><br />';
		return $html;
	}
	
	/**
	 * load information of paypal payment method
	 *
	 * @return Magestore_Affiliateplus_Model_Payment_Paypal
	 */
	public function loadPaymentMethodInfo(){
		if ($this->getPayment()){
			$paymentInfo = $this->getCollection()
				->addFieldToFilter('payment_id',$this->getPayment()->getId())
				->getFirstItem();
			if ($paymentInfo)
				$this->addData($paymentInfo->getData())->setId($paymentInfo->getId());
		}
		return parent::loadPaymentMethodInfo();
	}
	
	/**
	 * Save Payment Method Information
	 *
	 * @return Magestore_Affiliateplus_Model_Payment_Abstract
	 */
	public function savePaymentMethodInfo(){
		$this->setPaymentId($this->getPayment()->getId())->save();
		return parent::savePaymentMethodInfo();
	}
}