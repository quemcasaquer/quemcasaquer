<?php

class Magestore_Affiliateplusstatistic_Model_Observer
{
	public function refererSaveAfter($observer){
		$referer = $observer->getEvent()->getAffiliateplusReferer();
		$ipAddress = Mage::app()->getRequest()->getClientIp();
		$model = Mage::getModel('affiliateplusstatistic/statistic')
			->setRefererId($referer->getId())
			->setReferer($referer->getReferer())
			->setUrlPath($referer->getUrlPath())
			->setIpAddress($ipAddress)
			->setVisitAt(now())
			->setStoreId($referer->getStoreId())
			->save();
		return $this;
	}
}