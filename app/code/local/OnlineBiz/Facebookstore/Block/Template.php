<?php
/**
 * Export Product data to xml data
 *
 * @category   OnlineBiz
 * @package    OnlineBiz_Facebookstore
 * @author     Nguyen Viet Tung (http://onlinebizsoft.com)
 */
class OnlineBiz_Facebookstore_Block_Template extends Mage_Core_Block_Template
{
	
	public function isSecure()
	{
		return Mage::app()->getStore()->isCurrentlySecure();
	}
	
	public function getConnectUrl()
	{
		return $this->getUrl('facebookstore/customer_account/connect', array('_secure'=>true));
	}
	
	public function getChannelUrl()
	{
		return $this->getUrl('facebookstore/channel', array('_secure'=>$this->isSecure(),'locale'=>$this->getLocale()));
	}	
	
	public function getRequiredPermissions()
	{
		return json_encode('email,offline_access,user_birthday,status_update,publish_stream,user_location,user_hometown');
	}
	
	public function isEnabled()
	{
		return Mage::getSingleton('facebookstore/config')->isEnabled();
	}
	
	public function getApiKey()
	{
		return Mage::getSingleton('facebookstore/config')->getApiKey();
	}
	
	public function getLocale()
	{
		return Mage::getSingleton('facebookstore/config')->getLocale();
	}
	
    protected function _toHtml()
    {
        if (!$this->isEnabled()) {
            return '';
        }
        return parent::_toHtml();
    }
	
	protected function _afterToHtml($html){
		if(!Mage::helper('facebookstore')->isActivated()) 
			return '';
		return $html;
	}
}