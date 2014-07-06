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
 
class OnlineBiz_ObBase_Block_Notification_Window extends Mage_Adminhtml_Block_Notification_Window{
	protected function _construct(){
        parent::_construct();

		if(!Mage::getStoreConfig('obbase/install/run')){
       		$c = Mage::getModel('core/config_data');
			$c->setScope('default')
				->setPath('obbase/install/run')
				->setValue(time())
				->save();
			$this->setHeaderText($this->__("OnlineBiz Notifications Setup"));	
       		$this->setIsFirstRun(1);
			$this->setIsHtml(1);
       
	   }
    }
	
	protected function _toHtml(){
		 if($this->getIsHtml()){
		 	$this->setTemplate('onlinebizsoft/obbase/notification/window.phtml');
		 }
		 return parent::_toHtml();
	}
	public function presetFirstSetup(){
		
	}
	public function getNoticeMessageText(){
		return $this->getData('notice_message_text');
	}
	
	
}
