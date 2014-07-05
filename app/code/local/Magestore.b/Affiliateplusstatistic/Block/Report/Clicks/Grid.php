<?php
class Magestore_Affiliateplusstatistic_Block_Report_Clicks_Grid extends Magestore_Affiliateplusstatistic_Block_Report_Grid_Abstract
{
	protected $_columnGroupBy = 'visit_at';
	protected $_period_column = 'visit_at';
	
	public function __construct(){
		parent::__construct();
		$this->_resourceCollectionName = 'affiliateplusstatistic/report_clicks_collection';
		$this->setCountTotals(true);
	}
	
	protected function _prepareColumns(){
		$this->addColumn('visit_at',array(
			'header'	=> Mage::helper('affiliateplusstatistic')->__('Period'),
			'index'		=> 'visit_at',
			'width'		=> 100,
			'sortable'	=> false,
			'period_type'	=> $this->getPeriodType(),
			'renderer'	=> 'adminhtml/report_sales_grid_column_renderer_date',
			'totals_label'	=> Mage::helper('adminhtml')->__('Total'),
			'html_decorators'	=> array('nobr'),
		));
		
		$this->addColumn('referer',array(
			'header'	=> Mage::helper('affiliateplusstatistic')->__('Referer'),
			'index'		=> 'referer',
			'renderer'	=> 'affiliateplusstatistic/report_renderer_referer',
			'totals_label'	=> '',
			'sortable'	=> false
		));
		
		$this->addColumn('url_path',array(
			'header'	=> Mage::helper('affiliateplusstatistic')->__('Url Path'),
			'index'		=> 'url_path',
			'renderer'	=> 'affiliateplusstatistic/report_renderer_path',
			'totals_label'	=> '',
			'sortable'	=> false
		));
		
		$this->addColumn('referer_id',array(
			'header'	=> Mage::helper('affiliateplusstatistic')->__('Clicks'),
			'index'		=> 'referer_id',
			'type'		=> 'number',
			'total'		=> 'count',
			'sortable'	=> false
		));
		
		$this->addColumn('ip_address',array(
			'header'	=> Mage::helper('affiliateplusstatistic')->__('Unique Clicks'),
			'index_prefix'	=> 'DISTINCT ',
			'index'		=> 'ip_address',
			'type'		=> 'number',
			'total'		=> 'count',
			'renderer'	=> 'affiliateplusstatistic/report_renderer_unique',
			'sortable'	=> false
		));
		
		$this->addExportType('*/*/exportClicksCsv', Mage::helper('adminhtml')->__('CSV'));
		$this->addExportType('*/*/exportClicksExcel', Mage::helper('adminhtml')->__('Excel XML'));
		
		return parent::_prepareColumns();
	}
}