<?php
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsBlacknwhite_Model_Icons_Search
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'fa-search', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('fa-search')),
            array('value'=>'fa-arrow-circle-o-right', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('fa-arrow-circle-o-right')),
            array('value'=>'fa-caret-square-o-right', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('fa-caret-square-o-right')),
            array('value'=>'fa-search-plus', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('fa-search-plus')),
            array('value'=>'fa-hand-o-right', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('fa-hand-o-right')),
            array('value'=>'fa-filter', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('fa-filter'))
        );
    }

}