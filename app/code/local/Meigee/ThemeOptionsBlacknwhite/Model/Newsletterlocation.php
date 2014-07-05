<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsBlacknwhite_Model_Newsletterlocation
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'0', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('Popup')),
            array('value'=>'1', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('Sidebar'))
        );
    }

}