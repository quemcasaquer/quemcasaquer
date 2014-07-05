<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Slide_Animation extends Mage_Adminhtml_Block_Widget_Form_Container{
    public function __construct(){
        $this->_blockGroup      = 'revslider';
        $this->_controller      = 'adminhtml_slide';
        $this->_mode            = 'animation';
        parent::__construct();
        $this->setId('editAnimationForm');
        $this->_headerText      = Mage::helper('revslider')->__('Custom Animation Form');
        $this->removeButton('back');
        $this->removeButton('reset');
        $this->_updateButton('save', 'label', Mage::helper('revslider')->__('Save'));
        $windowId = $this->getRequest()->getParam('windowId');
        $type = $this->getRequest()->getParam('type');
        $aid = $this->getRequest()->getParam('aid');
        $this->_updateButton('save', 'onclick', "revLayer.addCustomAnimation('{$windowId}', '{$type}')");
        $this->_addButton('delete', array(
            'label' => Mage::helper('revslider')->__('Delete'),
            'class' => 'delete',
            'onclick' => "revLayer.removeCustomAnimation('{$windowId}', '{$aid}')"
        ));
    }
}