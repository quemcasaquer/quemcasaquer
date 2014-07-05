<?php

class Magestore_Affiliateplus_Helper_Sales extends Mage_Core_Helper_Abstract
{
	public function getConfig($code, $store = null){
		return Mage::getStoreConfig('affiliateplus/sales/'.$code,$store);
	}
	
	public function getMonthlyCommission(){
		$monthlyCommission = unserialize($this->getConfig('month_tier'));
		usort($monthlyCommission,array($this,'cmpSales'));
		return $monthlyCommission;
	}
	
	public function getYearlyCommission(){
		$yearlyCommission = unserialize($this->getConfig('year_tier'));
		usort($yearlyCommission,array($this,'cmpSales'));
		return $yearlyCommission;
	}
	
	public function cmpSales($aArray, $bArray){
		if ($aArray['sales'] == $bArray['sales'])
			return 0;
		return ($aArray['sales'] < $bArray['sales']) ? 1 : -1;
	}
	
	public function getAccountSales($accountId, $period = 'Y-m-'){
		$transactions = Mage::getResourceModel('affiliateplus/transaction_collection')
			->addFieldToFilter('account_id',$accountId)
			->addFieldToFilter('status',1)
			->addFieldToFilter('created_time',array('like' => date($period).'%'));
		$transactions->getSelect()
			->columns('COUNT(`order_id`) AS total_orders')
			->columns('SUM(`total_amount`) AS total_sales')
			->group('account_id');
		$salesStatistic = $transactions->getFirstItem();
		return $salesStatistic->getData("total_{$this->getConfig('type')}");
	}
}