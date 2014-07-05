<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Slider_Edit_Tab_Thumb
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface{

    public function getTabLabel(){
        return Mage::helper('revslider')->__('Thumbnails');
    }

    public function getTabTitle(){
        return Mage::helper('revslider')->__('Thumbnails Settings');
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
        $fieldset = $form->addFieldset('general_fieldset', array('legend' => Mage::helper('revslider')->__('Thumbnails Settings')));

        $fieldset->addField('thumb_width', 'text', array(
            'name'      => 'thumb_width',
            'class'     => 'validate-number',
            'label'     => Mage::helper('revslider')->__('Thumb Width'),
            'title'     => Mage::helper('revslider')->__('Thumb Width'),
            'value'     => $model->getData('thumb_width') ? $model->getData('thumb_width') : 100,
            'note'      => Mage::helper('revslider')->__('The basic Width of one Thumbnail (only if thumb is selected)')
        ));
        $fieldset->addField('thumb_height', 'text', array(
            'name'      => 'thumb_height',
            'class'     => 'validate-number',
            'label'     => Mage::helper('revslider')->__('Thumb Height'),
            'title'     => Mage::helper('revslider')->__('Thumb Height'),
            'value'     => $model->getData('thumb_height') ? $model->getData('thumb_height') : 50,
            'note'      => Mage::helper('revslider')->__('The basic Height of one Thumbnail (only if thumb is selected)')
        ));
        $fieldset->addField('thumb_amount', 'text', array(
            'name'      => 'thumb_amount',
            'class'     => 'validate-number',
            'label'     => Mage::helper('revslider')->__('Thumb Amount'),
            'title'     => Mage::helper('revslider')->__('Thumb Amount'),
            'value'     => $model->getData('thumb_amount') ? $model->getData('thumb_amount') : 1,
            'note'      => Mage::helper('revslider')->__('The amount of the Thumbs visible same time (only if thumb is selected)')
        ));

        $this->setForm($form);
        if ($model->getId()) $form->setValues($model->getData());
        return parent::_prepareForm();
    }
}