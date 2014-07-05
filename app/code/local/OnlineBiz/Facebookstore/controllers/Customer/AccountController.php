<?php

/**
 * Export Product data to xml data
 *
 * @category   OnlineBiz
 * @package    OnlineBiz_Facebookstore
 * @author     Nguyen Viet Tung (http://onlinebizsoft.com)
 */
class OnlineBiz_Facebookstore_Customer_AccountController extends Mage_Core_Controller_Front_Action
{
	
    public function preDispatch()
    {
        parent::preDispatch();

        if (!Mage::getSingleton('facebookstore/config')->isEnabled()) {
            $this->norouteAction();
            return;
        }
    }	
	public function _getRefererUrl()
	{
		$url = parent::_getRefererUrl();
		if($url==Mage::getBaseUrl())
			$url .= "facebookstore"; 
		return $url;
	}
	public function connectAction()
    {
    	if(!$this->_getSession()->validate()) {
    		$this->_getCustomerSession()->addError($this->__('Facebook connection failed.'));
    		//$this->_redirect('customer/account');
			$this->_redirectReferer($this->_getRefererUrl());
    		return;
    	}
    	
    	//login or connect
    	
    	$customer = Mage::getModel('customer/customer');
    	
    	$collection = $customer->getCollection()
    	 			->addAttributeToFilter('facebook_uid', (string)$this->_getSession()->getUid())
    				->setPageSize(1);
    				
    	if($customer->getSharingConfig()->isWebsiteScope()) {
            $collection->addAttributeToFilter('website_id', Mage::app()->getWebsite()->getId());
        }
        
        if($this->_getCustomerSession()->isLoggedIn()) {
        	$collection->addFieldToFilter('entity_id', array('neq' => $this->_getCustomerSession()->getCustomerId()));
        }
        
        $uidExist = (bool)$collection->count();
        
        if($this->_getCustomerSession()->isLoggedIn() && $uidExist) {
        	$existingCustomer = $collection->getFirstItem();
			$existingCustomer->setFacebookUid('');
        	$existingCustomer->getResource()->saveAttribute($existingCustomer, 'facebook_uid');
        }
        	
		if($this->_getCustomerSession()->isLoggedIn()) {
       		$currentCustomer = $this->_getCustomerSession()->getCustomer();
 			$currentCustomer->setFacebookUid($this->_getSession()->getUid());
			$currentCustomer->getResource()->saveAttribute($currentCustomer, 'facebook_uid');        	
			
			$this->_getCustomerSession()->addSuccess(
				$this->__('Your Facebook account has been successfully connected. Now you can fast login using Facebook Connect anytime.')
			);
			$this->_redirectReferer($this->_getRefererUrl());
			return;
        }
        
        if($uidExist) {
        	$uidCustomer = $collection->getFirstItem();
        	//additional fix:
			$uidCustomer->setFacebookUid($this->_getSession()->getUid());
			$uidCustomer->setFacebookToken(json_encode($this->_getSession()->getData()));
			if($uidCustomer->getConfirmation()){
				$uidCustomer->setConfirmation(null);
			}
			$uidCustomer->save();
			$this->_getCustomerSession()->setCustomerAsLoggedIn($uidCustomer);
			$this->_getCustomerSession()->addSuccess(
				$this->__('Your Facebook account has been successfully connected.')
			);
			$this->_redirectReferer($this->_getRefererUrl());
			return;        	
        }
        
		        
        try{
        	$standardInfo = $this->_getSession()->getClient()->call("/me");
        	
		}catch(Mage_Core_Exception $e){
    		$this->_getCustomerSession()->addError(
    			$this->__('Facebook connection failed.') .
    			' ' . 
    			$this->__('Service temporarily unavailable.')
    		);
			$this->_redirectReferer($this->_getRefererUrl());
    		return;    		
    	}

    	//@todo: check are first_name and last_name always there
		if(!isset($standardInfo['email'])) {
    		$this->_getCustomerSession()->addError(
    			$this->__('Facebook connection failed.') .
    			' ' .
				$this->__('Email address is required.')
    		);
			$this->_redirectReferer($this->_getRefererUrl());
    		return;
		}
		
		$customer
			->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
			->loadByEmail($standardInfo['email']);
		
		if($customer->getId()){
			$customer->setFacebookUid($this->_getSession()->getUid());
			$customer->setFacebookToken(json_encode($this->_getSession()->getData()));
			
			if($customer->getConfirmation()){
				$customer->setConfirmation(null);
			}
			$customer->save();
			
			$this->_getCustomerSession()->setCustomerAsLoggedIn($customer);
			$this->_getCustomerSession()->addSuccess(
				$this->__('Your Facebook account has been successfully connected. Now you can fast login using Facebook Connect anytime.')
			);
			$this->_redirectReferer($this->_getRefererUrl());
    		return;
		}
		
		//Auto registration when user not exist
		
		$randomPassword = $customer->generatePassword(8);
		
		$customer	->setId(null)
					->setSkipConfirmationIfEmail($standardInfo['email'])
					->setFirstname($standardInfo['first_name'])
					->setLastname($standardInfo['last_name'])
					->setEmail($standardInfo['email'])
					->setPassword($randomPassword)
					->setConfirmation($randomPassword)
					->setFacebookUid($this->_getSession()->getUid())
					->setFacebookToken(json_encode($this->_getSession()->getData()));

		//Set sex in my profile.
		if(isset($standardInfo['gender']) && $gender=Mage::getResourceSingleton('customer/customer')->getAttribute('gender')){
			$genderOptions = $gender->getSource()->getAllOptions();
			foreach($genderOptions as $option){
				if($option['label']==ucfirst($standardInfo['gender'])){
					 $customer->setGender($option['value']);
					 break;
				}
			}
		}
		
		//Set full birthday in my profile.
       	if(isset($standardInfo['birthday']) && count(explode('/',$standardInfo['birthday']))==3){
			
       		$dob = $standardInfo['birthday'];
			
       		if(method_exists($this,'_filterDates')){
       			$filtered = $this->_filterDates(array('dob'=>$dob), array('dob'));
       			$dob = current($filtered);
       		}

			$customer->setDob($dob);
		}

		if ($this->getRequest()->getParam('is_subscribed', false)) {
			$customer->setIsSubscribed(1);
		}
		
		//registration will fail if tax required, also if dob, gender aren't allowed in profile
		$errors = array();
		$validationCustomer = $customer->validate();
		if (is_array($validationCustomer)) {
				$errors = array_merge($validationCustomer, $errors);
		}
		$validationResult = count($errors) == 0;

		if (true === $validationResult) {
			$customer->save();
			
			$this->_getCustomerSession()->addSuccess(
				$this->__('Thank you for registering with %s', Mage::app()->getStore()->getFrontendName()) .
				'. ' . 
				$this->__('You will receive welcome email with registration info in a moment.')
			);
			//post to wall
			if(Mage::getStoreConfig('facebookstore/register/enable')){
				$store = Mage::app()->getStore()->getName().' '.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
				$messageContent = Mage::getStoreConfig('facebookstore/register/template_message_connect_via_facebook');
				$messageContent = str_ireplace("{user_name}", $customer->getName(), $messageContent);
				$messageContent = str_ireplace("{store_link}", $store, $messageContent);
				$params = array ('message'=> utf8_encode($messageContent)) ;
				Mage::getSingleton('facebookstore/session')->getClient()->postWall("/me/feed",  $params);
			}
			$customer->sendNewAccountEmail();
			
			$this->_getCustomerSession()->setCustomerAsLoggedIn($customer);
			
			$this->_redirectReferer($this->_getRefererUrl());
			return;
		
		//else set form data and redirect to registration
		} else {
 			$this->_getCustomerSession()->setCustomerFormData($customer->getData());
 			$this->_getCustomerSession()->addError($this->__('Facebook profile can\'t provide all required info, please register and then connect with Facebook for fast login.'));
			if (is_array($errors)) {
				foreach ($errors as $errorMessage) {
					$this->_getCustomerSession()->addError($errorMessage);
				}
			}
			
			//$this->_redirect('customer/account/create');
			$this->_redirectReferer($this->_getRefererUrl());
			
		}

    }
	
	private function _getCustomerSession()
	{
		return Mage::getSingleton('customer/session');
	}
    
	private function _getSession()
	{
		return Mage::getSingleton('facebookstore/session');
	}
	
}
