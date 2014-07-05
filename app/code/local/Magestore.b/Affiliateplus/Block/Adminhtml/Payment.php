<?php
class Magestore_Affiliateplus_Block_Adminhtml_Payment extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_payment';
    $this->_blockGroup = 'affiliateplus';
    $this->_headerText = Mage::helper('affiliateplus')->__('Withdrawals Manager');
	parent::__construct();
	$this->_removeButton('add');
  }
}