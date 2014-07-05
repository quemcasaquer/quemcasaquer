<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Slider_Edit_Tab_Font
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface{

    public function getTabLabel(){
        return Mage::helper('revslider')->__('Font Settings');
    }

    public function getTabTitle(){
        return Mage::helper('revslider')->__('Font Settings');
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
        $fieldset = $form->addFieldset('font_fieldset', array('legend' => Mage::helper('revslider')->__('Font Settings')));

        $google = $fieldset->addField('load_googlefont', 'select', array(
            'name'      => 'load_googlefont',
            'label'     => Mage::helper('revslider')->__('Load Google Font'),
            'title'     => Mage::helper('revslider')->__('Load Google Font'),
            'values'    => $model->getYesNoOptions(),
            'note'      => Mage::helper('revslider')->__('Yes / No to load google font')
        ));
        $google1 = $fieldset->addField('google_font', 'text', array(
            'name'      => 'google_font',
            'label'     => Mage::helper('revslider')->__('Google Font'),
            'note'      => Mage::helper('revslider')->__('Ex: Open+Sans:400,300,700&subset=latin,vietnamese. To add more google fonts please read <a href="%s" target="_blank">this tutorial</a>', 'http://codecanyon.net/item/slider-revolution-responsive-wordpress-plugin/2751380/faqs/15268'),
        ));
        $form->getElement('google_font')->setRenderer(
            $this->getLayout()->createBlock('revslider/adminhtml_widget_form_font')
        );

        $this->setForm($form);
        if ($model->getId()) $form->setValues($model->getData());
        if (version_compare(Mage::getVersion(), '1.7.0.0') < 0){
            $dependenceElement = $this->getLayout()->createBlock('amext/adminhtml_widget_form_element_dependence');
        }else{
            $dependenceElement = $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence');
        }
        $this->setChild('form_after', $dependenceElement
            ->addFieldMap($google->getHtmlId(), $google->getName())
            ->addFieldMap($google1->getHtmlId(), $google1->getName())
            ->addFieldDependence($google1->getName(), $google->getName(), 'true')
        );
        return parent::_prepareForm();
    }
}