<?php
class Magestore_Affiliateplus_Block_Adminhtml_Payment_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	/**
	 * get Affiliate Payment Helper
	 *
	 * @return Magestore_Affiliateplus_Helper_Payment
	 */
	protected function _getPaymentHelper(){
		return Mage::helper('affiliateplus/payment');
	}
	
	protected function _prepareForm()
	{	  
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('payment_data', array('legend'=>Mage::helper('affiliateplus')->__('Withdrawal information')/* , 'class' => 'fieldset-wide' */));
		
	
		$data = array();
		if ( Mage::getSingleton('adminhtml/session')->getPaymentData() )
		{
			$data = Mage::getSingleton('adminhtml/session')->getPaymentData();
			$data['temp_payment_method'] = $data['payment_method'];
			$form->setFormValues($data);
			Mage::getSingleton('adminhtml/session')->setPaymentData(null);
		} elseif ( Mage::registry('payment_data') ) {
			$data = Mage::registry('payment_data')->getData();
			$data['temp_payment_method'] = $data['payment_method'];
			$form->setFormValues($data);
		}
		
		
		$fieldset->addField('account_id', 'hidden', array(
			'name'      => 'account_id',
		));
		
		$fieldset->addField('account_email', 'hidden', array(
			'name'      => 'account_email',
		));	
		
		$fieldset->addField('account_name', 'text', array(
			'label'     => Mage::helper('affiliateplus')->__('Affiliate Name'),
			'class'     => 'required-entry',
			'required'  => true,
			'name'      => 'account_name',
			'readonly'  => 'readonly',
		));
	
		$whoPayFees = Mage::getStoreConfig('affiliateplus/payment/who_pay_fees');
		if($whoPayFees == 'payer')
			$note = Mage::helper('affiliateplus')->__('Not including fee');
		else
			$note = Mage::helper('affiliateplus')->__('Including fee');
		
		
		$params = array(
			'label'     => Mage::helper('affiliateplus')->__('Amount'),
			'name'      => 'amount',
			'class'     => 'required-entry',
			'required'  => true,
			'note'		=> $note,
		);
		
		if($this->getRequest()->getParam('id'))
			$params['readonly'] = 'readonly';
			

		$fieldset->addField('amount', 'text', $params);
		
		$methodPaypalPayment = Mage::getStoreConfig('affiliateplus/payment/payment_method');
		
		if($methodPaypalPayment != 'api' || $data['status'] == 3){ // 3 -> completed
			$params = array(
						'label'     => Mage::helper('affiliateplus')->__('Fee'),
						'name'      => 'fee',
						'class'     => 'required-entry',
						'required'  => true,
					);
			if($data['status'] == 3)
				$params['disabled'] = true;
			
			$fieldset->addField('fee', 'text', $params);
		}
		
		$paymentMethods = $this->_getPaymentHelper()->getAvailablePayment();
		
		if (!$this->_isActivePaymentPlugin()){
			$fieldset->addField('paypal_email', 'text', array(
				'label'     => Mage::helper('affiliateplus')->__('Paypal Email'),
				'name'      => 'paypal_email',
				'readonly'  => 'readonly',
				'class'     => 'required-entry',
				'required'  => true,
			));
			
			if($methodPaypalPayment != 'api'){
				$fieldset->addField('transaction_id', 'text', array(
					'label'     => Mage::helper('affiliateplus')->__('Transaction ID'),
					'name'      => 'transaction_id',
					'class'     => 'required-entry',
					'required'  => true,
				));
			}
		}else {
			$params = array(
				'label'		=> Mage::helper('affiliateplus')->__('Payment Method'),
				'name'		=> 'payment_method',
				'required'	=> true,
				'values'	=> $this->_getPaymentHelper()->getPaymentOption(),
				'onclick'	=> 'changePaymentMethod(this);',
			);
			
			if($data['status'] == 3 || $data['is_request'] == 1){
				$params['disabled'] = true;
				$fieldset->addField('temp_payment_method', 'select', $params);
				
				$fieldset->addField('payment_method', 'hidden', array(
					'name'      => 'payment_method',
				));
			}else
				$fieldset->addField('payment_method', 'select', $params);
			
			$form->addFieldset('payment_method_data', array('legend'=>Mage::helper('affiliateplus')->__('Payment Method Information')));
			
			foreach ($paymentMethods as $code => $paymentMethod){
				$paymentFieldset = $form->addFieldset("payment_fieldset_$code");
				Mage::dispatchEvent("affiliateplus_adminhtml_payment_method_form_$code",array(
					'form'		=> $form,
					'fieldset'	=> $paymentFieldset,
				));
				if ($code == 'paypal'){
					$paymentFieldset->addField('paypal_email', 'text', array(
						'label'     => Mage::helper('affiliateplus')->__('Paypal Email'),
						'name'      => 'paypal_email',
						'readonly'  => 'readonly',
						'class'     => 'required-entry',
						'required'  => true,
					));
					
					if($methodPaypalPayment != 'api'){
						$params = array(
							'label'     => Mage::helper('affiliateplus')->__('Transaction ID'),
							'name'      => 'transaction_id',
							'class'     => 'required-entry',
							'required'  => true,
						);
						
						if($data['status'] == 3)
							$params['readonly'] = 'readonly';
						
						$paymentFieldset->addField('transaction_id', 'text', $params);
					}
				}
			}
			$fieldset->addField('javascript','hidden',array(
				'after_element_html'	=> '
					<script type="text/javascript">
						function changePaymentMethod(el){
							var payment_fieldset = "payment_fieldset_" + el.value;
							$$("div.fieldset").each(function(e){
								if (e.id.startsWith("payment_fieldset_")){
									e.hide();
									var i = 0;
									while(e.down(".required-entry",i) != undefined)
										e.down(".required-entry",i++).disabled = true;
								}if (e.id == payment_fieldset){
									var i = 0;
									while(e.down(".required-entry",i) != undefined)
										e.down(".required-entry",i++).disabled = false;
									e.show();
								}
							});
						}
						document.observe("dom:loaded",function(){
							if ($("payment_method_data")) $("payment_method_data").hide();
							changePaymentMethod($("payment_method"));
						});
					</script>
					',
			));
		}
		
		
		//event to add more field
		Mage::dispatchEvent('affiliateplus_adminhtml_add_field_payment_form', array('fieldset' => $fieldset, 'form' => $form));
		
		$params = array(
				'label'     => Mage::helper('affiliateplus')->__('Status'),
				'name'      => 'status',
				'values'    => array(
					array( 'value'	=> 3, 'label'     => Mage::helper('affiliateplus')->__('Completed')),
					array( 'value'	=> 1, 'label'     => Mage::helper('affiliateplus')->__('Waiting')),
					array( 'value'	=> 2, 'label'     => Mage::helper('affiliateplus')->__('Processing')),
				));
		
		if(($methodPaypalPayment == 'api' && $data['payment_method'] == 'paypal') || $data['status'] == 3 /* completed */)
			$params['disabled'] = true;

		$id = $this->getRequest()->getParam('id');
		
		if($id){
			$fieldset->addField('status', 'select', $params);
			
			$fieldset->addField('request_time', 'note', array(
				'label'     => Mage::helper('affiliateplus')->__('Request Time'),
				'name'      => 'request_time',
				'text'		=> $this->formatDate($data['request_time'], 'medium')
			));
		}
	
		//$form->removeField('payment_data');
		
		$form->setValues($form->getFormValues());
		
		return parent::_prepareForm();
	}
	
	protected function _isActivePaymentPlugin(){
		$modules = Mage::getConfig()->getNode('modules')->children();
		$modulesArray = (array)$modules;
		if (is_object($modulesArray['Magestore_Affiliatepluspayment']))
			return $modulesArray['Magestore_Affiliatepluspayment']->is('active');
		return false;
	}
}