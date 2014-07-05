<?php
class Magestore_Affiliateplus_IndexController extends Mage_Core_Controller_Front_Action
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
	 * get Affiliate Payment Helper
	 *
	 * @return Magestore_Affiliateplus_Helper_Payment
	 */
	protected function _getPaymentHelper(){
		return Mage::helper('affiliateplus/payment');
	}
	
	/**
	 * get Core Session
	 *
	 * @return Mage_Core_Model_Session
	 */
	protected function _getCoreSession(){
		return Mage::getSingleton('core/session');
	}
	
    public function indexAction(){
		if(!Mage::helper('magenotification')->checkLicenseKeyFrontController($this)){ return; }
    	if ($this->_getAccountHelper()->isRegistered() && $this->_getAccountHelper()->accountNotLogin()){
    		if ($this->_getAccountHelper()->getAccount()->getApproved() == 1)
    			$this->_getCoreSession()->addError($this->_getHelper()->__('Your affiliate account is blocked. Please contact us to get our help.'));
    		elseif (!$this->_getCoreSession()->getData('has_been_signup')) 
    			$this->_getCoreSession()->addNotice($this->_getHelper()->__('Your affiliate account has not been approved. Please wait for our approval.'));
    	}
		$this->loadLayout();
		$page = Mage::getSingleton('cms/page');
		if ($page->getId())
			$this->getLayout()->getBlock('head')->setTitle($page->getContentHeading());
		else
			$this->getLayout()->getBlock('head')->setTitle(Mage::helper('affiliateplus')->__('Affiliate Home'));
		$this->renderLayout();
    }
    
    public function materialsAction(){
		if(!Mage::helper('magenotification')->checkLicenseKeyFrontController($this)){ return; }
    	if ($this->_getConfigHelper()->disableMaterials())
    		return $this->_redirect('*/*/');
    	$this->loadLayout();
		$page = Mage::getSingleton('cms/page');
		if ($page->getId())
			$this->getLayout()->getBlock('head')->setTitle($page->getContentHeading());
		else
			$this->getLayout()->getBlock('head')->setTitle(Mage::helper('affiliateplus')->__('Materials'));
    	$this->renderLayout();
    }
    
    public function listTransactionAction(){
		if(!Mage::helper('magenotification')->checkLicenseKeyFrontController($this)){ return; }
    	if ($this->_getAccountHelper()->accountNotLogin())
    		return $this->_redirect('affiliateplus/account/login');
    	$this->loadLayout();
    	//$this->getLayout()->getBlock('head')->setTitle($this->__('Commissions'));
    	$this->renderLayout();
    }
    
    public function paymentsAction(){
		if(!Mage::helper('magenotification')->checkLicenseKeyFrontController($this)){ return; }
    	if ($this->_getAccountHelper()->accountNotLogin())
    		return $this->_redirect('affiliateplus/account/login');
    	$this->loadLayout();
    	$this->getLayout()->getBlock('head')->setTitle($this->__('Withdrawals'));
    	$this->renderLayout();
    }
    
    public function viewPaymentAction(){
		if(!Mage::helper('magenotification')->checkLicenseKeyFrontController($this)){ return; }
    	if ($this->_getAccountHelper()->accountNotLogin())
    		return $this->_redirect('affiliateplus/account/login');
    	
    	$paymentId = $this->getRequest()->getParam('id');
    	$payment = Mage::getModel('affiliateplus/payment')->load($paymentId);
    	if ($payment->getAccountId() != Mage::getSingleton('affiliateplus/session')->getAccount()->getId()){
    		$this->_getCoreSession()->addError($this->__('Payment not found!'));
    		return $this->_redirect('affiliateplus/index/payments');
    	}
    	Mage::register('view_payment_data',$payment);
    	$this->loadLayout();
    	$this->getLayout()->getBlock('head')->setTitle($this->__('View Invoice'));
    	$this->renderLayout();
    }
    
    public function paymentFormAction(){
		if(!Mage::helper('magenotification')->checkLicenseKeyFrontController($this)){ return; }
    	if ($this->_getAccountHelper()->accountNotLogin())
    		return $this->_redirect('affiliateplus/account/login');
    	if (!$this->_getAccountHelper()->isEnoughBalance()){
    		$baseCurrency = Mage::app()->getStore()->getBaseCurrency();
    		$this->_getCoreSession()->addNotice($this->__('Minimum balance to request payment is %s'
    			,$baseCurrency->format($this->_getConfigHelper()->getPaymentConfig('payment_release'),array(),false)));
    		return $this->_redirect('affiliateplus/index/listTransaction');
    	}
    	$this->loadLayout();
    	$this->getLayout()->getBlock('head')->setTitle($this->__('Request Payment'));
    	$this->renderLayout();
    }
    
    public function requestPaymentAction(){
		if(!Mage::helper('magenotification')->checkLicenseKeyFrontController($this)){ return; }
    	if ($this->_getAccountHelper()->accountNotLogin())
    		return $this->_redirect('affiliateplus/account/login');
    	if(!$this->getRequest()->isPost())
    		return $this->_redirect('affiliateplus/index/paymentForm');
    	
    	$paymentCodes = $this->_getPaymentHelper()->getAvailablePaymentCode();
    	
    	if (!count($paymentCodes)){
    		$this->_getCoreSession()->addError($this->__('There is no payment method in the system. Please contact us to solve the problem.'));
    		return $this->_redirect('affiliateplus/index/payments');
    	}elseif (count($paymentCodes) == 1){
    		$paymentCode = $this->getRequest()->getParam('payment_method');
    		if (!$paymentCode) $paymentCode = current($paymentCodes);
    	}else
	    	$paymentCode = $this->getRequest()->getParam('payment_method');
    	
		if(!$paymentCode){
			$this->_getCoreSession()->addNotice($this->__('Please chose an available payment method!'));
			return $this->_redirect('affiliateplus/index/paymentForm',$this->getRequest()->getPost());
		}
		
    	if (!in_array($paymentCode,$paymentCodes)){
    		$this->_getCoreSession()->addError($this->__('This payment method not available, please chose an available payment method!'));
			return $this->_redirect('affiliateplus/index/paymentForm',$this->getRequest()->getPost());
    	}
    	$account = $this->_getAccountHelper()->getAccount();
    	$store = Mage::app()->getStore();
    	
    	$amount = $this->getRequest()->getParam('amount');
    	$amount = $amount / $store->convertPrice(1);
    	if ($amount < $this->_getConfigHelper()->getPaymentConfig('payment_release')){
			$this->_getCoreSession()->addNotice($this->__('Minimum balance to request payment is %s'
				,Mage::helper('core')->currency($this->_getConfigHelper()->getPaymentConfig('payment_release'),true,false)));
    		return $this->_redirect('affiliateplus/index/paymentForm');
    	}
		
		if($amount > $account->getBalance()){
			$this->_getCoreSession()->addError($this->__('The amount of the payment request cannot exceed your balance: %s.'
    			,Mage::helper('core')->currency($account->getBalance(),true,false)));
				
			return $this->_redirect('affiliateplus/index/paymentForm');
		}
    	
    	$payment = Mage::getModel('affiliateplus/payment')
    		->setPaymentMethod($paymentCode)
    		->setAmount($amount)
    		->setAccountId($account->getId())
    		->setAccountName($account->getName())
    		->setAccountEmail($account->getEmail())
    		->setRequestTime(now())
    		->setStatus(1)
    		->setIsRequest(1)
    		->setIsPayerFee(0);
    	if ($this->_getConfigHelper()->getPaymentConfig('who_pay_fees') == 'payer' && $paymentCode == 'paypal')
    		$payment->setIsPayerFee(1);
    	
    	if ($payment->hasWaitingPayment()){
    		$this->_getCoreSession()->addError($this->__('You are having a waiting request!'));
    		return $this->_redirect('affiliateplus/index/payments');
    	}
    	
    	if ($this->_getConfigHelper()->getSharingConfig('balance') == 'store')
    		$payment->setStoreIds($store->getId());
    	
    	$paymentMethod = $payment->getPayment();
    	
    	$paymentObj = new Varien_Object(array(
    		'payment_code'	=> $paymentCode,
    		'required'		=> true,
    	));
    	Mage::dispatchEvent("affiliateplus_request_payment_action_$paymentCode",array(
    		'payment_obj'	=> $paymentObj,
    		'payment'		=> $payment,
    		'payment_method'=> $paymentMethod,
    		'request'		=> $this->getRequest(),
    	));
    	$paymentCode = $paymentObj->getPaymentCode();
    	
    	if ($paymentCode == 'paypal'){
    		$paypalEmail = $this->getRequest()->getParam('paypal_email');
    		
    		//Change paypal email for affiliate account
    		if ($paypalEmail && $paypalEmail != $account->getPaypalEmail()){
    			$accountModel = Mage::getModel('affiliateplus/account')
	    			->setStoreId($store->getId())
	    			->load($account->getId());
	    		try {
	    			$accountModel->setPaypalEmail($paypalEmail)
	    				->setId($account->getId())
	    				->save();
	    		}catch (Exception $e){}
    		}
    		
    		$paypalEmail = $paypalEmail ? $paypalEmail : $account->getPaypalEmail();
    		if ($paypalEmail){
    			$paymentMethod->setEmail($paypalEmail);
    			$paymentObj->setRequired(false);
    		}
    	}
    	
    	if ($paymentObj->getRequired()){
    		$this->_getCoreSession()->addNotice($this->__('Please complete required fields in form below.'));
    		return $this->_redirect('affiliateplus/index/paymentForm',$this->getRequest()->getPost());
    	}
    	
    	// Save request payment for affiliate account
    	try {
    		$payment->save();
    		$paymentMethod->savePaymentMethodInfo();
    		$payment->sendMailRequestPaymentToSales();
    		$this->_getCoreSession()->addSuccess($this->__('Your request has been sent successfully!'));
    	}catch (Exception $e){
    		$this->_getCoreSession()->addError($e->getMessage());
    	}
    	
    	return $this->_redirect('affiliateplus/index/payments');
    }
    
    public function referrersAction(){
		if(!Mage::helper('magenotification')->checkLicenseKeyFrontController($this)){ return; }
    	if ($this->_getAccountHelper()->accountNotLogin())
    		return $this->_redirect('affiliateplus/account/login');
    	$this->loadLayout();
    	$this->getLayout()->getBlock('head')->setTitle($this->__('Clicks'));
    	$this->renderLayout();
    }
	
	public function listCategoriesAction(){
		if(!Mage::helper('magenotification')->checkLicenseKeyFrontController($this)){ return; }
		$this->_redirectUrl(Mage::getBaseUrl());
	}
}