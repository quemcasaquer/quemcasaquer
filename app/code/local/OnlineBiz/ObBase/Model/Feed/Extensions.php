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

class OnlineBiz_ObBase_Model_Feed_Extensions extends OnlineBiz_ObBase_Model_Feed_Abstract{
	
	 /**
     * Retrieve feed url
     *
     * @return string
     */
    public function getFeedUrl(){
		return OnlineBiz_ObBase_Helper_Config::EXTENSIONS_FEED_URL;
    }
	
	
	/**
	 * Checks feed
	 * @return 
	 */
	public function check(){
		if(!(Mage::app()->loadCache('obbase_extensions_feed')) || (time()-Mage::app()->loadCache('obbase_extensions_feed_lastcheck')) > 86400){
			$this->refresh();
		}
	}
	
	public function refresh(){
		$exts = array();
		try{
			$Node = $this->getFeedData();
			if(!$Node) return false;
			foreach($Node->children() as $ext){
				$exts[(string)$ext->name] = array(
					'display_name' => (string)$ext->display_name,
					'version' => (string)$ext->version,
					'url'		=> (string)$ext->url
				);
			}
			Mage::app()->saveCache(serialize($exts), 'obbase_extensions_feed');
			Mage::app()->saveCache(time(), 'obbase_extensions_feed_lastcheck');
			return true;
		}catch(Exception $E){
			return false;
		}
	}
}