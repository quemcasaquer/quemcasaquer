<?php
/**
 * Extensions Manager Extension
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://store.onlinebizsoft.com/license.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to admin@onlinebizsoft.com so we can mail you a copy immediately.
 *
 * @category   Magento Extensions
 * @package    ExtensionManager
 * @author     OnlineBiz <sales@onlinebizsoft.com>
 * @copyright  2007-2011 OnlineBiz
 * @license    http://store.onlinebizsoft.com/license.txt
 * @version    1.0.1
 * @link       http://store.onlinebizsoft.com
 */
class OnlineBiz_ObBase_Block_Store extends Mage_Adminhtml_Block_Template
{

    protected $_extensions_cache = array();
    protected $_extensions;

    protected $_section = '';
    protected $_store_data = null;


    protected function _prepareLayout()
    {
		$this->_section = $this->getAction()->getRequest()->getParam('section', false);
		if($this->_section == 'storeview') {
            $this->getLayout()
                    ->getBlock('head')
                    ->addJs('onlinebizsoft/obbase/base.js');
		
		}
        $this->setData('store_data', $this->_getStoreData());
        
        parent::_prepareLayout();
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->_section == 'storeview') {
            return parent::_toHtml();
        } else {
            return '';
        }
        
    }

    /**
     * Fetch store data and return as Varien Object
     * @return Varien_Object
     */
    protected function _getStoreData()
    {
        if (!is_null($this->_store_data))
            return $this->_store_data;
        $storeData = array();
        $connection = $this->_getStoreConnection();
        $storeResponse = $connection->read();

        if ($storeResponse !== false) {
            $storeResponse = preg_split('/^\r?$/m', $storeResponse, 2);
            $storeResponse = trim($storeResponse[1]);
            Mage::app()->saveCache($storeResponse, OnlineBiz_ObBase_Helper_Config::STORE_RESPONSE_CACHE_KEY);
        }
        else {
            $storeResponse =  Mage::app()->loadCache(OnlineBiz_ObBase_Helper_Config::STORE_RESPONSE_CACHE_KEY);
            if (!$storeResponse) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('Sorry, but Extensions Store is not available now. Please try again in a few minutes.'));
            }
        }

        $connection->close();
        $this->_store_data = new Varien_Object(array('text_response' => $storeResponse));
        return $this->_store_data;
    }

    /**
     * Returns URL to store
     * @return Varien_Http_Adapter_Curl
     */
    protected function _getStoreConnection()
    {
        $params = array(

        );
        $url = array();
        foreach ($params as $k => $v) {
            $url[] = urlencode($k) . "=" . urlencode($v);
        }
        $url = rtrim(OnlineBiz_ObBase_Helper_Config::STORE_URL) . (sizeof($url) ? ("?" . implode("&", $url)) : "");

        $curl = new Varien_Http_Adapter_Curl();
        $curl->setConfig(array(
                              'timeout' => 5
                         ));
        $curl->write(Zend_Http_Client::GET, $url, '1.0');

        return $curl;
    }


}
 
