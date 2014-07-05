<?php

class Magestore_Affiliateplusprogram_Block_Adminhtml_Program_Edit_Tab_Actions extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $form->setHtmlIdPrefix('affiliateplusprogram_');
      
      $dataObj = new Varien_Object(array(
      	'program_id' => '',
      	'discount_in_store'	=> '',
      	'commission_in_store'	=> '',
      	'discount_type_in_store'	=> '',
      	'commission_type_in_store'	=> '',
	  ));
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
      if (isset($data)) $dataObj->addData($data);
      $data = $dataObj->getData();
      
      $this->setForm($form);
      $fieldset = $form->addFieldset('affiliateplusprogram_actions', array('legend'=>Mage::helper('affiliateplusprogram')->__('Actions')));
      
      $inStore = $this->getRequest()->getParam('store');
      $defaultLabel = Mage::helper('affiliateplusprogram')->__('Use Default');
      $defaultTitle = Mage::helper('affiliateplusprogram')->__('-- Please Select --');
      $scopeLabel = Mage::helper('affiliateplusprogram')->__('STORE VIEW');

      $fieldset->addField('affiliate_type', 'select', array(
          'label'     => Mage::helper('affiliateplusprogram')->__('Affiliate Type'),
          'name'      => 'affiliate_type',
          'values'    => Mage::getSingleton('affiliateplusprogram/system_config_source_type')->toOptionArray(),
          'disabled'  => ($inStore && !$data['affiliate_type_in_store']),
          'after_element_html' => $inStore ? '</td><td class="use-default">
			<input id="affiliate_type_default" name="affiliate_type_default" type="checkbox" value="1" class="checkbox config-inherit" '.($data['affiliate_type_in_store'] ? '' : 'checked="checked"').' onclick="toggleValueElements(this, Element.previous(this.parentNode))" />
			<label for="affiliate_type_default" class="inherit" title="'.$defaultTitle.'">'.$defaultLabel.'</label>
          </td><td class="scope-label">
			['.$scopeLabel.']
          ' : '</td><td class="scope-label">
			['.$scopeLabel.']',
	  ));

      $fieldset->addField('commission_type', 'select', array(
          'label'     => Mage::helper('affiliateplusprogram')->__('Commission Type'),
          'name'      => 'commission_type',
          'values'    => Mage::getSingleton('affiliateplus/system_config_source_fixedpercentage')->toOptionArray(),
          'disabled'  => ($inStore && !$data['commission_type_in_store']),
          'after_element_html' => $inStore ? '</td><td class="use-default">
			<input id="commission_type_default" name="commission_type_default" type="checkbox" value="1" class="checkbox config-inherit" '.($data['commission_type_in_store'] ? '' : 'checked="checked"').' onclick="toggleValueElements(this, Element.previous(this.parentNode))" />
			<label for="commission_type_default" class="inherit" title="'.$defaultTitle.'">'.$defaultLabel.'</label>
          </td><td class="scope-label">
			['.$scopeLabel.']
          ' : '</td><td class="scope-label">
			['.$scopeLabel.']',
	  ));
	  
      $fieldset->addField('commission', 'text', array(
          'label'     => Mage::helper('affiliateplusprogram')->__('Commission'),
          'required'  => true,
          'name'      => 'commission',
          'disabled'  => ($inStore && !$data['commission_in_store']),
          'after_element_html' => $inStore ? '</td><td class="use-default">
			<input id="commission_default" name="commission_default" type="checkbox" value="1" class="checkbox config-inherit" '.($data['commission_in_store'] ? '' : 'checked="checked"').' onclick="toggleValueElements(this, Element.previous(this.parentNode))" />
			<label for="commission_default" class="inherit" title="'.$defaultTitle.'">'.$defaultLabel.'</label>
          </td><td class="scope-label">
			['.$scopeLabel.']
          ' : '</td><td class="scope-label">
			['.$scopeLabel.']',
      ));
      
      $fieldset->addField('discount_type', 'select', array(
          'label'     => Mage::helper('affiliateplusprogram')->__('Discount Type'),
          'name'      => 'discount_type',
          'values'    => Mage::getSingleton('affiliateplus/system_config_source_fixedpercentage')->toOptionArray(),
          'disabled'  => ($inStore && !$data['discount_type_in_store']),
          'after_element_html' => $inStore ? '</td><td class="use-default">
			<input id="discount_type_default" name="discount_type_default" type="checkbox" value="1" class="checkbox config-inherit" '.($data['discount_type_in_store'] ? '' : 'checked="checked"').' onclick="toggleValueElements(this, Element.previous(this.parentNode))" />
			<label for="discount_type_default" class="inherit" title="'.$defaultTitle.'">'.$defaultLabel.'</label>
          </td><td class="scope-label">
			['.$scopeLabel.']
          ' : '</td><td class="scope-label">
			['.$scopeLabel.']',
	  ));
	  
      $fieldset->addField('discount', 'text', array(
          'label'     => Mage::helper('affiliateplusprogram')->__('Discount'),
          'required'  => true,
          'name'      => 'discount',
          'disabled'  => ($inStore && !$data['discount_in_store']),
          'after_element_html' => $inStore ? '</td><td class="use-default">
			<input id="discount_default" name="discount_default" type="checkbox" value="1" class="checkbox config-inherit" '.($data['discount_in_store'] ? '' : 'checked="checked"').' onclick="toggleValueElements(this, Element.previous(this.parentNode))" />
			<label for="discount_default" class="inherit" title="'.$defaultTitle.'">'.$defaultLabel.'</label>
          </td><td class="scope-label">
			['.$scopeLabel.']
          ' : '</td><td class="scope-label">
			['.$scopeLabel.']',
      ));
      
      $model->setData('actions',$model->getData('actions_serialized'));
      $renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
            ->setTemplate('promo/fieldset.phtml')
            ->setNewChildUrl($this->getUrl('adminhtml/promo_quote/newActionHtml/form/affiliateplusprogram_actions_fieldset'));
      
	  Mage::dispatchEvent('affiliateplusprogram_adminhtml_edit_actions',array(
	  	'form'	=> $form,
	  	'form_data'	=> $data,
	  ));
      
      $fieldset = $form->addFieldset('actions_fieldset', array('legend'=>Mage::helper('affiliateplusprogram')->__('Use the program only to cart items matching the following conditions (leave blank for all items)')))->setRenderer($renderer);
      
      $fieldset->addField('actions','text',array(
      	'name'	=> 'actions',
      	'label'	=> Mage::helper('affiliateplusprogram')->__('Apply To'),
      	'title'	=> Mage::helper('affiliateplusprogram')->__('Apply To'),
      	'required'	=> true,
	  ))->setRule($model)->setRenderer(Mage::getBlockSingleton('rule/actions'));
      
      $form->setValues($data);
      
      return parent::_prepareForm();
  }
}