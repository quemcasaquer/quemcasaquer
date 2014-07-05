<?php

class Magestore_Affiliateplus_Helper_Payment extends Mage_Core_Helper_Abstract
{
	const XML_PAYMENT_METHODS = 'affiliateplus_payment';
	
	/**
	 * Magestore_Affiliateplus_Helper_Payment::getPaymentMethod()
	 * 
	 * @param mixed $paymentMethodCode
	 * @param int $storeId
	 * @return Magestore_Affiliateplus_Model_Payment_Abstract
	 */
	public function getPaymentMethod($paymentMethodCode, $storeId = null){
		$modelPath = Mage::getStoreConfig(self::XML_PAYMENT_METHODS.'/'.$paymentMethodCode.'/model',$storeId);
		if (!$modelPath) throw new Mage_Core_Exception($this->__('Cannot find payment method configure!'));
		
		$paymentMethod = Mage::getModel($modelPath);
		if (!($paymentMethod instanceof Magestore_Affiliateplus_Model_Payment_Abstract))
			throw new Mage_Core_Exception($this->__('Payment model need is an abstract of class %s!','Magestore_Affiliateplus_Model_Payment_Abstract'));
		
		if ($storeId)
			$paymentMethod->setStoreId($storeId);
		return $paymentMethod;
    }
    
    /**
     * get All available payment method code
     *
     * @param int $storeId
     * @return array
     */
    public function getAvailablePaymentCode($storeId = null){
    	$allPaymentConfig = Mage::getStoreConfig(self::XML_PAYMENT_METHODS,$storeId);
    	$paymentCode = array();
    	foreach ($allPaymentConfig as $code => $config)
    		if ($config['active'])
    			$paymentCode[] = $code;
    	
    	return $paymentCode;
    }
    
    /**
     * get all available payment method
     *
     * @param int $storeId
     * @return array
     */
    public function getAvailablePayment($storeId = null){
    	$paymentCodes = $this->getAvailablePaymentCode($storeId);
    	$payments = array();
    	foreach ($paymentCodes as $paymentCode)
			try {
				$payments[$paymentCode] = $this->getPaymentMethod($paymentCode,$storeId);
			} catch (Exception $e){
				
			}
    	return $payments;
    }
    
    /**
     * get all payment method as an options array
     *
     * @return array
     */
    public function getPaymentOption(){
    	$allPaymentConfig = Mage::getStoreConfig(self::XML_PAYMENT_METHODS,$storeId);
    	$payments = array();
    	foreach ($allPaymentConfig as $code => $config)
    		if ($config['active'])
    			$payments[] = array(
    				'value'	=> $code,
    				'label'	=> $config['label'],
    			);
    	
    	return $payments;
    }
}