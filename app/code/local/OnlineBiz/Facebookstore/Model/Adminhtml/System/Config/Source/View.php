<?php
/**
 * Export Product data to xml data
 *
 * @category   OnlineBiz
 * @package    OnlineBiz_Facebookstore
 * @author     Nguyen Viet Tung (http://onlinebizsoft.com)
 */
class OnlineBiz_Facebookstore_Model_Adminhtml_System_Config_Source_View
{


    const LIST_VIEW = 'list';
	
	
    const GRID_VIEW = 'grid';


    /*
     * Returns array of status options
     * @return array Options array like id => name
     */
    public static function toShortOptionArray()
    {
        $helper = Mage::helper('facebookstore');
        $result = array(
			self::GRID_VIEW    => $helper->__('Grid'),
            self::LIST_VIEW    => $helper->__('List')
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