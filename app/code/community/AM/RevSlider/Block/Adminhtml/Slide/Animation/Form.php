<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Slide_Animation_Form extends Mage_Adminhtml_Block_Widget_Form {
    protected function _prepareForm(){
        $model = Mage::registry('animation');
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'method'    => 'post'
        ));

        if ($model->getId()){
            $model->setData('anim_id', $model->getId());
            $form->addField('anim_id', 'hidden', array(
                'name' => 'id'
            ));
        }

        $fieldset = $form->addFieldset('animation_fieldset', array(
            'legend'    => $this->helper('revslider')->__('Animation Settings')
        ));

        $fieldset->addField('anim_preview', 'text', array(
            'note'      => Mage::helper('revslider')->__('Preview Transition (Speed and Easing only for preview)')
        ));
        $form->getElement('anim_preview')->setRenderer(
            $this->getLayout()->createBlock('revslider/adminhtml_widget_form_animation_preview')
        );
        $fieldset->addField('name', 'text', array(
            'name'      => 'anim-name',
            'label'     => Mage::helper('revslider')->__('Animation Name'),
            'title'     => Mage::helper('revslider')->__('Animation Name'),
            'class'     => 'required-entry'
        ));
        $fieldset->addField('anim_speed', 'text', array(
            'label'     => Mage::helper('revslider')->__('Speed'),
            'title'     => Mage::helper('revslider')->__('(ms)'),
            'value'     => 500
        ));
        $fieldset->addField('anim_easing', 'select', array(
            'label'     => Mage::helper('revslider')->__('Easing'),
            'title'     => Mage::helper('revslider')->__('Easing'),
            'values'    => Mage::getModel('revslider/slide')->getLayerEaseOptions()
        ));
        $fieldset->addField('anim_settings', 'text', array());
        $form->getElement('anim_settings')->setRenderer(
            $this->getLayout()->createBlock('revslider/adminhtml_widget_form_animation_settings')
        );

        $form->setUseContainer(true);
        if ($model->getId()) $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}