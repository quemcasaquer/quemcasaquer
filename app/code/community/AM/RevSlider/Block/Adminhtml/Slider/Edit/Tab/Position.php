<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Slider_Edit_Tab_Position
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface{

    public function getTabLabel(){
        return Mage::helper('revslider')->__('Position');
    }

    public function getTabTitle(){
        return Mage::helper('revslider')->__('Position Settings');
    }

    public function canShowTab(){
        return true;
    }

    public function isHidden(){
        return false;
    }

    public function _prepareForm(){
        /* @var $model AM_RevSlider_Model_Slider */
        $model = Mage::registry('revslider');
        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('position_fieldset', array('legend' => Mage::helper('revslider')->__('Position Settings')));

        $fieldset->addField('position', 'select', array(
            'name'      => 'position',
            'label'     => Mage::helper('revslider')->__('Position on the page'),
            'title'     => Mage::helper('revslider')->__('Position on the page'),
            'values'    => $model->getLCROptions(),
            'value'     => $model->getData('position') ? $model->getData('position') : 'center',
            'note'      => Mage::helper('revslider')->__('The position of the slider on the page (float:left | float:right | margin:0px auto)')
        ));
        $fieldset->addField('margin_top', 'text', array(
            'name'      => 'margin_top',
            'label'     => Mage::helper('revslider')->__('Margin Top'),
            'title'     => Mage::helper('revslider')->__('Margin Top'),
            'class'     => 'validate-number',
            'note'      => Mage::helper('revslider')->__('The top margin of the slider wrapper div'),
            'value'     => $model->getData('margin_top') ? $model->getData('margin_top') : 0
        ));
        $fieldset->addField('margin_bottom', 'text', array(
            'name'      => 'margin_bottom',
            'label'     => Mage::helper('revslider')->__('Margin Bottom'),
            'title'     => Mage::helper('revslider')->__('Margin Bottom'),
            'class'     => 'validate-number',
            'note'      => Mage::helper('revslider')->__('The bottom margin of the slider wrapper div'),
            'value'     => $model->getData('margin_bottom') ? $model->getData('margin_bottom') : 0
        ));
        $fieldset->addField('margin_left', 'text', array(
            'name'      => 'margin_left',
            'label'     => Mage::helper('revslider')->__('Margin Left'),
            'title'     => Mage::helper('revslider')->__('Margin Left'),
            'class'     => 'validate-number',
            'note'      => Mage::helper('revslider')->__('The left margin of the slider wrapper div'),
            'value'     => $model->getData('margin_left') ? $model->getData('margin_left') : 0
        ));
        $fieldset->addField('margin_right', 'text', array(
            'name'      => 'margin_right',
            'label'     => Mage::helper('revslider')->__('Margin Right'),
            'title'     => Mage::helper('revslider')->__('Margin Right'),
            'class'     => 'validate-number',
            'note'      => Mage::helper('revslider')->__('The right margin of the slider wrapper div'),
            'value'     => $model->getData('margin_right') ? $model->getData('margin_right') : 0
        ));

        $this->setForm($form);
        if ($model->getId()) $form->setValues($model->getData());
        return parent::_prepareForm();
    }
}