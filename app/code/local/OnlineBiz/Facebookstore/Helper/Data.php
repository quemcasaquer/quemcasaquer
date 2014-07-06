<?php

/**
 * Export Product data to xml data
 *
 * @category   OnlineBiz
 * @package    OnlineBiz_Facebookstore
 * @author     Nguyen Viet Tung (http://onlinebizsoft.com)
 */
class OnlineBiz_Facebookstore_Helper_Data extends Mage_Core_Helper_Abstract {
	protected $_storeCategories;
	public function getConnectUrl()
	{
		return $this->_getUrl('facebookstore/customer_account/connect', array('_secure'=>true));
	}
	public function isActivated(){
		return OnlineBiz_ObBase_Helper_Data::isActivated('OnlineBiz_Facebookstore', Mage::getStoreConfig('facebookstore/general/key'),'facebookstore/general/enabled');
	}
	public function isFacebookCustomer($customer)
	{
		if($customer->getFacebookUid()) {
			return true;
		}
		return false;
	}
	public function getPageStyle()
	{
		return Mage::getStoreConfig('facebookstore/general/fanpage_mode');
	}
	public function isHomepage()
	{
		$isHomepage = false;
		if(!Mage::app()->getRequest()->getParam('limit', false) && !Mage::app()->getRequest()->getParam('id', false) && Mage::app()->getRequest()->getActionName()!='search'){
			$isHomepage = true;	
		}
		return $isHomepage;
	}
	public function isCheckout()
	{
		if(Mage::app()->getRequest()->getControllerName()=='checkout' && Mage::app()->getRequest()->getActionName()=='index')
			return true;
		return false;
	}
	public function isCatalog()
	{
		if(Mage::app()->getRequest()->getControllerName()=='index' && (Mage::app()->getRequest()->getActionName()=='index' || Mage::app()->getRequest()->getActionName()=='category' || Mage::app()->getRequest()->getActionName()=='search'))
			return true;
		return false;
	}
	public function getOnFacebookCategories($sorted=false, $asCollection=false, $toLoad=true)
    {
        $parent     = (int)Mage::getStoreConfig('facebookstore/general/category_root_id');
        $cacheKey   = sprintf('%d-%d-%d-%d', $parent, $sorted, $asCollection, $toLoad);
        if (isset($this->_storeCategories[$cacheKey])) {
            return $this->_storeCategories[$cacheKey];
        }
        /**
         * Check if parent node of the store still exists
         */
        $category = Mage::getModel('catalog/category');
        /* @var $category Mage_Catalog_Model_Category */
        if (!$category->checkId($parent)) {
            if ($asCollection) {
                return new Varien_Data_Collection();
            }
            return array();
        }
        $recursionLevel  = max(0, (int) Mage::app()->getStore()->getConfig('catalog/navigation/max_depth'));
        $storeCategories = $category->getCategories($parent, $recursionLevel, $sorted, $asCollection, $toLoad);
        $this->_storeCategories[$cacheKey] = $storeCategories;
        return $storeCategories;
    }
	public function showSpecCatalog()
	{
		return Mage::getStoreConfig('facebookstore/general/category_root_id');
	}		public function isFanpage()	{		if(!Mage::getSingleton('customer/session')->getFanepage())		{			$signed_request = false;			if (isset($_REQUEST['signed_request'])) {				$encoded_sig = null;				$payload = null;				list($encoded_sig, $payload) = explode('.', $_REQUEST['signed_request'], 2);				$sig = base64_decode(strtr($encoded_sig, '-_', '+/'));				$signed_request = json_decode(base64_decode(strtr($payload, '-_', '+/'), true));				if($signed_request->page->liked){					Mage::getSingleton('customer/session')->setFanepage(true);				}			}		}				return Mage::getSingleton('customer/session')->getFanepage();	}
}