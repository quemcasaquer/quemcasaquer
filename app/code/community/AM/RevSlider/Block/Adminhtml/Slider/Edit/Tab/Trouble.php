<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Slider_Edit_Tab_Trouble
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface{

    public function getTabLabel(){
        return Mage::helper('revslider')->__('Troubleshooting');
    }

    public function getTabTitle(){
        return Mage::helper('revslider')->__('Troubleshooting');
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
        $fieldset = $form->addFieldset('trouble_fieldset', array('legend' => Mage::helper('revslider')->__('Troubleshooting')));
        $fieldset->addField('using_jquery', 'select', array(
            'name'      => 'using_jquery',
            'label'     => Mage::helper('revslider')->__('Using Shipped Jquery'),
            'note'      => Mage::helper('revslider')->__('Select "No" if you already has a jQuery instance'),
            'options'   => $model->getYesNoOptions(),
            'value'     => $model->getData('using_jquery') ? $model->getData('using_jquery') : 'true'
        ));
        $this->setForm($form);
        if ($model->getId()) $form->setValues($model->getData());
        return parent::_prepareForm();
    }
}