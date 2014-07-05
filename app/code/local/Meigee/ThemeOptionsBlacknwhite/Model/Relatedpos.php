<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsBlacknwhite_Model_Relatedpos
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'sidebar', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('Sidebar')),
			array('value'=>'bottom', 'label'=>Mage::helper('ThemeOptionsBlacknwhite')->__('Bottom (Under collateral block)'))
        );
    }

}