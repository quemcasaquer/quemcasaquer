<?php
class Magestore_Affiliateplusstatistic_Block_Diagrams_Totals extends Mage_Adminhtml_Block_Dashboard_Bar
{
	protected function _construct(){
		parent::_construct();
		$this->setTemplate('dashboard/totalbar.phtml');
	}
	
	protected function _prepareLayout(){
		$storeId = $this->getRequest()->getParam('store');
		$collection = Mage::getResourceModel('affiliateplusstatistic/sales_collection')
			->prepareTotal($this->getRequest()->getParam('period','24h'),0,0,$storeId);
		if ($storeId) $collection->addFieldToFilter('store_id',$storeId);
		$totals = $collection->load()->getFirstItem();
		
		$this->addTotal($this->__('Sales Amount'),$totals->getTotalAmount());
		$this->addTotal($this->__('Transactions'),$totals->getTotalTransaction(),true);
		$this->addTotal($this->__('Commission'),$totals->getTotalCommission());
		
		$collection = Mage::getResourceModel('affiliateplusstatistic/statistic_collection')
			->prepareTotal($this->getRequest()->getParam('period','24h'),0,0,$storeId);
		if ($storeId) $collection->addFieldToFilter('store_id',$storeId);
		$totals = $collection->load()->getFirstItem();
		
		$this->addTotal(
			$this->__('Unique Clicks / Total Clicks')
			,$totals->getTotalUniques() . ' / ' . $totals->getTotalClicks()
			,true
		);
	}
}