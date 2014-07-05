<?php

class Magestore_Affiliatepluscoupon_Block_Affiliatepluscoupon extends Magestore_Affiliateplus_Block_Account_Program
{
	public function getAccount(){
		return Mage::registry('account_model');
	}
	
	public function getListProgram(){
		$listProgram = parent::getListProgram();
		$correctPrograms = array();
		
		$programCoupons = Mage::registry('program_coupon_codes');
		foreach ($listProgram as $pId => $program){
			if ($pId == 'default'){
				if (floatval($this->_getConfigHelper()->getGeneralConfig('discount')) > 0){
					$program->setCouponCode($this->getAccount()->getCouponCode());
					$correctPrograms[$pId] = $program;
				}
			} else {
				if (!is_array($programCoupons)) continue;
				if (!isset($programCoupons[$pId])) continue;
				if ($programCoupons[$pId]){
					$program->setCouponCode($programCoupons[$pId]);
					$correctPrograms[$pId] = $program;
				}
			}
		}
		return $correctPrograms;
	}
	
	public function isMultiProgram(){
		return Mage::helper('affiliatepluscoupon')->isMultiProgram();
	}
}