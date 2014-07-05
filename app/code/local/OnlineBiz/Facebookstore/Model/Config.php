<?php
/**
 * Export Product data to xml data
 *
 * @category   OnlineBiz
 * @package    OnlineBiz_Facebookstore
 * @author     Nguyen Viet Tung (http://onlinebizsoft.com)
 */
class OnlineBiz_Facebookstore_Model_Config
{
	const XML_PATH_ENABLED = 'facebookstore/connect/enabled';
	const XML_PATH_API_KEY = 'facebookstore/connect/api_key';
	const XML_PATH_SECRET = 'facebookstore/connect/secret';
	const XML_PATH_LOCALE = 'facebookstore/connect/locale';
	
    public function isEnabled($storeId=null)
    {
		if( Mage::getStoreConfigFlag(self::XML_PATH_ENABLED, $storeId) && 
			$this->getApiKey($storeId) && 
			$this->getSecret($storeId))
		{
        	return true;
        }
        
        return false;
    }
	
    public function getApiKey($storeId=null)
    {
    	return trim(Mage::getStoreConfig(self::XML_PATH_API_KEY, $storeId));
    }
    
    public function getSecret($storeId=null)
    {
    	return trim(Mage::getStoreConfig(self::XML_PATH_SECRET, $storeId));
    }
	
    
    public function getRequiredPermissions()
    {
    	return array('email,offline_access,user_birthday,status_update,publish_stream,user_location,user_hometown');
    }
    
    public function getLocale($storeId=null)
    {
    	return Mage::getStoreConfig(self::XML_PATH_LOCALE, $storeId);
    }

}
