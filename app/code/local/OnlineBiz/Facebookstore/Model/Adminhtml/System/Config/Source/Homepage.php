<?php
/**
 * Export Product data to xml data
 *
 * @category   OnlineBiz
 * @package    OnlineBiz_Facebookstore
 * @author     Nguyen Viet Tung (http://onlinebizsoft.com)
 */
class OnlineBiz_Facebookstore_Model_Adminhtml_System_Config_Source_Homepage
{

    const NONE = 'empty';


    const FEATURED = 'featured';
	
	
    const RANDOM = 'random';


    /*
     * Returns array of status options
     * @return array Options array like id => name
     */
    public static function toShortOptionArray()
    {
        $helper = Mage::helper('facebookstore');
        $result = array(
            self::NONE    => $helper->__('Promotion Text'),
			self::FEATURED    => $helper->__('Featured Product'),
			self::RANDOM    => $helper->__('Random Product')
        );
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