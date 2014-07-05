<?php

class Magestore_Affiliateplus_Model_Total_Address_Affiliateplus extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
	public function __construct(){
		$this->setCode('affiliateplus');
	}
	
	/**
	 * get Config Helper
	 *
	 * @return Magestore_Affiliateplus_Helper_Config
	 */
	protected function _getConfigHelper(){
		return Mage::helper('affiliateplus/config');
	}
	
	public function collect(Mage_Sales_Model_Quote_Address $address){
		if ($this->_getConfigHelper()->getGeneralConfig('type_discount') == 'product')
			return $this;
		$items = $address->getAllItems();
		if (!count($items)) return $this;
		
		$affiliateInfo = Mage::helper('affiliateplus/cookie')->getAffiliateInfo();
		$baseDiscount = 0;
		$discountObj = new Varien_Object(array(
			'affiliate_info'	=> $affiliateInfo,
			'base_discount'		=> $baseDiscount,
			'default_discount'	=> true,
			'discounted_items'	=> array(),
		));
		Mage::dispatchEvent('affiliateplus_address_collect_total',array(
			'address'		=> $address,
			'discount_obj'	=> $discountObj,
		));
		
		$baseDiscount = $discountObj->getBaseDiscount();
		if ($discountObj->getDefaultDiscount()){
			$account = '';
			foreach ($affiliateInfo as $info)
				if ($info['account'])
					$account = $info['account'];
			if ($account && $account->getId()){
				$discountValue = floatval($this->_getConfigHelper()->getGeneralConfig('discount'));
				$discountedItems = $discountObj->getDiscountedItems();
				if ($this->_getConfigHelper()->getGeneralConfig('discount_type') == 'fixed'){
					foreach ($items as $item){
						if (in_array($item->getId(),$discountedItems) || 0 == $item->getBasePrice()) continue;
						$itemBaseDiscount = 0;
						if($item->getProduct()){
							$itemBaseDiscount = $item->getQty() * $discountValue;
							$price = $item->getQty() * $item->getBasePrice() - $item->getBaseDiscountAmount();
							$itemBaseDiscount = ($itemBaseDiscount < $price) ? $itemBaseDiscount : $price;
							$item->setBaseDiscountAmount($item->getBaseDiscountAmount()+$itemBaseDiscount)
								->setDiscountAmount($item->getDiscountAmount()+Mage::app()->getStore()->convertPrice($itemBaseDiscount));
						}
						$baseDiscount += $itemBaseDiscount;
					}
				}elseif ($this->_getConfigHelper()->getGeneralConfig('discount_type') == 'percentage'){
					if ($discountValue > 100) $discountValue = 100;
					if ($discountValue < 0) $discountValue = 0;
					foreach ($items as $item){
						if (in_array($item->getId(),$discountedItems) || 0 == $item->getBasePrice()) continue;
						if ($item->getProduct()){
							$price = $item->getQty() * $item->getBasePrice() - $item->getBaseDiscountAmount();
							$itemBaseDiscount = $price * $discountValue / 100;
							$item->setBaseDiscountAmount($item->getBaseDiscountAmount()+$itemBaseDiscount)
								->setDiscountAmount($item->getDiscountAmount()+Mage::app()->getStore()->convertPrice($itemBaseDiscount));
							$baseDiscount += $itemBaseDiscount;
						}
					}
				}
			}
		}
		
		if ($baseDiscount > $address->getBaseGrandTotal())
			$baseDiscount = $address->getBaseGrandTotal();
		
		if ($baseDiscount){
			$discount = Mage::app()->getStore()->convertPrice($baseDiscount);
			$address->setBaseAffiliateplusDiscount(-$baseDiscount);
			$address->setAffiliateplusDiscount(-$discount);
			
			$address->setBaseGrandTotal($address->getBaseGrandTotal() - $baseDiscount);
			$address->setGrandTotal($address->getGrandTotal() - $discount);
		}
		
		return $this;
	}
	
	public function fetch(Mage_Sales_Model_Quote_Address $address){
		$amount = $address->getAffiliateplusDiscount();
		$title = $this->_getConfigHelper()->__('Affiliate Discount');
		if ($amount != 0){
			$address->addTotal(array(
				'code'	=> $this->getCode(),
				'title'	=> $title,
				'value'	=> $amount,
			));
		}
		return $this;
	}
}