<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsBlacknwhite_Model_Pagelayout
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'left', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('Left')),
            array('value'=>'right', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('Right')),
            array('value'=>'none', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('No Sidebar'))          
        );
    }

}