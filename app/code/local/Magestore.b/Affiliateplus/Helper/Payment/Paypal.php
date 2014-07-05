<?php
class Magestore_Affiliateplus_Helper_Payment_Paypal extends Mage_Core_Helper_Abstract
{
//data is list email and value of payment 
	public function getPaymanetUrl($data){
		$url = $this->_getMasspayUrl();
		$i = 0;
		$baseCurrencyCode = Mage::app()->getStore()->getBaseCurrencyCode();
		foreach($data as $item){
			$url .= '&L_EMAIL'.$i.'='.$item['email'].'&L_AMT'.$i.'='.$item['amount'].'&CURRENCYCODE'.$i.'='.$baseCurrencyCode;
			// $url .= '&L_EMAIL'.$i.'='.$item['email'].'&L_AMT'.$i.'='.$item['amount'];
			$i++;
		}
		return $url;
	}
	
	protected function _getMasspayUrl(){
		$url = $this->_getApiEndpoint();
		$url .= '&METHOD=MassPay&RECEIVERTYPE=EmailAddress';
		return $url;
	}
	
	protected function _getApiEndpoint(){
		$isSandbox = Mage::getStoreConfig('paypal/wpp/sandbox_flag');
		$paypalApi = $this->_getPaypalApi();
        $url = sprintf('https://api-3t%s.paypal.com/nvp?', $isSandbox ? '.sandbox' : '');
		$url .= 'USER=' . $paypalApi['api_username'] . '&PWD=' . $paypalApi['api_password'] . '&SIGNATURE=' . $paypalApi['api_signature'] . '&VERSION=62.5';
		return $url;
    }
	
	protected function _getPaypalApi(){
		$data['api_username'] = Mage::getStoreConfig('paypal/wpp/api_username');
		$data['api_password'] = Mage::getStoreConfig('paypal/wpp/api_password');
		$data['api_signature'] = Mage::getStoreConfig('paypal/wpp/api_signature');
		return $data;
	}
}