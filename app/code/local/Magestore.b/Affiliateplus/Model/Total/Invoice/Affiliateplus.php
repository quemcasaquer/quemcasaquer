<?php

class Magestore_Affiliateplus_Model_Total_Invoice_Affiliateplus extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
	public function collect(Mage_Sales_Model_Order_Invoice $invoice){
		$order = $invoice->getOrder();
		$baseDiscount = $order->getBaseAffiliateplusDiscount();
		$discount = $order->getAffiliateplusDiscount();
		
		$discountObj = new Varien_Object(array(
			'base_discount'	=> $baseDiscount,
			'discount'		=> $discount,
		));
		
		Mage::dispatchEvent('affiliateplus_invoice_collect_total',array(
			'invoice'		=> $invoice,
			'order'			=> $order,
			'discount_obj'	=> $discountObj,
		));
		
		$baseDiscount = $discountObj->getBaseDiscount();
		$discount = $discountObj->getDiscount();
		
		if (floatval($baseDiscount)){
			$invoice->setBaseAffiliateplusDiscount($baseDiscount);
			$invoice->setAffiliateplusDiscount($discount);
			
			$invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseDiscount);
			$invoice->setGrandTotal($invoice->getGrandTotal() + $discount);
		}
		return $this;
	}
}