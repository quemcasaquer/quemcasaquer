<?php

class Magestore_Affiliateplus_Model_Total_Creditmemo_Affiliateplus extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
	public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo){
		$order = $creditmemo->getOrder();
		$baseDiscount = $order->getBaseAffiliateplusDiscount();
		$discount = $order->getAffiliateplusDiscount();
		
		$discountObj = new Varien_Object(array(
			'base_discount'	=> $baseDiscount,
			'discount'		=> $discount,
		));
		
		Mage::dispatchEvent('affiliateplus_creditmemo_collect_total',array(
			'creditmemo'	=> $creditmemo,
			'order'			=> $order,
			'discount_obj'	=> $discountObj,
		));
		
		$baseDiscount = $discountObj->getBaseDiscount();
		$discount = $discountObj->getDiscount();
		
		if (floatval($baseDiscount)){
			$creditmemo->setBaseAffiliateplusDiscount($baseDiscount);
			$creditmemo->setAffiliateplusDiscount($discount);
			
			$creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseDiscount);
			$creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $discount);
		}
		return $this;
	}
}