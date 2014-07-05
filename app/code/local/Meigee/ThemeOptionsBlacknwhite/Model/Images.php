<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsBlacknwhite_Model_Images
{
    public function toOptionArray()
    {
        return array(
            array('value'=>1.11, 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('1 X 1.11 (Demo Value)')),
            array('value'=>1, 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('1 X 1')),
            array('value'=>1.25, 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('1 X 1.25')),
            array('value'=>1.75, 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('1 X 1.5')),
            array('value'=>2, 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('1 X 2'))         
        );
    }

}