<?php

class Magestore_Affiliateplus_Model_Transaction extends Mage_Core_Model_Abstract
{
	const XML_PATH_ADMIN_EMAIL_IDENTITY = 'trans_email/ident_general';
	const XML_PATH_EMAIL_IDENTITY = 'trans_email/ident_sales';
	const XML_PATH_NEW_TRANSACTION_ACCOUNT_EMAIL = 'affiliateplus/email/new_transaction_account_email_template';
	const XML_PATH_NEW_TRANSACTION_SALES_EMAIL = 'affiliateplus/email/new_transaction_sales_email_template';
	const XML_PATH_UPDATED_TRANSACTION_ACCOUNT_EMAIL = 'affiliateplus/email/updated_transaction_account_email_template';

    public function _construct(){
        parent::_construct();
        $this->_init('affiliateplus/transaction');
    }
    
    public function complete(){
    	if (!$this->getId()) return $this;
    	if ($this->getStatus() != '2') return $this;
    	// Add commission for affiliate account
    	$account = Mage::getModel('affiliateplus/account')
    		->setStoreId($this->getStoreId())
    		->load($this->getAccountId());
    	try {
			$commission = $this->getCommission() + $this->getCommissionPlus() + $this->getCommission() * $this->getPercentPlus() / 100;
    		$account->setBalance($account->getBalance() + $commission)//$this->getCommission())
    			->save();
				
    		$this->setStatus('1')->save();
			
			//update balance tier affiliate
			Mage::dispatchEvent('affiliateplus_complete_transaction',array('transaction' => $this));
			
	    	// Send email to affiliate account
	    	$this->sendMailUpdatedTransactionToAccount(true);
    	} catch (Exception $e){
    		
    	}
    	return $this;
    }
    
    public function cancel(){
    	if (!$this->getId()) return $this;
    	if ($this->getStatus() == '2'){
    		try {
    			$this->setStatus('3')->save();
    		} catch (Exception $e){
    			
    		}
    	} elseif ($this->getStatus() == '1') {
    		// Remove commission for affiliate account
    		$account = Mage::getModel('affiliateplus/account')
	    		->setStoreId($this->getStoreId())
	    		->load($this->getAccountId());
    		try {
				$commission = $this->getCommission() + $this->getCommissionPlus() + $this->getCommission() * $this->getPercentPlus() / 100;
    			$account->setBalance($account->getBalance() - $commission)//$this->getCommission())
    				->save();
    			$this->setStatus('3')->save();
				
				//update balance tier affiliate
				Mage::dispatchEvent('affiliateplus_cancel_transaction',array('transaction' => $this));
				
	    		// Send email to affiliate account
	    		$this->sendMailUpdatedTransactionToAccount(false);
    		} catch (Exception $e){
    			
    		}
    	}
    	return $this;
    }
	
	public function sendMailNewTransactionToAccount(){
		if(!Mage::getStoreConfig('affiliateplus/email/is_sent_email_account_new_transaction'))
			return $this;
			
		$store = Mage::getModel('core/store')->load($this->getStoreId());
		$currentCurrency = $store->getCurrentCurrency();
		$store->setCurrentCurrency($store->getBaseCurrency());		

		$account = Mage::getModel('affiliateplus/account')->load($this->getAccountId());
		
		if (!$account->getNotification()) return $this;
		
		//update commission tier affiliate
		Mage::dispatchEvent('affiliateplus_reset_transaction_commission',array('transaction' => $this));
		
		$this->setProducts(Mage::helper('affiliateplus')->getFrontendProductHtmls($this->getOrderItemIds()))
				->setTotalAmountFormated(Mage::helper('core')->currency($this->getTotalAmount()))
				->setCommissionFormated(Mage::helper('core')->currency($this->getCommission()))
				->setPlusCommission($this->getCommissionPlus() + $this->getCommission() * $this->getPercentPlus() / 100)
				->setPlusCommissionFormated(Mage::helper('core')->currency($this->getPlusCommission()))
				->setAccountName($account->getName())
				->setAccountEmail($account->getEmail())
				->setCreatedAtFormated(Mage::helper('core')->formatDate($this->getCreatedTime(),'medium'))
				;
        $translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
		
		$template = Mage::getStoreConfig(self::XML_PATH_NEW_TRANSACTION_ACCOUNT_EMAIL, $store->getId());
		
        $sendTo = array(
            array(
                'email' => $account->getEmail(),
                'name'  => $account->getName(),
            )
        );
		$mailTemplate = Mage::getModel('core/email_template');
		 
        foreach ($sendTo as $recipient) {
            $mailTemplate->setDesignConfig(array('area'=>'frontend', 'store'=>$store->getId()))
                ->sendTransactional(
                    $template,
                    Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY, $store->getId()),
                    $recipient['email'],
                    $recipient['name'],
                    array(
                        'transaction'  => $this,
						'store'  => $store,
                    )
                );
		}

		$translate->setTranslateInline(true);
		
		//set current currency
		$store->setCurrentCurrency($currentCurrency);				
		
		return $this;
	}
	
	public function sendMailNewTransactionToSales(){
		if(!Mage::getStoreConfig('affiliateplus/email/is_sent_email_sales_new_transaction'))
			return $this;
		
		$store = Mage::getModel('core/store')->load($this->getStoreId());
		$currentCurrency = $store->getCurrentCurrency();
		$store->setCurrentCurrency($store->getBaseCurrency());	
		$sales = Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY, $store->getId());
		
		$account = Mage::getModel('affiliateplus/account')->load($this->getAccountId());
		$customer = Mage::getModel('customer/customer')->load($this->getCustomerId());
		
		//update commission tier affiliate
		//Mage::dispatchEvent('affiliateplus_reset_transaction_commission',array('transaction' => $this));
		
		$this->setCustomerName($this->getCustomerName())
				->setCustomerEmail($this->getCustomerEmail())
				->setAccountName($account->getName())
				->setAccountEmail($account->getEmail())
				->setProducts(Mage::helper('affiliateplus')->getBackendProductHtmls($this->getOrderItemIds()))
				->setTotalAmountFormated(Mage::helper('core')->currency($this->getTotalAmount()))
				->setCommissionFormated(Mage::helper('core')->currency($this->getCommission()))
				->setPlusCommission($this->getCommissionPlus() + $this->getCommission() * $this->getPercentPlus() / 100)
				->setPlusCommissionFormated(Mage::helper('core')->currency($this->getPlusCommission()))
				->setDiscountFormated(Mage::helper('core')->currency($this->getDiscount()))
				->setCreatedAtFormated(Mage::helper('core')->formatDate($this->getCreatedTime(),'medium'))
				->setSalesName($sales['name'])
				;
        $translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
		
		$template = Mage::getStoreConfig(self::XML_PATH_NEW_TRANSACTION_SALES_EMAIL,$store->getId());
				
        $sendTo = array(
            array(
                'email' => $sales['email'],
                'name'  => $sales['name'],
            )
        );
		
		$mailTemplate = Mage::getModel('core/email_template');
		
        foreach ($sendTo as $recipient) {
            $mailTemplate->setDesignConfig(array('area'=>'frontend', 'store'=>$store->getId()))
                ->sendTransactional(
                    $template,
                    Mage::getStoreConfig(self::XML_PATH_ADMIN_EMAIL_IDENTITY, $store->getId()),
                    $recipient['email'],
                    $recipient['name'],
                    array(
                        'transaction'  => $this,
						'store'  => $store,
                    )
                );
		}

		$translate->setTranslateInline(true);	
		//set current currency
		$store->setCurrentCurrency($currentCurrency);	
		return $this;
	}
	
	public function sendMailUpdatedTransactionToAccount($isCompleted){
		if(!Mage::getStoreConfig('affiliateplus/email/is_sent_email_account_updated_transaction'))
			return $this;
		
		$store = Mage::getModel('core/store')->load($this->getStoreId());
		$currentCurrency = $store->getCurrentCurrency();
		$store->setCurrentCurrency($store->getBaseCurrency());		

		$account = Mage::getModel('affiliateplus/account')->load($this->getAccountId());
		
		if (!$account->getNotification()) return $this;
		
		//update commission tier affiliate
		Mage::dispatchEvent('affiliateplus_reset_transaction_commission',array('transaction' => $this));
		
		$this->setProducts(Mage::helper('affiliateplus')->getFrontendProductHtmls($this->getOrderItemIds()))
				->setTotalAmountFormated(Mage::helper('core')->currency($this->getTotalAmount()))
				->setCommissionFormated(Mage::helper('core')->currency($this->getCommission()))
				->setPlusCommission($this->getCommissionPlus() + $this->getCommission() * $this->getPercentPlus() / 100)
				->setPlusCommissionFormated(Mage::helper('core')->currency($this->getPlusCommission()))
				->setAccountName($account->getName())
				->setAccountEmail($account->getEmail())
				->setCreatedAtFormated(Mage::helper('core')->formatDate($this->getCreatedTime(),'medium'))
				->setIsCompleted($isCompleted)
				;
        $translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
		
		$template = Mage::getStoreConfig(self::XML_PATH_UPDATED_TRANSACTION_ACCOUNT_EMAIL, $store->getId());
		
        $sendTo = array(
            array(
                'email' => $account->getEmail(),
                'name'  => $account->getName(),
            )
        );
		$mailTemplate = Mage::getModel('core/email_template');
		 
        foreach ($sendTo as $recipient) {
            $mailTemplate->setDesignConfig(array('area'=>'frontend', 'store'=>$store->getId()))
                ->sendTransactional(
                    $template,
                    Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY, $store->getId()),
                    $recipient['email'],
                    $recipient['name'],
                    array(
                        'transaction'  => $this,
						'store'  => $store,
                    )
                );
		}

		$translate->setTranslateInline(true);
		//set current currency
		$store->setCurrentCurrency($currentCurrency);				
		return $this;
	}
}