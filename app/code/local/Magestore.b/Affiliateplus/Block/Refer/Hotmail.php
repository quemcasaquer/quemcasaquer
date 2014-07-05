<?php
class Magestore_Affiliateplus_Block_Refer_Hotmail extends Magestore_Affiliateplus_Block_Refer_Abstract
{
	/**
	 * get Contacts list to show
	 * 
	 * @return array
	 */
	public function getContacts(){
		$list = array();
		
		$hotmail = Mage::getSingleton('affiliateplus/refer_hotmail');
		if (!$hotmail->isAuth()) return $list;
		$contactsData = $hotmail->getContactsData();
		
		return $list;
	}
}