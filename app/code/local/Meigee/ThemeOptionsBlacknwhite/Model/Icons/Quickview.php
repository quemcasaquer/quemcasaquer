<?php
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsBlacknwhite_Model_Icons_Quickview
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'fa-search', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('fa-search')),
            array('value'=>'fa-camera', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('fa-camera')),
            array('value'=>'fa-eye', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('fa-eye')),
            array('value'=>'fa-cog', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('fa-cog')),
            array('value'=>'fa-eye-slash', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('fa-eye-slash')),
            array('value'=>'fa-search-plus', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('fa-search-plus'))
        );
    }

}