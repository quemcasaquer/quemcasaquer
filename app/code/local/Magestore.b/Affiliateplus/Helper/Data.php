<?php

class Magestore_Affiliateplus_Helper_Data extends Mage_Core_Helper_Abstract {
	
	public function getBackendProductHtmls($productIds){
		$productHtmls = array();
		$productIds = explode(',', $productIds);
		foreach($productIds as $productId){
			$productName = Mage::getModel('catalog/product')->load($productId)->getName();
			$productUrl = $this->_getUrl('adminhtml/catalog_product/edit/', array('_current'=>true, 'id' => $productId));
			$productHtmls[] = '<a href="'.$productUrl.'" title="'.Mage::helper('affiliateplus')->__('View Product Detail').'">'.$productName.'</a>';
		}
		return implode('<br />', $productHtmls);
	}
	
	public function getFrontendProductHtmls($productIds){
		$productHtmls = array();
		$productIds = explode(',', $productIds);
		foreach($productIds as $productId){
			$product = Mage::getModel('catalog/product')->load($productId);
			$productName = $product->getName();
			$productUrl = $product->getProductUrl();
			$productHtmls[] = '<a href="'.$productUrl.'" title="'.Mage::helper('affiliateplus')->__('View Product Detail').'">'.$productName.'</a>';
		}
		return implode('<br />', $productHtmls);
	}
	
	public function getStore($storeId){
		return Mage::getModel('core/store')->load($storeId);
	}
	
	public function getAffiliateCustomerIds(){
		$customerIds = array();
		$collection = Mage::getModel('affiliateplus/account')->getCollection();
		
		foreach($collection as $account){
			$customerIds[] = $account->getCustomerId();
		}
		
		return $customerIds;
	}
	
	public function isBalanceIsGlobal(){
		$scope = Mage::getStoreConfig('affiliateplus/sharing/balance');
		if($scope == 'store')
			return false;
		else
			return true;
	}
	
	public function multilevelIsActive(){
		$modules = Mage::getConfig()->getNode('modules')->children();
		$modulesArray = (array)$modules;
		if (isset($modulesArray['Magestore_Affiliatepluslevel']) && is_object($modulesArray['Magestore_Affiliatepluslevel']))
			return $modulesArray['Magestore_Affiliatepluslevel']->is('active');
		return false;
	}
}