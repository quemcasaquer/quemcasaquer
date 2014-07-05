<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsBlacknwhite_Model_Currencyswitcher
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'currency_select', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('Select Box')),
            array('value'=>'currency_images', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('Flags'))          
        );
    }

}