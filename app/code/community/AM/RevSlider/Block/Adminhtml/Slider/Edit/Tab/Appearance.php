<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Slider_Edit_Tab_Appearance
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface{

    public function getTabLabel(){
        return Mage::helper('revslider')->__('Appearance');
    }

    public function getTabTitle(){
        return Mage::helper('revslider')->__('Appearance Settings');
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
        $fieldset = $form->addFieldset('appearance_fieldset', array('legend' => Mage::helper('revslider')->__('Appearance Settings')));

        $fieldset->addField('shadow_type', 'select', array(
            'name'      => 'shadow_type',
            'label'     => Mage::helper('revslider')->__('Shadow Type'),
            'title'     => Mage::helper('revslider')->__('Shadow Type'),
            'values'    => $model->getShadowOptions(),
            'value'     => $model->getData('shadow_type') ? $model->getData('shadow_type') : 2,
            'note'      => Mage::helper('revslider')->__('The Shadow display underneath the banner. The shadow apply to fixed and responsive modes only, the full width slider don\'t have a shadow')
        ));
        $fieldset->addField('show_timerbar', 'select', array(
            'name'      => 'show_timerbar',
            'label'     => Mage::helper('revslider')->__('Show Timer Line'),
            'title'     => Mage::helper('revslider')->__('Show Timer Line'),
            'values'    => $model->getShadowLineOptions(),
            'note'      => Mage::helper('revslider')->__('Show the top running timer line')
        ));
        $fieldset->addField('background_color', 'text', array(
            'name'      => 'background_color',
            'label'     => Mage::helper('revslider')->__('Background color'),
            'title'     => Mage::helper('revslider')->__('Background color'),
            'value'     => $model->getData('background_color') ? $model->getData('background_color') : '#E9E9E9',
            'note'      => Mage::helper('revslider')->__('Slider wrapper div background color, for transparent slider, leave empty'),
            'class'     => 'color {required:false}'
        ));
        $fieldset->addField('background_dotted_overlay', 'select', array(
            'name'      => 'background_dotted_overlay',
            'label'     => Mage::helper('revslider')->__('Dotted Overlay Size'),
            'title'     => Mage::helper('revslider')->__('Dotted Overlay Size'),
            'note'      => Mage::helper('revslider')->__('Show a dotted overlay on the whole slider, choose width of dots'),
            'values'    => $model->getBackgroundDottedOptions()
        ));
        $fieldset->addField('padding', 'text', array(
            'name'      => 'padding',
            'label'     => Mage::helper('revslider')->__('Padding (border)'),
            'title'     => Mage::helper('revslider')->__('Padding (border)'),
            'class'     => 'validate-number',
            'value'     => $model->getData('padding') ? $model->getData('padding') : 0,
            'note'      => Mage::helper('revslider')->__('The wrapper div padding, if it has value, then together with background color it it will make border around the slider')
        ));
        $bg = $fieldset->addField('show_background_image', 'select', array(
            'name'      => 'show_background_image',
            'label'     => Mage::helper('revslider')->__('Show Background Image'),
            'title'     => Mage::helper('revslider')->__('Show Background Image'),
            'values'    => $model->getYesNoOptions(),
            'note'      => Mage::helper('revslider')->__('Yes / No to put background image to the main slider wrapper')
        ));
        $bg1 = $fieldset->addField('background_image', 'text', array(
            'name'      => 'background_image',
            'label'     => Mage::helper('revslider')->__('Background Image Url'),
            'title'     => Mage::helper('revslider')->__('Background Image Url'),
            'note'      => Mage::helper('revslider')->__('The background image that will be on the slider wrapper. Will be shown at slider preloading')
        ));
        $bg2 = $fieldset->addField('bg_fit', 'select', array(
            'name'      => 'bg_fit',
            'label'     => Mage::helper('revslider')->__('Background Fit'),
            'title'     => Mage::helper('revslider')->__('Background Fit'),
            'values'    => $model->getBackgroundSizeOptions(),
            'note'      => Mage::helper('revslider')->__('How the background will be fitted into the Slider')
        ));
        $bg3 = $fieldset->addField('bg_repeat', 'select', array(
            'name'      => 'bg_repeat',
            'label'     => Mage::helper('revslider')->__('Background Repeat'),
            'title'     => Mage::helper('revslider')->__('Background Repeat'),
            'values'    => $model->getBackgroundRepeatOptions(),
            'note'      => Mage::helper('revslider')->__('How the background will be repeated into the Slider')
        ));
        $bg4 = $fieldset->addField('bg_position', 'select', array(
            'name'      => 'bg_position',
            'label'     => Mage::helper('revslider')->__('Background Position'),
            'title'     => Mage::helper('revslider')->__('Background Position'),
            'values'    => $model->getBackgroundPositionOptions(),
            'note'      => Mage::helper('revslider')->__('How the background will be positioned into the Slider')
        ));
        $form->getElement('background_image')->setRenderer(
            $this->getLayout()->createBlock('amext/adminhtml_widget_form_element_browser','',array(
                'element'   => $bg1
            ))
        );

        $this->setForm($form);
        if ($model->getId()) $form->setValues($model->getData());
        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap($bg->getHtmlId(), $bg->getName())
            ->addFieldMap($bg1->getHtmlId(), $bg1->getName())
            ->addFieldMap($bg2->getHtmlId(), $bg2->getName())
            ->addFieldMap($bg3->getHtmlId(), $bg3->getName())
            ->addFieldMap($bg4->getHtmlId(), $bg4->getName())
            ->addFieldDependence($bg1->getName(), $bg->getName(), 'true')
            ->addFieldDependence($bg2->getName(), $bg->getName(), 'true')
            ->addFieldDependence($bg3->getName(), $bg->getName(), 'true')
            ->addFieldDependence($bg4->getName(), $bg->getName(), 'true')
        );
        return parent::_prepareForm();
    }
}