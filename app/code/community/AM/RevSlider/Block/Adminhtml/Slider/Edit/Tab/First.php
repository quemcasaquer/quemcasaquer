<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Slider_Edit_Tab_First
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface{

    public function getTabLabel(){
        return Mage::helper('revslider')->__('First Slide');
    }

    public function getTabTitle(){
        return Mage::helper('revslider')->__('Alternative First Slide');
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
        $fieldset = $form->addFieldset('general_fieldset', array('legend' => Mage::helper('revslider')->__('Alternative First Slide')));

        $fieldset->addField('start_with_slide', 'text', array(
            'name'      => 'start_with_slide',
            'class'     => 'validate-number',
            'label'     => Mage::helper('revslider')->__('Start With Slide'),
            'title'     => Mage::helper('revslider')->__('Start With Slide'),
            'value'     => $model->getData('start_with_slide') ? $model->getData('start_with_slide') : 1,
            'note'      => Mage::helper('revslider')->__('Change it if you want to start from a different slide then 1')
        ));
        $show = $fieldset->addField('first_transition_active', 'select', array(
            'name'      => 'first_transition_active',
            'label'     => Mage::helper('revslider')->__('First Transition Active'),
            'title'     => Mage::helper('revslider')->__('First Transition Active'),
            'values'    => $model->getOnOffOptions(),
            'note'      => Mage::helper('revslider')->__('If active, it will overwrite the first slide transition. Use it when you want a special transition for the first slide only')
        ));
        $show1 = $fieldset->addField('first_transition_type', 'select', array(
            'name'      => 'first_transition_type',
            'label'     => Mage::helper('revslider')->__('First Transition Type'),
            'title'     => Mage::helper('revslider')->__('First Transition Type'),
            'values'    => $model->getTransistionOptions(),
            'value'     => $model->getData('first_transition_type') ? $model->getData('first_transition_type') : 'fade',
            'note'      => Mage::helper('revslider')->__('First slide transition type')
        ));
        $show2 = $fieldset->addField('first_transition_duration', 'text', array(
            'name'      => 'first_transition_duration',
            'class'     => 'validate-number',
            'label'     => Mage::helper('revslider')->__('First Transition Duration'),
            'title'     => Mage::helper('revslider')->__('First Transition Duration'),
            'value'     => $model->getData('first_transition_duration') ? $model->getData('first_transition_duration') : 300,
            'note'      => Mage::helper('revslider')->__('First slide transition duration (Default:300, min: 100 max 2000)')
        ));
        $show3 = $fieldset->addField('first_transition_slot_amount', 'text', array(
            'name'      => 'first_transition_slot_amount',
            'class'     => 'validate-number',
            'label'     => Mage::helper('revslider')->__('First Transition Slot Amount'),
            'title'     => Mage::helper('revslider')->__('First Transition Slot Amount'),
            'value'     => $model->getData('first_transition_slot_amount') ? $model->getData('first_transition_slot_amount') : 7,
            'note'      => Mage::helper('revslider')->__('The number of slots or boxes the slide is divided into. If you use boxfade, over 7 slots can be juggy')
        ));

        $this->setForm($form);
        if ($model->getId()) $form->setValues($model->getData());
        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap($show->getHtmlId(), $show->getName())
            ->addFieldMap($show1->getHtmlId(), $show1->getName())
            ->addFieldMap($show2->getHtmlId(), $show2->getName())
            ->addFieldMap($show3->getHtmlId(), $show3->getName())
            ->addFieldDependence($show1->getName(), $show->getName(), 'on')
            ->addFieldDependence($show2->getName(), $show->getName(), 'on')
            ->addFieldDependence($show3->getName(), $show->getName(), 'on')
        );
        return parent::_prepareForm();
    }
}