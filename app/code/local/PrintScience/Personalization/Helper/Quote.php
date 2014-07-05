<?php
/**
 * Print Science personalization quote helper
 *
 */
class PrintScience_Personalization_Helper_Quote extends Mage_Core_Helper_Abstract 
{
    /**
     * Get shopping cart quote item by personalization API session key
     *
     * @param string $apiSessionKey
     * @return Mage_Sales_Model_Quote_Item | false
     */
    public function getCartItemByApiSessionKey($apiSessionKey) 
    {
        $cartItems = Mage::getModel('checkout/cart')->getItems();
        if (empty($cartItems)) {
            return false;
        }
        foreach ($cartItems as $item) {
            $data = $this->getItemData($item);
            if (($data) && ($data['apiSessionKey'] == $apiSessionKey)) {
                return $item;
            }
        }
        return false;
    }
    
    /**
     * Get personalization data of shopping cart quote item
     *
     * @param Mage_Sales_Model_Quote_Item $item
     * @return array | false
     */
    public function getItemData($item) 
    {
        $option = $item->getOptionByCode('personalization');
        if (!$option) {
            return false;
        }
        return unserialize($option->getValue());
    }
    
    /**
     * Add personalization data to shopping cart quote item
     *
     * @param Mage_Sales_Model_Quote_Item $item
     * @param array $data
     */
    public function addItemData($item, $data)
    {
        $option = array(
            'product_id' => $item->getProductId(),
            'product'    => $item->getProduct(),
            'code'       => 'personalization',
            'value'      => serialize($data)
        );      
        $item->addOption($option);     
    }
    
	public function getPersonalizationPreviews($quoteId)
    {
        $default = array();
       
		$quoteItemOptions = Mage::getModel('sales/quote_item_option')
	               	->getCollection()
	                ->addItemFilter($quoteId);
	    $quoteItem = Mage::getModel('sales/quote_item')
	                ->load($quoteId)
	                ->setOptions($quoteItemOptions);
		$personalizationInfo = $this->getItemData($quoteItem);
        
		$apiSessionKey = $personalizationInfo['apiSessionKey'];
		
        $apiResponse = Mage::getModel('printscience_personalization/apiGateway')
            ->getPreview($apiSessionKey);
        if ((!$apiResponse) || ($apiResponse->getFaultCode())) {
        	//$item = $this->getItem();
			
			if($personalizationInfo = $quoteItem->getData('personalization_info')){
				$personalizationInfo = unserialize($personalizationInfo);
				if(!empty($personalizationInfo['product_images'])){
					$default = $personalizationInfo['product_images'];
				}
			}
            return $default;
        }
        return $apiResponse->getPeviewUrls();
    }
}