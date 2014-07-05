<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Slider_Import extends Mage_Adminhtml_Block_Widget_Form_Container{
    public function __construct(){
        $this->_blockGroup      = 'revslider';
        $this->_controller      = 'adminhtml_slider';
        $this->_mode            = 'import';
        parent::__construct();
        $this->_updateButton('save', 'label', Mage::helper('revslider')->__('Import'));
    }

    public function getHeaderText(){
        return Mage::helper('revslider')->__('Import Slider');
    }
}