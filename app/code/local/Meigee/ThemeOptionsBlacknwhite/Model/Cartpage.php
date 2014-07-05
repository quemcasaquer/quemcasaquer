<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsBlacknwhite_Model_Cartpage
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'cart_standard', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('Standard')),
            array('value'=>'cart_accordion', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('Accordion'))          
        );
    }

}