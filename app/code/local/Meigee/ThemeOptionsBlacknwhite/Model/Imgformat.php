<?php
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsBlacknwhite_Model_Imgformat
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'.png', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('.png')),
            array('value'=>'.jpg', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('.jpg')),
            array('value'=>'.gif', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('.gif'))          
        );
    }

}