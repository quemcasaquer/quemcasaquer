<?php
/**
 * Export Product data to xml data
 *
 * @category   OnlineBiz
 * @package    OnlineBiz_Facebookstore
 * @author     Nguyen Viet Tung (http://onlinebizsoft.com)
 */
class OnlineBiz_Facebookstore_Model_Adminhtml_System_Config_Source_Mode
{


    const MODE_ORIGINAL = '1';
	
	
    const MODE_TIMELINE = '2';


    /*
     * Returns array of status options
     * @return array Options array like id => name
     */
    public static function toShortOptionArray()
    {
        $helper = Mage::helper('facebookstore');
        $result = array(
			self::MODE_ORIGINAL    => $helper->__('Based Style'),
            self::MODE_TIMELINE    => $helper->__('Timeline Style')
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