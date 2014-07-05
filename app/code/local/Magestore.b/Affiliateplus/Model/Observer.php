<?php

class Magestore_Affiliateplus_Model_Observer
{
	/**
	 * get Config Helper
	 *
	 * @return Magestore_Affiliateplus_Helper_Config
	 */
	protected function _getConfigHelper(){
		return Mage::helper('affiliateplus/config');
	}
	
	public function productGetFinalPrice($observer){
		if ($this->_getConfigHelper()->getGeneralConfig('type_discount') == 'cart')
			return $this;
		$affiliateInfo = Mage::helper('affiliateplus/cookie')->getAffiliateInfo();
		$account = '';
    	foreach ($affiliateInfo as $info)
			if ($info['account']){
				$account = $info['account'];
				break;
			}
		if (!$account) return $this;
		$product = $observer['product'];
		$product->setData('final_price',$this->_getFinalPrice($product,$product->getData('final_price')));
	}
	
	public function productListCollection($observer){
		if ($this->_getConfigHelper()->getGeneralConfig('type_discount') == 'cart')
			return $this;
		$affiliateInfo = Mage::helper('affiliateplus/cookie')->getAffiliateInfo();
		$account = '';
    	foreach ($affiliateInfo as $info)
			if ($info['account']){
				$account = $info['account'];
				break;
			}
		if (!$account) return $this;
		$productCollection = $observer['collection'];
		foreach ($productCollection as $product)
			$product->setData('final_price',$this->_getFinalPrice($product,$product->getData('final_price')));
	}
	
	protected function _getFinalPrice($product, $price){
		$discountedObj = new Varien_Object(array(
			'price'			=> $price,
			'discounted'	=> false,
		));
		
		Mage::dispatchEvent('affiliateplus_product_get_final_price',array(
			'product'			=> $product,
			'discounted_obj'	=> $discountedObj,
		));
		
		if ($discountedObj->getDiscounted()) return $discountedObj->getPrice();
		$price = $discountedObj->getPrice();
		
		if ($this->_getConfigHelper()->getGeneralConfig('discount_type') == 'fixed'){
			$price -= floatval($this->_getConfigHelper()->getGeneralConfig('discount'));
		}elseif ($this->_getConfigHelper()->getGeneralConfig('discount_type') == 'percentage') {
			$price -= floatval($this->_getConfigHelper()->getGeneralConfig('discount')) / 100 * $price;
		}
		if ( $price < 0 ) return 0;
		return $price;
	}
	
    public function controllerActionPredispatch($observer){
    	$controller = $observer['controller_action'];
    	$request = $controller->getRequest();
    	
    	$accountCode = $request->getParam('acc');
		
		if (!$accountCode && $request->getParam('df08b0441bac900')){
			$resource = Mage::getSingleton('core/resource');
			$read = $resource->getConnection('core_read');
			$write = $resource->getConnection('core_write');
			try {
				$select = $read->select()
					->from($resource->getTableName('affiliate_referral'),array('customer_id'))
					->where("identify_code=?",trim($request->getParam('df08b0441bac900')));
				$result = $read->fetchRow($select);
				$oldCustomerId = $result['customer_id'];
				if ($oldCustomerId)
					$accountCode = Mage::getModel('affiliateplus/account')
						->loadByCustomerId($oldCustomerId)
						->getIdentifyCode();
			} catch (Exception $e){}
		}
		
    	if (!$accountCode) return $this;
    	
    	if ($account = Mage::getSingleton('affiliateplus/session')->getAccount())
    		if ($account->getIdentifyCode() == $accountCode)
    			return $this;
    	
    	$expiredTime = $this->_getConfigHelper()->getGeneralConfig('expired_time');
    	$cookie = Mage::getSingleton('core/cookie');
    	if ($expiredTime)
    		$cookie->setLifeTime(intval($expiredTime)*86400);
    	
    	$current_index = $cookie->get('affiliateplus_map_index');
    	
    	$addCookie = new Varien_Object(array(
    		'existed'	=> false,
    	));
    	for($i = intval($current_index); $i>0 ; $i--){
    		if ($cookie->get("affiliateplus_account_code_$i") == $accountCode){
    			$addCookie->setExisted(true);
    			$addCookie->setIndex($i);
    			Mage::dispatchEvent('affiliateplus_controller_action_predispatch_add_cookie',array(
    				'request'	=> $request,
    				'add_cookie'=> $addCookie,
    				'cookie'	=> $cookie,
    			));
    			if ($addCookie->getExisted()) return $this;
    		}
    	}
    	$current_index = $current_index ? intval($current_index)+1 : 1;
    	$cookie->set('affiliateplus_map_index',$current_index);
    	
    	$cookie->set("affiliateplus_account_code_$current_index",$accountCode);
    	
    	$cookieParams = new Varien_Object(array(
    		'params'	=> array(),
    	));
    	Mage::dispatchEvent('affiliateplus_controller_action_predispatch_observer',array(
    		'controller_action'	=> $controller,
    		'cookie_params'		=> $cookieParams,
    		'cookie'			=> $cookie,
    	));
    	
    	foreach ($cookieParams->getParams() as $key => $value)
    		$cookie->set("affiliateplus_$key"."_$current_index",$value);
    	
    	$account = Mage::getModel('affiliateplus/account')->loadByIdentifyCode($accountCode);
    	if (!$account->getId()) return $this;
    	$storeId = Mage::app()->getStore()->getId();
    	if (!$storeId) return $this;
    	$ipAddress = $request->getClientIp();
    	$refererModel = Mage::getModel('affiliateplus/referer');
    	
    	$refererCollection = $refererModel->getCollection()
    		->addFieldToFilter('account_id',$account->getId());
    	if (!in_array($ipAddress,$refererCollection->getIpListArray())){
    		$account->setUniqueClicks($account->getUniqueClicks() + 1);
    		try {
    			$account->save();
    		}catch (Exception $e){}
    	}
    	
    	$account->setStoreId($storeId)->load($account->getId());
    	$refererCollection->addFieldToFilter('store_id',$storeId);
    	if (!in_array($ipAddress,$refererCollection->getIpListArray()))
    		if ($account->getUniqueClicksInStore())
    			$account->setUniqueClicks($account->getUniqueClicks() + 1);
    		else 
    			$account->setUniqueClicks(1);
    	$account->setTotalClicks($account->getTotalClicks() + 1);
    	try {
    		$account->save();
    	}catch (Exception $e){}
    	
    	$httpReferrerInfo = parse_url($request->getServer('HTTP_REFERER'));
    	$referer = isset($httpReferrerInfo['host']) ? $httpReferrerInfo['host'] : '';
    	$refererModel->loadExistReferer($account->getId(),$referer,$storeId,$request->getOriginalRequest()->getPathInfo());
    	
    	Mage::dispatchEvent('affiliateplus_referrer_load_existed',array(
    		'referrer_model'	=> $refererModel,
    		'controller_action'	=> $controller,
    	));
    	
    	try {
    		$refererModel->setIpAddress($ipAddress)->save();
    	}catch (Exception $e){}
    	
    	return $this;
    }
    
    public function orderPlaceAfter($observer){
    	$order = $observer['order'];
    	if (!$order->getBaseSubtotal()) return $this;
    	
    	$affiliateInfo = Mage::helper('affiliateplus/cookie')->getAffiliateInfo();
    	$account = '';
    	foreach ($affiliateInfo as $info)
			if ($info['account']){
				$account = $info['account'];
				break;
			}

		if ($account && $account->getId()){
			$baseDiscount = $order->getBaseAffiliateplusDiscount();
			//$maxCommission = $order->getBaseGrandTotal() - $order->getBaseShippingAmount();
			
			// Before calculate commission
			$commissionObj = new Varien_Object(array(
				'commission'		=> 0,
				'default_commission'=> true,
				'order_item_ids'	=> array(),
				'order_item_names'	=> array(),
				'commission_items'	=> array(),
				'extra_content'		=> array(),
				'tier_commissions'	=> array(),
			));
			Mage::dispatchEvent('affiliateplus_calculate_commission_before',array(
				'order'			=> $order,
				'affiliate_info'=> $affiliateInfo,
				'commission_obj'=> $commissionObj,
			));
			
			$commissionValue = floatval($this->_getConfigHelper()->getGeneralConfig('commission'));
			$commission = $commissionObj->getCommission();
			$orderItemIds = $commissionObj->getOrderItemIds();
			$orderItemNames = $commissionObj->getOrderItemNames();
			$commissionItems = $commissionObj->getCommissionItems();
			$extraContent = $commissionObj->getExtraContent();
			$tierCommissions = $commissionObj->getTierCommissions();
			
			$defaultItemIds = array();
			$defaultItemNames = array();
			$defaultAmount = 0;
			$defCommission = 0;
			if ($commissionValue && $commissionObj->getDefaultCommission()){
				if ($this->_getConfigHelper()->getGeneralConfig('commission_type') == 'percentage'){
					if ($commissionValue > 100) $commissionValue = 100;
					if ($commissionValue < 0) $commissionValue = 0;
				}
				
				foreach ($order->getAllItems() as $item){
					if (in_array($item->getId(),$commissionItems)) continue;
					
					if ($this->_getConfigHelper()->getGeneralConfig('affiliate_type') == 'profit')
						$baseProfit = $item->getBasePrice() - $item->getBaseCost();
					else
						$baseProfit = $item->getBasePrice();
					
					$baseProfit = $item->getQtyOrdered() * $baseProfit - $item->getBaseDiscountAmount();
					if ($baseProfit <= 0) continue;
					
					$orderItemIds[] = $item->getProduct()->getId();
					$orderItemNames[] = $item->getName();
					
					$defaultItemIds[] = $item->getProduct()->getId();
					$defaultItemNames[] = $item->getName();
					
					if ($this->_getConfigHelper()->getGeneralConfig('commission_type') == 'fixed')
						$defaultCommission = min($item->getQtyOrdered() * $commissionValue , $baseProfit);
						//$defaultCommission = $item->getQtyOrdered() * min($commissionValue, $baseProfit);
					elseif ($this->_getConfigHelper()->getGeneralConfig('commission_type') == 'percentage')
						$defaultCommission = $baseProfit * $commissionValue / 100;
						//$defaultCommission = $item->getQtyOrdered() * $baseProfit * $commissionValue / 100;
					
					$commissionObj = new Varien_Object(array(
						'profit'		=> $baseProfit,
						'commission'	=> $defaultCommission,
						'tier_commission'	=> array(),
					));
					Mage::dispatchEvent('affiliateplus_calculate_tier_commission',array(
						'item'		=> $item,
						'account'	=> $account,
						'commission_obj'	=> $commissionObj
					));
					
					if ($commissionObj->getTierCommission())
						$tierCommissions[$item->getId()] = $commissionObj->getTierCommission();
					$commission += $commissionObj->getCommission();//$defaultCommission;
					
					$defCommission += $commissionObj->getCommission();
					$defaultAmount += $item->getBasePrice();
				}
				//if ($this->_getConfigHelper()->getGeneralConfig('commission_type') == 'percentage')
				//	$commission = $maxCommission * $commissionValue / 100;
			}
			
			//$originalCommission = $commission;
			//$ratio = $maxCommission / $order->getBaseSubtotal();
			//$commission = $ratio * $originalCommission;
			//if ($commission > $maxCommission)
			//	$commission = $maxCommission;
			
			if (!$baseDiscount && !$commission) return $this;
			
			$customer = Mage::getSingleton('customer/session')->getCustomer();
			
			// Calculate Addition commission
			$commissionPlus = 0;
			$salesHelper = Mage::helper('affiliateplus/sales');
			// Monthly commission
			if ($salesHelper->getConfig('month')){
				$commissionLevels = $salesHelper->getMonthlyCommission();
				if ($levels = count($commissionLevels)){
					$accountSales = $salesHelper->getAccountSales($account->getId());// + $order->getBaseSubtotal();
					foreach ($commissionLevels as $commissionLV){
						if ($accountSales >= $commissionLV['sales']){
							$commissionPlus = $commissionLV['commission'];
							break;
						}
					}
				}
			}
			// Yearly commission
			if ($salesHelper->getConfig('year')){
				$commissionLevels = $salesHelper->getYearlyCommission();
				if ($levels = count($commissionLevels)){
					$yearlyPlus = 0;
					$accountSales = $salesHelper->getAccountSales($account->getId(),'Y-');// + $order->getBaseSubtotal();
					foreach ($commissionLevels as $commissionLV){
						if ($accountSales >= $commissionLV['sales']){
							$yearlyPlus = $commissionLV['commission'];
							break;
						}
					}
					$commissionPlus += $yearlyPlus;
				}
			}
			
			// Create transaction
			$transactionData = array(
				'account_id'	=> $account->getId(),
				'account_name'	=> $account->getName(),
				'account_email'	=> $account->getEmail(),
				'customer_id'	=> $customer->getId(),
				'customer_email'	=> $customer->getEmail(),
				'order_id'		=> $order->getId(),
				'order_number'	=> $order->getIncrementId(),
				'order_item_ids'	=> implode(',',$orderItemIds),
				'order_item_names'	=> implode(',',$orderItemNames),
				'total_amount'	=> $order->getBaseSubtotal(),
				'discount'		=> $baseDiscount,
				'commission'	=> $commission,
				'created_time'	=> now(),
				'status'		=> '2',
				'store_id'		=> Mage::app()->getStore()->getId(),
				'extra_content'	=> $extraContent,
				'tier_commissions'	=> $tierCommissions,
				//'ratio'			=> $ratio,
				//'original_commission'	=> $originalCommission,
				'default_item_ids'		=> $defaultItemIds,
				'default_item_names'	=> $defaultItemNames,
				'default_commission'	=> $defCommission,
				'default_amount'		=> $defaultAmount,
			);
			if ($account->getUsingCoupon()){
				$session = Mage::getSingleton('checkout/session');
				$transactionData['coupon_code'] = $session->getData('affiliate_coupon_code');
				if ($program = $account->getUsingProgram()){
					$transactionData['program_id'] = $program->getId();
					$transactionData['program_name'] = $program->getName();
				} else {
					$transactionData['program_id'] = 0;
					$transactionData['program_name'] = 'Affiliate Program';
				}
				$session->unsetData('affiliate_coupon_code');
				$session->unsetData('affiliate_coupon_data');
			}
			
			if ($salesHelper->getConfig('commission_type') == 'percentage'){
				$transactionData['percent_plus'] = $commissionPlus;
				$transactionData['commission_plus'] = 0;
			} else {
				$transactionData['percent_plus'] = 0;
				$transactionData['commission_plus'] = $commissionPlus;
			}
			
			$transaction = Mage::getModel('affiliateplus/transaction')->setData($transactionData)->setId(null);
			
			Mage::dispatchEvent('affiliateplus_calculate_commission_after',array(
				'transaction'	=> $transaction,
				'order'			=> $order,
				'affiliate_info'=> $affiliateInfo,
			));
			
			try {
				$transaction->save();
				Mage::dispatchEvent('affiliateplus_recalculate_commission',array(
					'transaction'	=> $transaction,
					'order'			=> $order,
					'affiliate_info'=> $affiliateInfo,
				));
				
				if ($transaction->getIsChangedData())
					$transaction->save();
				Mage::dispatchEvent('affiliateplus_created_transaction',array(
					'transaction'	=> $transaction,
					'order'			=> $order,
					'affiliate_info'=> $affiliateInfo,
				));
				
				$transaction->sendMailNewTransactionToAccount();
				$transaction->sendMailNewTransactionToSales();
			}catch (Exception $e){
				// Exception
			}
		}
    }
    
    public function orderSaveAfter($observer){
    	$order = $observer->getOrder();
    	$storeId = $order->getStoreId();
    	
    	$configOrderStatus = $this->_getConfigHelper()->getPaymentConfig('updatebalance_orderstatus',$storeId);
    	$configOrderStatus = $configOrderStatus ? $configOrderStatus : 'processing';
    	if ($order->getStatus() == $configOrderStatus){
    		$transaction = Mage::getModel('affiliateplus/transaction')->load($order->getIncrementId(),'order_number');
    		// Complete Transaction
    		return $transaction->complete();
    	}
    	
    	$cancelStatus = explode(',',$this->_getConfigHelper()->getPaymentConfig('cancel_transaction_orderstatus',$storeId));
    	if (in_array($order->getStatus(),$cancelStatus)){
    		$transaction = Mage::getModel('affiliateplus/transaction')->load($order->getIncrementId(),'order_number');
    		// Cancel Transaction
    		return $transaction->cancel();
    	}
    }
	
	public function paypalPrepareItems($observer){
		$paypalCart = $observer->getEvent()->getPaypalCart();
		if ($paypalCart){
			$salesEntity = $paypalCart->getSalesEntity();
			$totalDiscount = 0;
			if ($salesEntity->getBaseAffiliateplusDiscount())
				$totalDiscount = $salesEntity->getBaseAffiliateplusDiscount();
			else
				foreach ($salesEntity->getAddressesCollection() as $address)
					if ($address->getBaseAffiliateplusDiscount())
						$totalDiscount = $address->getBaseAffiliateplusDiscount();
			if ($totalDiscount)
				$paypalCart->updateTotal(Mage_Paypal_Model_Cart::TOTAL_DISCOUNT,abs((float)$totalDiscount),Mage::helper('affiliateplus')->__('Affiliate Discount'));
		}
	}
	
	public function sendReportEmail(){
		$websites = Mage::app()->getWebsites(true);
		foreach ($websites as $website){
			if (!$website->getConfig('affiliateplus/email/is_sent_report')) continue;
			$periodData = array(
				'week'	=> array(
					'date'	=> 'w',
					'label'	=> $this->_getConfigHelper()->__('last week'),
				),
				'month'	=> array(
					'date'	=> 'j',
					'label'	=> $this->_getConfigHelper()->__('last month'),
				),
				'year'	=> array(
					'date'	=> 'z',
					'label'	=> $this->_getConfigHelper()->__('last year'),
				)
			);
			$period = $website->getConfig('affiliateplus/email/report_period');
			if (date($periodData[$period]['date']) != 1) continue;
			
			$store = $website->getDefaultStore();
			if (!$store) continue;
			$storeId = $store->getId();
			
			$accounts = Mage::getResourceModel('affiliateplus/account_collection')
				->addFieldToFilter('main_table.status',1)
				->addFieldToFilter('main_table.notification',1);
			
			$accounts->getSelect()->joinLeft(
				array('e' => $accounts->getTable('customer/entity')),
				'main_table.customer_id	= e.entity_id',
				array('website_id')
			)->where('e.website_id = ?',$website->getId())
			->where('e.is_active = 1');
			
			$date = new Zend_Date();
			$to = $date->toString();
			$function = 'sub'.ucfirst($period);
			$fromDate = $date->$function(1)->toString('YYYY-MM-dd');
			$from = $date->toString();
			
			$translate = Mage::getSingleton('core/translate');
			$translate->setTranslateInline(false);
			$template = $website->getConfig('affiliateplus/email/report_template');
			$sender = Mage::getStoreConfig('trans_email/ident_sales',$store);
			
			foreach ($accounts as $account){
				$statistic = new Varien_Object();
				$transactions = Mage::getResourceModel('affiliateplus/transaction_collection')
					->addFieldToFilter('account_id',$account->getId());
				$transactions->getSelect()->reset(Zend_Db_Select::COLUMNS)
					->where('date(created_time) >= ?',$fromDate)
					->columns(array(
						'status',
						'sales'			=> 'SUM(`total_amount`)',
						'transactions'	=> 'COUNT(`transaction_id`)',
						'commissions'	=> 'SUM(`commission`+`commission`*`percent_plus`+`commission_plus`)',
					))->group('status');
				foreach ($transactions as $transaction){
					if ($transaction->getStatus() == 1){
						$statistic->setData('complete',$transaction->getData());
					} elseif ($transaction->getStatus() == 2){
						$statistic->setData('pending',$transaction->getData());
					} elseif ($transaction->getStatus() == 3){
						$statistic->setData('cancel',$transaction->getData());
					}
				}
				$visitors = Mage::getResourceModel('log/visitor_collection');
				try {
					$visitors->getSelect()->from(array('main_table' => $accounts->getTable('log/visitor')));
				} catch (Exception $e){}
				$visitors->getSelect()->reset(Zend_Db_Select::COLUMNS)
					->join(
						array('url'	=> $accounts->getTable('log/url_table')),
						'main_table.visitor_id = url.visitor_id',
						array()
					)->join(
						array('info' => $accounts->getTable('log/url_info_table')),
						'url.url_id = info.url_id',
						array()
					)->join(
						array('visitor' => $accounts->getTable('log/visitor_info')),
						'main_table.visitor_id = visitor.visitor_id',
						array()
					)->where('date(url.visit_time) >= ?',$fromDate)
					->where('info.url LIKE ?',"%acc={$account->getIdentifyCode()}%")
					->columns(array(
						'clicks'	=> 'COUNT(DISTINCT url.visitor_id)',
						'unique'	=> 'COUNT(DISTINCT visitor.remote_addr)'
					))->group('LOCATE(main_table.store_id,main_table.store_id)');
				$statistic->setData('click',$visitors->getFirstItem()->getData());
				$mailTemplate = Mage::getModel('core/email_template')
					->setDesignConfig(array(
						'area'	=> 'frontend',
						'store'	=> $storeId,
					))
					->sendTransactional(
						$template,
						$sender,
						$account->getEmail(),
						$account->getName(),
						array(
							'store'	=> $store,
							'account'	=> $account,
							'statistic'	=> $statistic,
							'period'	=> $this->_getConfigHelper()->__($period),
							'label'	=> $periodData[$period]['label'],
							'from'	=> $from,
							'to'	=> $to,
						)
					);
			}
			
			$translate->setTranslateInline(true);
		}
	}
}
