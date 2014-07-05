<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Slider_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface{

    public function _prepareForm(){
        /* @var $model AM_RevSlider_Model_Slider */
        $model = Mage::registry('revslider');
        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('info_fieldset', array('legend' => Mage::helper('revslider')->__('Revolution Slider Information')));

        if ($model->getId()){
            $fieldset->addField('slider_id', 'hidden', array(
                'name'  => 'slider_id'
            ));
        }
        $fieldset->addField('title', 'text', array(
            'name'      => 'title',
            'label'     => Mage::helper('revslider')->__('Title'),
            'title'     => Mage::helper('revslider')->__('Title'),
            'required'  => true,
            'note'      => Mage::helper('revslider')->__('The title of the slider. Example: Slider1')
        ));
        $layout = $fieldset->addField('layout', 'select', array(
            'name'      => 'layout',
            'label'     => Mage::helper('revslider')->__('Layout'),
            'title'     => Mage::helper('revslider')->__('Layout'),
            'values'    => $model->getLayouts()
        ));
        $layout4 = $fieldset->addField('fullscreen_offset_container', 'text', array(
            'name'      => 'fullscreen_offset_container',
            'label'     => Mage::helper('revslider')->__('Fullscreen Offset Container'),
            'title'     => Mage::helper('revslider')->__('Fullscreen Offset Container'),
            'note'      => Mage::helper('revslider')->__('Example: #header -> The height of fullscreen slider will be decreased with the height of the #header to fit perfect in the screen')
        ));
        $layout42 = $fieldset->addField('fullscreen_min_height', 'text', array(
            'name'      => 'fullscreen_min_height',
            'label'     => Mage::helper('revslider')->__('Min. Fullscreen Height'),
            'title'     => Mage::helper('revslider')->__('Min. Fullscreen Height'),
            'class'     => 'validate-number'
        ));
        $layout21 = $fieldset->addField('responsitive_w1', 'text', array(
            'name'      => 'responsitive_w1',
            'label'     => Mage::helper('revslider')->__('Screen Width 1'),
            'title'     => Mage::helper('revslider')->__('Screen Width 1'),
            'class'     => 'validate-number',
            'value'     => $model->getData('responsitive_w1') ? $model->getData('responsitive_w1') : 940
        ));
        $layout22 = $fieldset->addField('responsitive_sw1', 'text', array(
            'name'      => 'responsitive_sw1',
            'label'     => Mage::helper('revslider')->__('Slider Width 1'),
            'title'     => Mage::helper('revslider')->__('Slider Width 1'),
            'class'     => 'validate-number',
            'value'     => $model->getData('responsitive_sw1') ? $model->getData('responsitive_sw1') : 770
        ));
        $layout23 = $fieldset->addField('responsitive_w2', 'text', array(
            'name'      => 'responsitive_w2',
            'label'     => Mage::helper('revslider')->__('Screen Width 2'),
            'title'     => Mage::helper('revslider')->__('Screen Width 2'),
            'class'     => 'validate-number',
            'value'     => $model->getData('responsitive_w2') ? $model->getData('responsitive_w2') : 780
        ));
        $layout24 = $fieldset->addField('responsitive_sw2', 'text', array(
            'name'      => 'responsitive_sw2',
            'label'     => Mage::helper('revslider')->__('Slider Width 2'),
            'title'     => Mage::helper('revslider')->__('Slider Width 2'),
            'class'     => 'validate-number',
            'value'     => $model->getData('responsitive_sw2') ? $model->getData('responsitive_sw2') : 500
        ));
        $layout25 = $fieldset->addField('responsitive_w3', 'text', array(
            'name'      => 'responsitive_w3',
            'label'     => Mage::helper('revslider')->__('Screen Width 3'),
            'title'     => Mage::helper('revslider')->__('Screen Width 3'),
            'class'     => 'validate-number',
            'value'     => $model->getData('responsitive_w3') ? $model->getData('responsitive_w3') : 510
        ));
        $layout26 = $fieldset->addField('responsitive_sw3', 'text', array(
            'name'      => 'responsitive_sw3',
            'label'     => Mage::helper('revslider')->__('Slider Width 3'),
            'title'     => Mage::helper('revslider')->__('Slider Width 3'),
            'class'     => 'validate-number',
            'value'     => $model->getData('responsitive_sw3') ? $model->getData('responsitive_sw3') : 310
        ));
        $layout31 = $fieldset->addField('auto_height', 'select', array(
            'name'      => 'auto_height',
            'label'     => Mage::helper('revslider')->__('Unlimited Height'),
            'title'     => Mage::helper('revslider')->__('Unlimited Height'),
            'options'   => $model->getOnOffOptions()
        ));
        $layout32 = $fieldset->addField('force_full_width', 'select', array(
            'name'      => 'force_full_width',
            'label'     => Mage::helper('revslider')->__('Force Full Width'),
            'title'     => Mage::helper('revslider')->__('Force Full Width'),
            'options'   => $model->getOnOffOptions()
        ));
        $layout41 = $fieldset->addField('full_screen_align_force', 'select', array(
            'name'      => 'full_screen_align_force',
            'label'     => Mage::helper('revslider')->__('FullScreen Align'),
            'title'     => Mage::helper('revslider')->__('FullScreen Align'),
            'options'   => $model->getOnOffOptions()
        ));
        $fieldset->addField('width', 'text', array(
            'name'      => 'width',
            'label'     => Mage::helper('revslider')->__('Grid Width'),
            'title'     => Mage::helper('revslider')->__('Grid Width'),
            'class'     => 'validate-number',
            'value'     => $model->getData('width') ? $model->getData('width') : 960
        ));
        $fieldset->addField('height', 'text', array(
            'name'      => 'height',
            'label'     => Mage::helper('revslider')->__('Grid Height'),
            'title'     => Mage::helper('revslider')->__('Grid Height'),
            'class'     => 'validate-number',
            'value'     => $model->getData('height') ? $model->getData('height') : 350
        ));
        $fieldset->addField('status', 'select', array(
            'name'      => 'status',
            'label'     => Mage::helper('revslider')->__('Status'),
            'title'     => Mage::helper('revslider')->__('Status'),
            'options'   => $model->getStatuses(),
            'value'     => $model->getData('status') ? $model->getData('status') : 1
        ));
        $fieldset->addField('date_from', 'date', array(
            'name'      => 'date_from',
            'label'     => Mage::helper('revslider')->__('Visible From'),
            'title'     => Mage::helper('revslider')->__('Visible From'),
            'note'      => Mage::helper('revslider')->__('If set, slider will be visible after the date is reached'),
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'format'    => Mage::app()->getLocale()->getDateFormat(
                Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
            )
        ));
        $fieldset->addField('date_to', 'date', array(
            'name'      => 'date_to',
            'label'     => Mage::helper('revslider')->__('Visible Until'),
            'title'     => Mage::helper('revslider')->__('Visible Until'),
            'note'      => Mage::helper('revslider')->__('If set, slider will be visible till the date is reached'),
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'format'    => Mage::app()->getLocale()->getDateFormat(
                Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
            )
        ));

        $this->setForm($form);
        if ($model->getId()) $form->setValues($model->getData());
        if (version_compare(Mage::getVersion(), '1.7.0.0') < 0){
            $dependenceElement = $this->getLayout()->createBlock('amext/adminhtml_widget_form_element_dependence');
        }else{
            $dependenceElement = $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence');
        }
        $this->setChild('form_after', $dependenceElement
            ->addFieldMap($layout->getHtmlId(), $layout->getName())
            ->addFieldMap($layout4->getHtmlId(), $layout4->getName())
            ->addFieldMap($layout21->getHtmlId(), $layout21->getName())
            ->addFieldMap($layout22->getHtmlId(), $layout22->getName())
            ->addFieldMap($layout23->getHtmlId(), $layout23->getName())
            ->addFieldMap($layout24->getHtmlId(), $layout24->getName())
            ->addFieldMap($layout25->getHtmlId(), $layout25->getName())
            ->addFieldMap($layout26->getHtmlId(), $layout26->getName())
            ->addFieldMap($layout31->getHtmlId(), $layout31->getName())
            ->addFieldMap($layout32->getHtmlId(), $layout32->getName())
            ->addFieldMap($layout41->getHtmlId(), $layout41->getName())
            ->addFieldMap($layout42->getHtmlId(), $layout42->getName())
            ->addFieldDependence($layout4->getName(), $layout->getName(), 'fullscreen')
            ->addFieldDependence($layout41->getName(), $layout->getName(), 'fullscreen')
            ->addFieldDependence($layout42->getName(), $layout->getName(), 'fullscreen')
            ->addFieldDependence($layout32->getName(), $layout->getName(), array('fullscreen', 'fullwidth'))
            ->addFieldDependence($layout21->getName(), $layout->getName(), 'responsitive')
            ->addFieldDependence($layout22->getName(), $layout->getName(), 'responsitive')
            ->addFieldDependence($layout23->getName(), $layout->getName(), 'responsitive')
            ->addFieldDependence($layout24->getName(), $layout->getName(), 'responsitive')
            ->addFieldDependence($layout25->getName(), $layout->getName(), 'responsitive')
            ->addFieldDependence($layout26->getName(), $layout->getName(), 'responsitive')
            ->addFieldDependence($layout31->getName(), $layout->getName(), 'fullwidth')
        );
        return parent::_prepareForm();
    }

    public function getTabLabel(){
        return Mage::helper('revslider')->__('Information');
    }

    public function getTabTitle(){
        return Mage::helper('revslider')->__('Slider Information');
    }

    public function canShowTab(){
        return true;
    }

    public function isHidden(){
        return false;
    }
}