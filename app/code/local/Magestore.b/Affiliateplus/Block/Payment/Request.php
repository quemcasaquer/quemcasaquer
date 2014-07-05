<?php
class Magestore_Affiliateplus_Block_Payment_Request extends Mage_Core_Block_Template
{
	/**
	 * Get Affiliate Payment Helper
	 *
	 * @return Magestore_Affiliateplus_Helper_Payment
	 */
	protected function _getPaymentHelper(){
		return Mage::helper('affiliateplus/payment');
	}
	
	public function _prepareLayout(){
		parent::_prepareLayout();
		
		$layout = $this->getLayout();
		$paymentMethods = $this->getAllPaymentMethod();
		foreach ($paymentMethods as $code => $method){
			$paymentMethodFormBlock = $layout->createBlock($method->getFormBlockType(),"payment_method_form_$code")->setPaymentMethod($method);
			$this->setChild("payment_method_form_$code",$paymentMethodFormBlock);
		}
		
		return $this;
    }
    
    public function getAllPaymentMethod(){
    	if (!$this->hasData('all_payment_method')){
    		$this->setData('all_payment_method',$this->_getPaymentHelper()->getAvailablePayment());
    	}
    	return $this->getData('all_payment_method');
    }
    
    public function getAmount(){
    	return $this->getRequest()->getParam('amount');
    }
    
    /**
     * get Current Affiliate Account
     *
     * @return Magestore_Affiliateplus_Model_Account
     */
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