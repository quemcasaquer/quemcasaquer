<?php

class Magestore_Affiliateplus_Block_Adminhtml_Transaction_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'affiliateplus';
        $this->_controller = 'adminhtml_transaction';
        
        $this->_removeButton('save');
        $this->_removeButton('delete');
		$this->_removeButton('reset');
		
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('affiliateplus_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'affiliateplus_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'affiliateplus_content');
                }
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('transaction_data') && Mage::registry('transaction_data')->getId() ) {
            return Mage::helper('affiliateplus')->__("View Transaction of '%s'", $this->htmlEscape(Mage::registry('transaction_data')->getAccountName()));
        } else {
            return Mage::helper('affiliateplus')->__('View Transaction');
        }
    }
}