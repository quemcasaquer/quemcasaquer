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

class OnlineBiz_ObBase_Model_Feed_Updates extends OnlineBiz_ObBase_Model_Feed_Abstract{
	
	 /**
     * Retrieve feed url
     *
     * @return string
     */
    public function getFeedUrl(){
		return OnlineBiz_ObBase_Helper_Config::UPDATES_FEED_URL;
    }
	
	/**
	 * Checks feed
	 * @return 
	 */
	public function check(){
		if((time()-Mage::app()->loadCache('obbase_updates_feed_lastcheck')) > Mage::getStoreConfig('obbase/feed/check_frequency')){
			$this->refresh();
		}
	}
	
	public function refresh(){
		$feedData = array();
		try{
			$Node = $this->getFeedData();
			if(!$Node) return false;
			foreach($Node->children() as $item){
				
				 if($this->isInteresting($item)){
				 	$date = strtotime((string)$item->date);
					if(!Mage::getStoreConfig('obbase/install/run') || (Mage::getStoreConfig('obbase/install/run') < $date)){
					 	$feedData[] = array(
	                 	   'severity'      => 3,
	                 	   'date_added'    => $this->getDate((string)$item->date),
	                 	   'title'         => (string)$item->title,
	                 	   'description'   => (string)$item->content,
	                 	   'url'           => (string)$item->url,
	                	);
					}
				 }
			}
			if ($feedData) {
                Mage::getModel('adminnotification/inbox')->parse(($feedData));
            }

			Mage::app()->saveCache(time(), 'obbase_updates_feed_lastcheck');
			return true;
		}catch(Exception $E){
			return false;			
		}
	}


	
	public function getInterests(){
		if(!$this->getData('interests')){
			$types = @explode(',', 'INFO,PROMO,UPDATE_RELEASE,NEW_RELEASE,INSTALLED_UPDATE');
			$this->setData('interests', $types);
		}
		return $this->getData('interests');
	}
	
	/**
	 * 
	 * @return 
	 */
	public function isInteresting($item){
		$interests = $this->getInterests();
		
		$types = @explode(",", (string)$item->type);
		$exts = @explode(",", (string)$item->extensions);
		
		$isInterestedInSelfUpgrades = array_search(OnlineBiz_ObBase_Model_Source_Updates_Type::TYPE_INSTALLED_UPDATE, $types);
		
		foreach($types as $type){
			
			if(array_search($type, $interests) !== false){
				return true;
			}
			if(($type == OnlineBiz_ObBase_Model_Source_Updates_Type::TYPE_UPDATE_RELEASE) && $isInterestedInSelfUpgrades){
				foreach($exts as $ext){
					if($this->isExtensionInstalled($ext)){
						return true;
					}
				}
			}
		}
		return false;
	}

	public function isExtensionInstalled($code){
		$modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());
		
        foreach ($modules as $moduleName) {
        	if($moduleName == $code){
        		return true;
        	}
        }
		return false;
	}

}