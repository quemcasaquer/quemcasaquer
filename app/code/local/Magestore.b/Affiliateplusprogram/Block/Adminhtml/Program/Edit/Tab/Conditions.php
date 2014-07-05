<?php

class Magestore_Affiliateplusprogram_Block_Adminhtml_Program_Edit_Tab_Conditions extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      if (Mage::getSingleton('adminhtml/session')->getAffiliateplusprogramData()){
          $data = Mage::getSingleton('adminhtml/session')->getAffiliateplusprogramData();
          $programId = isset($data['program_id']) ? $data['program_id'] : 0;
          $model = Mage::getModel('affiliateplusprogram/program')
          		->load($programId)
		  		->setData($data);
          Mage::getSingleton('adminhtml/session')->setAffiliateplusprogramData(null);
      } elseif (Mage::registry('affiliateplusprogram_data')){
          $model = Mage::registry('affiliateplusprogram_data');
          $data = $model->getData();
      }
	  $model->setData('conditions',$model->getData('conditions_serialized'));
      
	  $form = new Varien_Data_Form();
      $form->setHtmlIdPrefix('affiliateplusprogram_');
      
      $renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
            ->setTemplate('promo/fieldset.phtml')
            ->setNewChildUrl($this->getUrl('adminhtml/promo_quote/newConditionHtml/form/affiliateplusprogram_conditions_fieldset'));
      
      $fieldset = $form->addFieldset('conditions_fieldset', array('legend'=>Mage::helper('affiliateplusprogram')->__('Use the program only if the following conditions are met (leave blank for all products)')))->setRenderer($renderer);
      
      $fieldset->addField('conditions','text',array(
      	'name'	=> 'conditions',
      	'label'	=> Mage::helper('affiliateplusprogram')->__('Conditions'),
      	'title'	=> Mage::helper('affiliateplusprogram')->__('Conditions'),
      	'required'	=> true,
	  ))->setRule($model)->setRenderer(Mage::getBlockSingleton('rule/conditions'));
      
      $form->setValues($data);
      $this->setForm($form);
      return parent::_prepareForm();
  }
}