<?php

class Magestore_Affiliatepluspayment_Model_Bank extends Magestore_Affiliateplus_Model_Payment_Abstract
{
	protected $_code = 'bank';
	
	protected $_formBlockType = 'affiliatepluspayment/bank_form';
	protected $_infoBlockType = 'affiliatepluspayment/bank_info';
    
    /*event*/
    
    protected $_eventPrefix = 'affiliatepluspayment_bank';
    protected $_eventObject = 'affiliatepluspayment_bank';


    public function _construct(){
        parent::_construct();
        $this->_init('affiliatepluspayment/bank');
    }
    
    public function savePaymentMethodInfo(){
    	$payment = $this->getPayment();
    	if ($this->getBankBankaccountId()){
    		$bankAccount = Mage::getModel('affiliatepluspayment/bankaccount')->load($this->getBankBankaccountId());
    		$this->setBankaccountId($bankAccount->getId())
	    		->setBankaccountHtml($bankAccount->format(true));
    	}
    	$this->setInvoiceNumber($this->getBankInvoiceNumber())
    		->setMessage($this->getBankMessage());
    	$this->setPaymentId($payment->getId())->save();
		return parent::savePaymentMethodInfo();
    }
    
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
    
    public function calculateFee(){
    	return $this->getPayment()->getFee();
    }
    
    public function getInfoString(){
		return Mage::helper('affiliateplus/payment')->__('
			Method: %s \n
		',$this->getLabel());
	}
	
	public function getInfoHtml(){
		$html = Mage::helper('affiliateplus/payment')->__('Method: ');
		$html .= '<strong>'.$this->getLabel().'</strong><br />';
		return $html;
	}
    
    protected function _afterSave(){
        $payment = $this->getPayment();
        if($payment->getStatus()== 3){
            if($payment->getPaymentMethod() == 'bank'){
                $verify = Mage::getModel('affiliateplus/payment_verify')->loadExist($payment->getAccountId(), $this->getBankaccountId(), 'bank');
                if(!$verify->isVerified()){
                    try{
                    $verify->setVerified(1)
                            ->save();
                    }  catch (Exception $e){
                        
                    }
                }
            }
        }elseif ($payment->getStatus() == 1) {
            if($payment->getPaymentMethod() == 'bank'){
                $verify = Mage::getModel('affiliateplus/payment_verify')->loadExist($payment->getAccountId(), 0, 'bank');
                if($verify->getId()){
                    try{
                        $verify->setData('field',$this->getBankaccountId())
                            ->save();
                        
                    }  catch (Exception $e){
                        Zend_Debug::dump($e->getMessage());
                    }
                }
            }
        }
    }
}