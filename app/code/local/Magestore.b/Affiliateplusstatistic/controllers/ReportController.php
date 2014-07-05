<?php
class Magestore_Affiliateplusstatistic_ReportController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction() {
		$this->_title($this->__('Affiliateplus'))
			->_title($this->__('Statistic'));
		$this->loadLayout()
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Affiliate Plus'), Mage::helper('adminhtml')->__('Statistic'));
		
		return $this;
	}
	
	public function _initReportAction($blocks){
		if (!is_array($blocks))
			$blocks = array($blocks);

		$requestData = Mage::helper('adminhtml')->prepareFilterString($this->getRequest()->getParam('filter'));
		$requestData = $this->_filterDates($requestData, array('from', 'to'));
		$requestData['store_ids'] = $this->getRequest()->getParam('store_ids');
		$params = new Varien_Object();
		
		foreach ($requestData as $key => $value)
			if (!empty($value))
				$params->setData($key, $value);
		
		foreach ($blocks as $block)
			if ($block) {
				$block->setPeriodType($params->getData('period_type'));
				$block->setFilterData($params);
			}
		
		return $this;
	}
 
	public function salesAction() {
		if(!Mage::helper('magenotification')->checkLicenseKeyAdminController($this)){ return; }
		$this->_initAction()
			->_title($this->__('Sales Report'))
			->_setActiveMenu('affiliateplus/statistic/sales')
			->_addBreadcrumb($this->__('Sales Report'));
		
		$gridBlock = $this->getLayout()->getBlock('report_sales.grid');
		$filterFormBlock = $this->getLayout()->getBlock('grid.filter.form');
		
		$this->_initReportAction(array($gridBlock,$filterFormBlock));
		
		$this->renderLayout();
	}
	
	public function exportSalesCsvAction(){
		if(!Mage::helper('magenotification')->checkLicenseKeyAdminController($this)){ return; }
		$fileName = 'affiliateSales.csv';
		$grid = $this->getLayout()->createBlock('affiliateplusstatistic/report_sales_grid');
		$this->_initReportAction($grid);
		$this->_prepareDownloadResponse($fileName,$grid->getCsvFile());
	}
	
	public function exportSalesExcelAction(){
		if(!Mage::helper('magenotification')->checkLicenseKeyAdminController($this)){ return; }
		$fileName = 'affiliateplusSales.xml';
		$grid = $this->getLayout()->createBlock('affiliateplusstatistic/report_sales_grid');
		$this->_initReportAction($grid);
		$this->_prepareDownloadResponse($fileName,$grid->getExcelFile($fileName));
	}
	
	public function clicksAction(){
		if(!Mage::helper('magenotification')->checkLicenseKeyAdminController($this)){ return; }
		$this->_initAction()
			->_title($this->__('Click Report'))
			->_setActiveMenu('affiliateplus/statistic/clicks')
			->_addBreadcrumb($this->__('Click Report'));
		
		$gridBlock = $this->getLayout()->getBlock('report_clicks.grid');
		$filterFormBlock = $this->getLayout()->getBlock('grid.filter.form');
		
		$this->_initReportAction(array($gridBlock,$filterFormBlock));
		
		$this->renderLayout();
	}
	
	public function exportClicksCsvAction(){
		if(!Mage::helper('magenotification')->checkLicenseKeyAdminController($this)){ return; }
		$fileName = 'affiliateClicks.csv';
		$grid = $this->getLayout()->createBlock('affiliateplusstatistic/report_clicks_grid');
		$this->_initReportAction($grid);
		$this->_prepareDownloadResponse($fileName,$grid->getCsvFile());
	}
	
	public function exportClicksExcelAction(){
		if(!Mage::helper('magenotification')->checkLicenseKeyAdminController($this)){ return; }
		$fileName = 'affiliateplusClicks.xml';
		$grid = $this->getLayout()->createBlock('affiliateplusstatistic/report_clicks_grid');
		$this->_initReportAction($grid);
		$this->_prepareDownloadResponse($fileName,$grid->getExcelFile($fileName));
	}
}