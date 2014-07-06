<?php
/**
 * Export Product data to xml data
 *
 * @category   OnlineBiz
 * @package    OnlineBiz_Facebookstore
 * @author     Nguyen Viet Tung (http://onlinebizsoft.com)
 */
class OnlineBiz_Facebookstore_ChannelController extends Mage_Core_Controller_Front_Action
{
	
	public function indexAction()
	{
		
		$expires = 365*24*60*60; //1 year
		$this->getResponse()
				->setHeader('Pragma', '', true)
				->setHeader('Cache-Control', 'maxage='.$expires, true)
				->setHeader('Expires', gmdate('D, d M Y H:i:s', time()+$expires), true)
				->setHeader('Last-Modified', gmdate('D, d M Y H:i:s', time()))
				;
		
		if($this->getRequest()->getHeader('If-Modified-Since')) {
			$this->getResponse()->setHttpResponseCode(304);
		}
		
		$locale = $this->getRequest()->getParam('locale', false);
		if(!$locale) {
			$locale = Mage::getSingleton('facebookstore/config')->getLocale();
		}
		
		$this->getResponse()->setBody(
			$this->getLayout()->createBlock('facebookstore/channel')
			->setLocale($locale)
			->toHtml()
		);
	}
	
}