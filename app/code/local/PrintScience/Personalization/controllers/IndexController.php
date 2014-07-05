<?php
/**
 * Personalization controller
 *
 */
class PrintScience_Personalization_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Personalization startShoppingCart action handler
     *
     * @return boolean
     */
    public function startShoppingCartAction()
    {
        $session       = Mage::getSingleton('core/session');
        $sessionHelper = Mage::helper('printscience_personalization/session');
        $quoteHelper   = Mage::helper('printscience_personalization/quote');
		$datasHelper   = Mage::helper('printscience_personalization/data');
		$homeUrl = Mage::getUrl('home');
        $request       = $this->getRequest();
        
        $apiSessionKey = $request->getParam('api_session_key');
        $isValidRequest = false;
        if ($apiSessionKey) {
            $cartItem = $quoteHelper->getCartItemByApiSessionKey($apiSessionKey);
            if ($cartItem) {
                $isValidRequest = true;
            }
        }
        if (!$isValidRequest) {
            $session->addError($this->__('Invalid request.'));
            //$this->_redirect('home');
			$datasHelper->_redirectToUrl($homeUrl);
            return false;
        }
        
        $cartItemData = $quoteHelper->getItemData($cartItem);

        $sessionHelper->addData($cartItemData['sessionKey'], array(
            'apiSessionKey' => $cartItemData['apiSessionKey'],
        ));

        //$this->_redirectUrl($cartItemData['appUrl']);
		$datasHelper->_redirectToUrl($cartItemData['appUrl']);
    }
    
    /**
     * Personalization startShoppingCart action handler
     *
     * @return boolean
     */
    public function resumeShoppingCartAction()
    {
        $session       = Mage::getSingleton('core/session');
        $sessionHelper = Mage::helper('printscience_personalization/session');
        $quoteHelper   = Mage::helper('printscience_personalization/quote');
		$datasHelper   = Mage::helper('printscience_personalization/data');
        $request       = $this->getRequest();
        $errorUrl      = Mage::getUrl('checkout/cart');
        
        $apiSessionKey = $request->getParam('api_session_key');
        $productId = intval($request->getParam('product'));
        $product   = Mage::getModel('catalog/product')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($productId);
        if (!$product->getId()) {
            $session->addError($this->__('Invalid request.'));
            //$this->_redirect($errorUrl);
			$datasHelper->_redirectToUrl($errorUrl);
            return false;
        }

        /**
         * Check if personalization is enabled for this product.
         * If this is simple product then we need to check its 'personalization_enabled' attribute.
         * If this is configurable product then we need to check 'personalization_enabled' attribute of its subproduct.
         * No other product types are supported.
         */
        $isValidProduct = true;
        
        $productTypeId = $product->getTypeId();
        if ($productTypeId == Mage_Catalog_Model_Product_Type::TYPE_SIMPLE) {
            if (!$product->getPersonalizationEnabled()) {
                $isValidProduct = false;
            }
        } elseif ($productTypeId == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
            $attributes = $request->getParam('super_attribute');
            $product    = $product->getTypeInstance()->getProductByAttributes($attributes);
            if ((!$product) || (!$product->getPersonalizationEnabled())) {
                $isValidProduct = false;
            }
        }  elseif ($productTypeId == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
			if (!$product->getPersonalizationEnabled()) {
                $isValidProduct = false;
            }
		} else {
            $isValidProduct = false;
        }
        
        if (!$isValidProduct) {
            $session->addError($this->__('Invalid Product.'));
            //$this->_redirect($errorUrl);
			$datasHelper->_redirectToUrl($errorUrl);
            return false;
        }
        // check if template ID is valid
        $templateId = $product->getPersonalizationTemplateId();
        if (!$templateId) {
            $session->addError($controller->__('Unable to start personalization: template ID is empty.'));    
            //$this->_redirect($errorUrl);
		    $datasHelper->_redirectToUrl($errorUrl);
            return false;          
        }
        
        $isValidRequest = false;
        if ($apiSessionKey) {
            $cartItem = $quoteHelper->getCartItemByApiSessionKey($apiSessionKey);
            if ($cartItem) {
                $isValidRequest = true;
            }
        }
        if (!$isValidRequest) {
            $session->addError($this->__('Invalid request.'));
            //$this->_redirect($errorUrl);
			$datasHelper->_redirectToUrl($errorUrl);
            return false;
        }
        
        $cartItemData = $quoteHelper->getItemData($cartItem);

        $sessionHelper->addData($cartItemData['sessionKey'], array(
            'apiSessionKey' => $cartItemData['apiSessionKey'],
        ));
        $sessionKey = $cartItemData['sessionKey'];
        $sessionKey    = $sessionHelper->generateKey();
        
        $buyRequestParams = $request->getParams();
        if (isset($buyRequestParams['uenc'])) {
            unset($buyRequestParams['uenc']);
        }
        $returnUrlParams['_query'] = $buyRequestParams;
        $returnUrlParams['personalization_session_key'] = $apiSessionKey;
        
        $successUrl = Mage::getUrl('personalization/index/success', $returnUrlParams);
        $failUrl    = Mage::getUrl('personalization/index/fail', $returnUrlParams);
        $cancelUrl  = Mage::getUrl('personalization/index/cancel', $returnUrlParams);
        
        // begin personalization
        $apiGateway  = Mage::getModel('printscience_personalization/apiGateway');
        $apiResponse = $apiGateway->resumePersonalization($apiSessionKey, $templateId, $successUrl, $failUrl, $cancelUrl);
        if (!$apiResponse) {
            $session->addError($this->__('Unable to start personalization: API response was empty.'));    
            //$this->_redirect($errorUrl);
			$datasHelper->_redirectToUrl($errorUrl);
            return false;
        }
        if ($apiResponse->getFaultCode()) {
            $session->addError($this->__('Unable to start personalization: ' . $apiResponse->getFaultString()));
            //$this->_redirect($errorUrl);
			$datasHelper->_redirectToUrl($errorUrl);
            return false;
        }
        $apiSessionKey = $apiResponse->getSessionKey();
        $appUrl = $apiResponse->getAppUrl();
        
        $sessionHelper->addData($apiSessionKey, array(
            'apiSessionKey' => $apiSessionKey,
            'appUrl' => $appUrl,
            'buyRequest' => serialize($buyRequestParams)
        ));
        
        $this->_redirectUrl($appUrl);
		//$datasHelper->_redirectToUrl($appUrl);
        return false;
    }

    /**
     * Personalization success action handler.
     *
     * @return boolean
     */
    public function successAction()
    {
        $session = Mage::getSingleton('core/session');
        $request = $this->getRequest();
        $datasHelper = Mage::helper('printscience_personalization/data');
		$homeUrl = Mage::getUrl('home');
		
        if (!$this->_isValidRequest()) {
            $session->addError($this->__('Invalid request.'));  
            //$this->_redirect('home'); 
			$datasHelper->_redirectToUrl($homeUrl);
            return false;             
        }
        
        $product = $this->_initProduct();
        if (!$product) {
            $session->addError($this->__('Personalization error: product not found.'));
            //$this->_redirect('home'); 
			$datasHelper->_redirectToUrl($homeUrl);
            return false; 
        }
         
        $sessionHelper = Mage::helper('printscience_personalization/session');
        $quoteHelper   = Mage::helper('printscience_personalization/quote');
        $apiGateway    = Mage::getModel('printscience_personalization/apiGateway');
        
        $sessionKey    = $request->getParam('personalization_session_key');
        $sessionData   = $sessionHelper->getData($sessionKey);
        $apiSessionKey = $sessionData['apiSessionKey'];
        
        // is item with this API session key already in cart?
        $cartItem = $quoteHelper->getCartItemByApiSessionKey($apiSessionKey);
        
        if (!$cartItem) {
            $errorUrl = $product->getProductUrl();
        } else {
            $errorUrl = Mage::getUrl('checkout/cart');
        }
        
        $apiResponse = $apiGateway->getPreview($apiSessionKey);
        if ($apiResponse->getFaultCode()) {
            $session->addError($this->__('Personalization was not completed.'));
            //$this->_redirectUrl($errorUrl);      
			$datasHelper->_redirectToUrl($errorUrl);
            return false;      
        }
		if (!$cartItem) {
            Mage::register('PrintScience_Personalization_calledFromSuccessAction', true);
            $this->_forward('add', 'cart', 'checkout');
        } else {
			$checkOutUrl = Mage::getUrl('checkout/cart');
			$datasHelper->_redirectToUrl($checkOutUrl);
		}
    }
    
    /**
     * Personalization cancel action handler
     *
     * @return boolean
     */
    public function cancelAction()
    {
        $session = Mage::getSingleton('core/session');
        $request = $this->getRequest();
		$datasHelper = Mage::helper('printscience_personalization/data');
        $homeUrl = Mage::getUrl('home');
		
        if (!$this->_isValidRequest()) {
            $session->addError($this->__('Invalid request.'));  
            //$this->_redirect('home'); 
			$datasHelper->_redirectToUrl($homeUrl);
            return false;             
        } 
               
        $product = $this->_initProduct();
        if (!$product) {
            $session->addError($this->__('Personalization error: product not found.'));  
            //$this->_redirect('home'); 
			$datasHelper->_redirectToUrl($homeUrl);
            return false; 
        }      
        
        $apiGateway    = Mage::getModel('printscience_personalization/apiGateway');
        $sessionHelper = Mage::helper('printscience_personalization/session');
        $quoteHelper   = Mage::helper('printscience_personalization/quote');
        
        $sessionKey    = $request->getParam('personalization_session_key');
        $sessionData   = $sessionHelper->getData($sessionKey);
        $apiSessionKey = $sessionData['apiSessionKey'];
        
        // is item with this API session key already in cart?
        $cartItem = $quoteHelper->getCartItemByApiSessionKey($apiSessionKey);
        
        if (!$cartItem) {
            $apiGateway->endPersonalization($apiSessionKey);
            $sessionHelper->unsetData($sessionKey);
            $redirectUrl = $product->getProductUrl();
        } else {
            $redirectUrl = Mage::getUrl('checkout/cart');
        }
        
        //$this->_redirectUrl($redirectUrl);
		$datasHelper->_redirectToUrl($redirectUrl);
    }
    
    /**
     * Personalization fail action handler
     *
     */
    public function failAction()
    {
        $this->_forward('cancel');
    }
    
    /**
     * Initialize product instance from request data
     *
     * @return Mage_Catalog_Model_Product | false
     */
    private function _initProduct()
    {
        $productId = (int) $this->getRequest()->getParam('product');
        if ($productId) {
            $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($productId);
            if ($product->getId()) {
                return $product;
            }
        }
        return false;
    }    
    
    /**
     * Check if current request to Success, Cancel or Fail action is valid
     *
     * @return boolean
     */
    private function _isValidRequest() 
    {
        $request       = $this->getRequest();
        $sessionHelper = Mage::helper('printscience_personalization/session');
        $quoteHelper    = Mage::helper('printscience_personalization/quote');
        
        $sessionKey = $request->getParam('personalization_session_key');
                
        if ($sessionKey) {
            $sessionData = $sessionHelper->getData($sessionKey);
            if ($sessionData) {
                // no need to validate buyRequest params if item is already in cart
                $cartItem = $quoteHelper->getCartItemByApiSessionKey($sessionData['apiSessionKey']); 
                if ($cartItem) {
                    return true;
                } else {
                    $buyRequestParams = $request->getParams();
                    unset($buyRequestParams['personalization_session_key']);
                    if (unserialize($sessionData['buyRequest']) == $buyRequestParams) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}