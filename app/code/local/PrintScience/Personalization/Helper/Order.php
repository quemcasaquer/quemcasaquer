<?php
/**
 * Print Science personalization quote helper
 *
 */
class PrintScience_Personalization_Helper_Order extends Mage_Core_Helper_Abstract 
{
    /**
     * Get personalization data of the order item
     *
     * @param Mage_Sales_Model_Order_Item $item
     * @return array | false
     */
    public function getItemData($item) 
    {
        $data = $item->getProductOptionByCode('personalization');
        if (!$data) {
            return false;
        }
        return $data;
    }
        
    /**
     * Add personalization data to the order item
     *
     * @param Mage_Sales_Model_Order_Item $item
     * @param array $data
     */
    public function addItemData($item, $data)
    {
        $options = $item->getProductOptions();
        $options += array(
            'personalization' => $data
        );
        $item->setProductOptions($options);
    }
}