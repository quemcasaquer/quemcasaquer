<?php
class Magestore_Affiliateplus_Block_Account_Program extends Mage_Core_Block_Template
{
	/**
	 * get Configuration helper
	 *
	 * @return Magestore_Affiliateplus_Helper_Config
	 */
	protected function _getConfigHelper(){
		return Mage::helper('affiliateplus/config');
	}
	
	public function getMinPaymentRelease(){
		return $this->_getConfigHelper()->getPaymentConfig('payment_release');
	}
	
	public function getListProgram(){
		$programList = array();
		
		if ($this->_getConfigHelper()->getGeneralConfig('commission')
			&& $this->_getConfigHelper()->getGeneralConfig('discount')){
			$defaultProgram = new Varien_Object(array(
				'name'				=> $this->__('Affiliate Program'),
				'commission_type'	=> $this->_getConfigHelper()->getGeneralConfig('commission_type'),
				'commission'		=> $this->_getConfigHelper()->getGeneralConfig('commission'),
				'discount_type'		=> $this->_getConfigHelper()->getGeneralConfig('discount_type'),
				'discount'			=> $this->_getConfigHelper()->getGeneralConfig('discount'),
			));
			Mage::dispatchEvent('affiliateplus_prepare_program',array('info' => $defaultProgram));
			$programList['default'] = $defaultProgram;
		}
		
		$programListObj = new Varien_Object(array(
			'program_list'	=> $programList,
		));
		Mage::dispatchEvent('affiliateplus_get_list_program_welcome',array(
			'program_list_object'	=> $programListObj,
		));
		
		return $programListObj->getProgramList();
	}
}