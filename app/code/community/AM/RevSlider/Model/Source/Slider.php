<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Model_Source_Slider{
    public function toOptionArray(){
        $collection = Mage::getModel('revslider/slider')
            ->getCollection();
        $array = array();
        foreach ($collection as $slide){
            $array[] = array(
                'value' => $slide->getId(),
                'label' => $slide->getTitle()
            );
        }
        return $array;
    }
}