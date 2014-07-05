<?php

class Magestore_Affiliateplus_Block_Adminhtml_Banner_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$data = array();
		if(Mage::getSingleton('adminhtml/session')->getBannerData()){
			$data = Mage::getSingleton('adminhtml/session')->getBannerData();
			Mage::getSingleton('adminhtml/session')->setBannerData(null);
		} elseif ( Mage::registry('banner_data')) {
			$data =  Mage::registry('banner_data');
		}
		$obj = new Varien_Object($data);
		  
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('banner_form', array('legend'=>Mage::helper('affiliateplus')->__('Banner information')/* , 'class' => 'fieldset-wide' */));
	
		
		$inStore = $this->getRequest()->getParam('store');
      	$defaultLabel = Mage::helper('affiliateplus')->__('Use Default');
      	$defaultTitle = Mage::helper('affiliateplus')->__('-- Please Select --');
      	$scopeLabel = Mage::helper('affiliateplus')->__('STORE VIEW');
		

		if(!$inStore)
	  	  $disabledTitle = false;
	  	else
	  	  $disabledTitle = !$obj->getData('title_in_store');

		$isDefaultTitleCheck = $disabledTitle ? 'checked="checked"': '';
		
		$fieldset->addField('title', 'text', array(
			'label'     => Mage::helper('affiliateplus')->__('Title'),
			'class'     => 'required-entry',
			'required'  => true,
			'name'      => 'title',
			'disabled'  => $disabledTitle,
          	'after_element_html'	=> $inStore ? '</td><td class="use-default">
			<input id="title_default" name="title_default" type="checkbox" value="1" class="checkbox config-inherit" '.$isDefaultTitleCheck.' onclick="toggleValueElements(this, Element.previous(this.parentNode))" />
			<label for="title_default" class="inherit" title="'.$defaultTitle.'">'.$defaultLabel.'</label>
          </td><td class="scope-label">
			['.$scopeLabel.']
          ' : '</td><td class="scope-label">
			['.$scopeLabel.']',
		));
	
		Mage::dispatchEvent('affiliateplus_adminhtml_add_field_banner_form', array('fieldset' => $fieldset, 'form' => $form));
	
		$fieldset->addField('type_id', 'select', array(
			'label'     => Mage::helper('affiliateplus')->__('Banner Type'),
			'name'      => 'type_id',
			'required'  => true,
			'onchange'	=> 'showFileField()',
			'values'    => array(
				array('value'     => 1, 'label'     => Mage::helper('affiliateplus')->__('Image')),
				array('value'     => 2, 'label'     => Mage::helper('affiliateplus')->__('Flash')),
				array('value'     => 3, 'label'     => Mage::helper('affiliateplus')->__('Text'))
			),
		));
		
		if($obj->getData('source_file')) // if exit file, required is false
			$isRequired = false;
		else
			$isRequired = true;

		$fieldset->addField('source_file', 'file', array(
          'label'     => Mage::helper('affiliateplus')->__('Source File'),
          'name'      => 'source_file',
		  'required'  => $isRequired,
		));
		
		if($obj->getData('banner_id') && $obj->getData('type_id') != 3){ //show view link if banner is image or flash
			$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'affiliateplus/banner/';
			$fieldset->addField('banner_view', 'note', array(
				'label'     => Mage::helper('affiliateplus')->__('Banner View'),
				'text'      => $this->getLayout()->createBlock('affiliateplus/adminhtml_banner_view')
											->setBannerType($obj->getData('type_id'))
											->setFile($url . $obj->getData('source_file'))
											->setWidth($obj->getData('width'))
											->setHeight($obj->getData('height'))										
											->toHtml(),
			));		
	  	}
		
		$fieldset->addField('width', 'text', array(
          'label'     => Mage::helper('affiliateplus')->__('Width (px)'),
          'required'  => true,
          'name'      => 'width',
		));	  
		
		$fieldset->addField('height', 'text', array(
          'label'     => Mage::helper('affiliateplus')->__('Height (px)'),
          'required'  => true,
          'name'      => 'height',
		));			
	  
		$fieldset->addField('link', 'text', array(
          'label'     => Mage::helper('affiliateplus')->__('Link'),
          'name'      => 'link',
		));
	
		
		if(!$inStore)
	  	  $disabledStatus = false;
	  	else
	  	  $disabledStatus = !$obj->getData('status_in_store');
		  
		$isDefaultStatusCheck = $disabledStatus ? 'checked="checked"': '';
		
		$fieldset->addField('status', 'select', array(
			'label'     => Mage::helper('affiliateplus')->__('Status'),
			'name'      => 'status',
			'values'    => array(
				array(
					'value'     => 1,
					'label'     => Mage::helper('affiliateplus')->__('Enabled'),
				),
		
				array(
					'value'     => 2,
					'label'     => Mage::helper('affiliateplus')->__('Disabled'),
				),
			),
			
			'disabled'  => $disabledStatus,
          	'after_element_html'	=> $inStore ? '</td><td class="use-default">
			<input id="status_default" name="status_default" type="checkbox" value="1" class="checkbox config-inherit" '.$isDefaultStatusCheck.' onclick="toggleValueElements(this, Element.previous(this.parentNode))" />
			<label for="status_default" class="inherit" title="'.$defaultTitle.'">'.$defaultLabel.'</label>
          </td><td class="scope-label">
			['.$scopeLabel.']
          ' : '</td><td class="scope-label">
			['.$scopeLabel.']',
		));
	
		if ( Mage::getSingleton('adminhtml/session')->getBannerData() )
		{
			$form->setValues(Mage::getSingleton('adminhtml/session')->getBannerData());
			Mage::getSingleton('adminhtml/session')->setBannerData(null);
		} elseif ( Mage::registry('banner_data') ) {
			$form->setValues(Mage::registry('banner_data')->getData());
		}
		return parent::_prepareForm();
	}
}