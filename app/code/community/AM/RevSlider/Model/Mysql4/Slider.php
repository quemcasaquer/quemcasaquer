<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Model_Mysql4_Slider extends Mage_Core_Model_Mysql4_Abstract{
    public function _construct(){
        $this->_init('revslider/slider', 'id');
    }
}