<?php

class PrintScience_Personalization_Model_Override_Checkout_Cart extends Mage_Checkout_Model_Cart
{
    
    /**
     * Convert order item to quote item
     *
     * @param Mage_Sales_Model_Order_Item $orderItem
     * @param mixed $qtyFlag if is null set product qty like in order
     * @return Mage_Checkout_Model_Cart
     */
    public function addOrderItem($orderItem, $qtyFlag=null)
    {
        /* @var $orderItem Mage_Sales_Model_Order_Item */
        if (is_null($orderItem->getParentItem())) {
            $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($orderItem->getProductId());
            if (!$product->getId()) {
                return $this;
            }

            $info = $orderItem->getProductOptionByCode('info_buyRequest');
			
			/* add info */
            $quoteHelper = Mage::helper('printscience_personalization/quote');
			//$quoteItem = Mage::getModel('sales/quote_item')->load( $orderItem->getQuoteItemId());
			
			$quoteItemOptions = Mage::getModel('sales/quote_item_option')
                ->getCollection()
                ->addItemFilter($orderItem->getQuoteItemId());
           $quoteItem = Mage::getModel('sales/quote_item')
                ->load($orderItem->getQuoteItemId())
                ->setOptions($quoteItemOptions);
			
			$personalizationInfo = $quoteHelper->getItemData($quoteItem);
			if(!empty($personalizationInfo)){
				$info['personalization'] = $personalizationInfo;
			}
			
        	$orderPersonalizationInfo = $orderItem->getProductOptionByCode('personalization');
			if(!empty($orderPersonalizationInfo)){
				$info['order_personalization'] = $orderPersonalizationInfo;
			}
			
            $info = new Varien_Object($info);
            if (is_null($qtyFlag)) {
                $info->setQty($orderItem->getQtyOrdered());
            } else {
                $info->setQty(1);
            }

            $this->addProduct2($product, $info);
        }
        return $this;
    }

    

    

    /**
     * Add product to shopping cart (quote)
     *
     * @param   int|Mage_Catalog_Model_Product $productInfo
     * @param   mixed $requestInfo
     * @return  Mage_Checkout_Model_Cart
     */
    public function addProduct2($productInfo, $requestInfo=null)
    {
   
		$product = $this->_getProduct($productInfo);
        $request = $this->_getProductRequest($requestInfo);
        
        $productId = $product->getId();

        if ($product->getStockItem()) {
            $minimumQty = $product->getStockItem()->getMinSaleQty();
            //If product was not found in cart and there is set minimal qty for it
            if ($minimumQty && $minimumQty > 0 && $request->getQty() < $minimumQty
                && !$this->getQuote()->hasProductId($productId)
            ){
                $request->setQty($minimumQty);
            }
        }

        if ($productId) {
            try {
                $result = $this->getQuote()->addProduct($product, $request);
            } catch (Mage_Core_Exception $e) {
                $this->getCheckoutSession()->setUseNotice(false);
                $result = $e->getMessage();
            }
            /**
             * String we can get if prepare process has error
             */
            if (is_string($result)) {
                $redirectUrl = ($product->hasOptionsValidationFail())
                    ? $product->getUrlModel()->getUrl(
                        $product,
                        array('_query' => array('startcustomization' => 1))
                    )
                    : $product->getProductUrl();
                $this->getCheckoutSession()->setRedirectUrl($redirectUrl);
                if ($this->getCheckoutSession()->getUseNotice() === null) {
                    $this->getCheckoutSession()->setUseNotice(true);
                }
                Mage::throwException($result);
            }
        } else {
            Mage::throwException(Mage::helper('checkout')->__('The product does not exist.'));
        }

    	if($personalizationInfo = $request->getData('personalization')){
			$value = serialize($personalizationInfo);
			$option = array(
	            'product_id' => $result->getProductId(),
	            'product'    => $result->getProduct(),
	            'code'       => 'personalization',
	            'value'      => $value
        	);  
        	    
        	
        	$result->addOption($option);
        	
		}
        
    	if($orderPersonalizationInfo = $request->getData('order_personalization')){
			$orderPersonalizationInfo = serialize($orderPersonalizationInfo);
			
			$result->setData('personalization_info',$orderPersonalizationInfo);
		}
		
        //Mage::dispatchEvent('checkout_cart_product_add_after', array('quote_item' => $result, 'product' => $product));
       	
		
		
		$this->getCheckoutSession()->setLastAddedProductId($productId);
        return $this;
    }
}
