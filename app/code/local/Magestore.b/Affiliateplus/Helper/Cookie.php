<?php

class Magestore_Affiliateplus_Helper_Cookie extends Mage_Core_Helper_Abstract
{
	protected $_affiliateInfo = null;
	public function getAffiliateInfo(){
		if (!is_null($this->_affiliateInfo)) return $this->_affiliateInfo;
		$info = array();
		$cookie = Mage::getSingleton('core/cookie');
		$map_index = $cookie->get('affiliateplus_map_index');
		
		for($i=$map_index; $i>0 ; $i--){
			$accountCode = $cookie->get("affiliateplus_account_code_$i");
			$account = Mage::getModel('affiliateplus/account')->setStoreId(Mage::app()->getStore()->getId())->loadByIdentifyCode($accountCode);
			if ($account->getId()
				&& $account->getStatus() == 1
				&& $account->getId() != Mage::helper('affiliateplus/account')->getAccount()->getId()){
				$info[$accountCode] = array(
					'index'	=> $i,
					'code'	=> $accountCode,
					'account'	=> $account,
				);
			}
		}
		
		$infoObj = new Varien_Object(array(
			'info'	=> $info,
		));
		Mage::dispatchEvent('affiliateplus_get_affiliate_info',array(
			'cookie'	=> $cookie,
			'info_obj'	=> $infoObj,
		));
		
		$this->_affiliateInfo = $infoObj->getInfo();
		return $this->_affiliateInfo;
	}
}