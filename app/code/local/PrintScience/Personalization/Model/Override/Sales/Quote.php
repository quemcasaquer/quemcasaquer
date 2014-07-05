<?php
class PrintScience_Personalization_Model_Override_Sales_Quote extends Mage_Sales_Model_Quote
{
	public function updateItem($itemId, $buyRequest, $params = null)
    {
        $item = $this->getItemById($itemId);
        $personalization_session_key = $item->getBuyRequest()->getData('personalization_session_key');
    	if(!empty($personalization_session_key)){
        	$item->setQty($buyRequest->getQty());
        	return $item;
        }
        else{
        	return parent::updateItem($itemId, $buyRequest, $params);
        }
    }
}
