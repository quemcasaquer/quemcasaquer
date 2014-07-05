<?php
/**
 * Export Product data to xml data
 *
 * @category   OnlineBiz
 * @package    OnlineBiz_Facebookstore
 * @author     Nguyen Viet Tung (http://onlinebizsoft.com)
 */
class OnlineBiz_Facebookstore_Model_Observer
{    

    public function postWallAffterCheckOut($observer)
    {
		try{
			$quote = $observer->getEvent()->getQuote();
			$request = $observer->getEvent()->getRequest();
			$items = $quote->getItemsCollection()->getItems();
			$accessToken = '';
			if(Mage::getSingleton('customer/session')->isLoggedIn()){
				
				$customer = Mage::getSingleton('customer/session')->getCustomer();
				$accessToken = $customer->getFacebookToken();
				$session = new Varien_Object(json_decode($accessToken, true));

				Mage::getSingleton('facebookstore/session')->getClient()->setSession($session);
			}
			if(Mage::getSingleton('facebookstore/session')->getData() && $accessToken!=''){
				foreach( $items as $item )
				{
					$product = Mage::getModel('catalog/product')->load($item->getProduct()->getId());
					$store = Mage::app()->getStore()->getName().' '.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
					$messageContent = Mage::getStoreConfig('facebookstore/postwall/template_message_place_order');
					$messageContent = str_ireplace("{product_quantity}", $item->getQty(), $messageContent);
					$messageContent = str_ireplace("{store_link}", $store, $messageContent);
					$description = Mage::getStoreConfig('facebookstore/postwall/template_description_place_order');
					$description = str_ireplace("{product_name}", $product->getName(), $description);
					$description = str_ireplace("{product_price}", Mage::helper('core')->currency($item->getPrice(),true,false), $description);
					$description = str_ireplace("{product_link}", $product->getProductUrl(), $description);
					
					$params = array ('message'=> utf8_encode($messageContent),'description'=>$description, 'link'=>$product->getProductUrl()) ;
					if(Mage::getStoreConfig('facebookstore/postwall/post_image')){
						$params['picture'] = (string)Mage::helper('catalog/image')->init($product, 'image')->resize(120);
					}
					if(Mage::getStoreConfig('facebookstore/postwall/enabled')){
						Mage::getSingleton('facebookstore/session')->getClient()->postWall("/me/feed",  $params);
					}
					
				}
			}
		} catch(Exception $e){
			
		}
        return $this;    
    }
    
	public function setResHeader($observer)
	{
		if(Mage::app()->getRequest()->getModuleName()=='facebookstore')
			Mage::app()->getResponse()->setHeader('P3P','CP="ALL DSP COR CURa ADMa DEVa TAIa IVAi IVDi CONi HISi TELi OUR IND PHY ONL FIN COM NAV INT DEM GOV"',true);
		return $this;
	}
	public function addLayoutHandle(Varien_Event_Observer $observer)
    {
		if(Mage::app()->getRequest()->getModuleName()!='facebookstore')
			return $this;
        /* @var $update Mage_Core_Model_Layout_Update */
		$update = $observer->getEvent()->getLayout()->getUpdate();
		$update->addHandle('init_facebookstore');
		return $this;
    }
	
    /**
    * Use different layout for facebook page
    *
    * @param mixed $observer
    */
    public function controller_front_init_before($observer)
    {
        if (Mage::helper('facebookstore')->getPageStyle()==2) {
            Mage::getConfig()->setNode('frontend/layout/updates/facebookstore/file', 'onlinebizsoft/facebooktimeline.xml');
        }
    }
}