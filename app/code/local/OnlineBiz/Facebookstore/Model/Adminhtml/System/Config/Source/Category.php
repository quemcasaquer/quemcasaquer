<?php
/**
 * Export Product data to xml data
 *
 * @category   OnlineBiz
 * @package    OnlineBiz_Facebookstore
 * @author     Nguyen Viet Tung (http://onlinebizsoft.com)
 */
class OnlineBiz_Facebookstore_Model_Adminhtml_System_Config_Source_Category
{

    /*
     * Returns array of affiliate
     * @return array Usernames array like user_id => user_firstname + user_lastname
     */
    public static function toShortOptionArray()
    {
        $result = array();
        $collection = Mage::getModel('catalog/category')->getCollection();
        $collection->addAttributeToSelect('name')->setStoreId(Mage::app()->getStore()->getId())->load(); 
			foreach($collection as $item)
				$result[$item->getEntityId()] = $item->getName;
		
        return $result;
    }

    public static function toOptionArray()
    {
        $options = self::toShortOptionArray();
        $res = array();

        foreach($options as $k => $v)
            $res[] = array(
                'value' => $k,
                'label' => $v
            );

        return $res;
    }


}