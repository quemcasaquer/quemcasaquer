<?php
/**
 * Print Science personalization observer
 *
 */
class PrintScience_Personalization_Model_Observer
{   
    /**
     * Event observer
     *
     * @var Varien_Event_Observer
     */
    private $_observer;
    
    /**
     * Check if current action is 'checkout_cart_add' and start personalization process 
     *
     * @param Varien_Event_Observer $observer
     */
    public function checkForCheckoutCartAddAction($observer) 
    {
        $this->_observer = $observer;
        
        $controller = $observer->getControllerAction();
        $sessionHelper = Mage::helper('printscience_personalization/session');
        $request    = Mage::app()->getRequest();
        
        $sessionKey = $request->getParam('api_session_key');

        if (($controller->getFullActionName() == 'checkout_cart_add') 
            && (!Mage::registry('PrintScience_Personalization_calledFromSuccessAction'))) {         
            $this->startPersonalization();
        }elseif($sessionKey !='' && strcasecmp($controller->getFullActionName(), 'printscience_personalization_index_resumeShoppingCart') === 0){
            //$this->startPersonalization();
        }
    }
    
    /**
     * Start personalization process
     * 
     * @param Mage_Core_Controller_Request_Http $product
     */
    private function startPersonalization()
    {
        $controller = $this->_observer->getControllerAction();
        $request    = Mage::app()->getRequest();
        $response   = Mage::app()->getResponse();
        $session    = Mage::getSingleton('core/session');
		$datasHelper = Mage::helper('printscience_personalization/data');
        
        $productId = intval($request->getParam('product'));
        $product   = Mage::getModel('catalog/product')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($productId);
        if (!$product->getId()) {
            return false;
        }
         
        $errorUrl = $product->getProductUrl();
           
        /**
         * Check if personalization is enabled for this product.
         * If this is simple product then we need to check its 'personalization_enabled' attribute.
         * If this is configurable product then we need to check 'personalization_enabled' attribute of its subproduct.
         * No other product types are supported.
         */
        $productTypeId = $product->getTypeId();
        if ($productTypeId == Mage_Catalog_Model_Product_Type::TYPE_SIMPLE) {
            if (!$product->getPersonalizationEnabled()) {
                return false;
            }
        } elseif ($productTypeId == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
            $attributes = $request->getParam('super_attribute');
            $product    = $product->getTypeInstance()->getProductByAttributes($attributes);
            $product = Mage::getModel('catalog/product')->load($product->getId());
            if ((!$product) || (!$product->getPersonalizationEnabled())) {
                return false;
            }
        } elseif ($productTypeId == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
            if (!$product->getPersonalizationEnabled()) {
                return false;
            }
        } else {
            return false;
        }
        
        // check if template ID is valid
        $templateId = $product->getPersonalizationTemplateId();
        if (!$templateId) {
            $session->addError($controller->__('Unable to start personalization: template ID is empty.'));    
            $response->setRedirect($errorUrl)->sendHeaders();
			//$datasHelper->_redirectToUrl($errorUrl);
    		exit();	      
        }
        
        $sessionHelper = Mage::helper('printscience_personalization/session');
        $sessionKey    = $sessionHelper->generateKey();
        
        $buyRequestParams = $request->getParams();
        if (isset($buyRequestParams['uenc'])) {
            unset($buyRequestParams['uenc']);
        }
        $returnUrlParams['_query'] = $buyRequestParams;
        if (isset($returnUrlParams['uenc'])) {
            unset($returnUrlParams['uenc']);
        }
        $returnUrlParams['personalization_session_key'] = $sessionKey;
        
        $successUrl = Mage::getUrl('personalization/index/success', $returnUrlParams);
        $failUrl    = Mage::getUrl('personalization/index/fail', $returnUrlParams);
        $cancelUrl  = Mage::getUrl('personalization/index/cancel', $returnUrlParams);
        // begin personalization
        $apiGateway  = Mage::getModel('printscience_personalization/apiGateway');
        $apiResponse = $apiGateway->beginPersonalization($templateId, $successUrl, $failUrl, $cancelUrl);
        if (!$apiResponse) {
            $session->addError($controller->__('Unable to start personalization: API response was empty.'));    
            $response->setRedirect($errorUrl)->sendHeaders();
			//$datasHelper->_redirectToUrl($errorUrl);
            exit();
        }
        if ($apiResponse->getFaultCode()) {
            $session->addError($controller->__('Unable to start personalization: ' . $apiResponse->getFaultString()));
            $response->setRedirect($errorUrl)->sendHeaders();
			//$datasHelper->_redirectToUrl($errorUrl);
            exit();
        }       
        $apiSessionKey = $apiResponse->getSessionKey();
        $appUrl = $apiResponse->getAppUrl();

        $sessionHelper->addData($sessionKey, array(
            'apiSessionKey' => $apiSessionKey,
            'appUrl' => $appUrl,
            'buyRequest' => serialize($buyRequestParams)
        ));
		
        $response->setRedirect($appUrl)->sendHeaders();
		
		//$datasHelper->_redirectToUrl($appUrl);
        exit();
    }
    
    /**
     * End personalization process when item is deleted from shopping cart
     *
     * @param Varien_Event_Observer $observer
     */
    public function endPersonalization($observer) 
    {
        $this->_observer = $observer;
        
        $quoteHelper = Mage::helper('printscience_personalization/quote');
        $apiGateway  = Mage::getModel('printscience_personalization/apiGateway');
        
        $quoteItem = $observer->getEvent()->getData('quote_item');
        $data = $quoteHelper->getItemData($quoteItem);
        if ($data) {
            $apiGateway->endPersonalization($data['apiSessionKey']);
        }
    }
    
    /**
     * Add personalization options to cart item
     *
     * @param Varien_Event_Observer $observer
     */
    public function addOptionsToCartItem($observer)
    {
        $this->_observer = $observer;
        
        $request = Mage::app()->getRequest();
        
        $sessionKey = $request->getParam('personalization_session_key');
        
        $quoteItem = $observer->getEvent()->getData('quote_item');
        $productItem = Mage::getModel('catalog/product')->load($quoteItem->getProduct()->getId());
		if (!$sessionKey) {
        //if ((!$productItem->getPersonalizationEnabled()) || (!$sessionKey)) {
            return false;
        }
        
        $sessionHelper = Mage::helper('printscience_personalization/session');
        $sessionData   = $sessionHelper->getData($sessionKey);
        if (!$sessionData) {
            return false;
        }
        unset($sessionData['buyRequest']);
        $sessionData['sessionKey'] = $sessionKey;
        
        $parentItem = $quoteItem->getParentItem();
        if ($parentItem) {
            $quoteItem = $parentItem; 
        }
        $quoteHelper = Mage::helper('printscience_personalization/quote');
        
        
        $quoteHelper->addItemData($quoteItem, $sessionData);
    }
    
    /**
     * Add personalization options to order items
     *
     * @param Varien_Event_Observer $observer
     */
    public function addOptionsToOrderItems($observer)
    {
        $this->_observer = $observer;
        
        $quoteHelper = Mage::helper('printscience_personalization/quote');
        $orderHelper = Mage::helper('printscience_personalization/order');
        $apiGateway  = Mage::getModel('printscience_personalization/apiGateway');
        
        $order      = $observer->getEvent()->getData('order');
        $orderItems = $order->getItemsCollection();
        foreach ($orderItems as $orderItem) {
            $quoteItemOptions = Mage::getModel('sales/quote_item_option')
                ->getCollection()
                ->addItemFilter($orderItem->getQuoteItemId());
            if (!count($quoteItemOptions)) {
                return false;
            }
            $quoteItem = Mage::getModel('sales/quote_item')
                ->load($orderItem->getQuoteItemId())
                ->setOptions($quoteItemOptions);            
            if (!$quoteItem->getId()) {
                continue;
            }
            
			if($personalizationInfo = $quoteItem->getData('personalization_info')){
				$personalizationInfo = unserialize($personalizationInfo);
				if(!empty($personalizationInfo)){
					$orderHelper->addItemData($orderItem,$personalizationInfo);
				}
				continue;
			}

			
            $quoteItemData = $quoteHelper->getItemData($quoteItem);
            if (!$quoteItemData) {
                continue;
            }
                        
            $apiResponse = $apiGateway->getPreview($quoteItemData['apiSessionKey']);
            if ((!$apiResponse) || ($apiResponse->getFaultCode())) {
				continue;
            }
                        
            $orderHelper->addItemData($orderItem, array(
                'preview_pdf_url' => $apiResponse->getPdfUrl(),
				'product_images' =>$apiResponse->getPeviewUrls()
            ));
            
            $apiGateway->endPersonalization($quoteItemData['apiSessionKey']);
        } 
    }
        
    /**
     * Intialize personalization gallery on shopping cart
     *
     * @param Varien_Event_Observer $observer
     * @return boolean
     */
    public function initGallery($observer)
    {
        $this->_observer = $observer;
        
        $layout      = Mage::app()->getLayout();
        $quoteHelper = Mage::helper('printscience_personalization/quote');
        
        $cartItems   = Mage::getModel('checkout/cart')->getItems();
        if (!$cartItems) {
            return false;
        }
        $containsPersonalizedItems = false;
        foreach ($cartItems as $item) {
            if ($quoteHelper->getItemData($item)) {
                $containsPersonalizedItems = true;
                break;
            }
        }
        //if ($containsPersonalizedItems) {
        
        $layout->getBlock('head')
        	->addJs('printscience_personalization/jquery/jquery.cycle/jquery.cycle.lite.min.js')
                /*->addJs('printscience_personalization/jquery/fancybox/jquery.fancybox-1.3.4.pack.js')*/
                ->addItem('js_css', 'printscience_personalization/jquery/fancybox/jquery.fancybox-1.3.4.css')
                ->addJs('printscience_personalization/gallery.js')
                ->addItem('js_css', 'printscience_personalization/gallery.css');
        
            /*$layout->getBlock('head')
                ->addJs('printscience_personalization/jquery/jquery-1.4.2.min.js')
                ->addJs('printscience_personalization/jquery/jquery.cycle/jquery.cycle.lite.min.js')
                ->addJs('printscience_personalization/jquery/fancybox/jquery.fancybox-1.3.4.pack.js')
                ->addItem('js_css', 'printscience_personalization/jquery/fancybox/jquery.fancybox-1.3.4.css')
                ->addJs('printscience_personalization/gallery.js')
                ->addItem('js_css', 'printscience_personalization/gallery.css');*/
        //}
    }
    
    /**
     * Ensure that all personalized items in shopping cart have proper API session key
     *
     * @param Varien_Event_Observer $observer
     */
    public function checkCartItems($observer)
    {
        $this->_observer = $observer;
        
        $quoteHelper = Mage::helper('printscience_personalization/quote');
        $apiGateway  = Mage::getModel('printscience_personalization/apiGateway');        

        $quote = $observer->getEvent()->getData('data_object');
        $quoteItems = $quote->getItemsCollection();
        foreach ($quoteItems as $item) {
            $data = $quoteHelper->getItemData($item);
            if (($data) && (isset($data['apiSessionKey']))) {
                $apiResponse = $apiGateway->getPreview($data['apiSessionKey']);
                if ((!$apiResponse) || ($apiResponse->getFaultCode())) {
                    // remove by hieptq
                	//$quote->removeItem($item->getItemId());
                }
            }
        }        
    }
	public function addToCartComplete(Varien_Event_Observer $observer) {
		// Send the user to the Item added page
		$response = $observer->getResponse();
		$request = $observer->getRequest();
		$datasHelper = Mage::helper('printscience_personalization/data');
		$checkOutUrl = Mage::getUrl('checkout/cart');
		$datasHelper->_redirectToUrl($checkOutUrl, "1");
		exit;
	}
}
