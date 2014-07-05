<?php
class Magestore_Affiliateplusstatistic_Block_Grids_Referers extends Mage_Adminhtml_Block_Dashboard_Grid
{
	public function __construct(){
        parent::__construct();
        $this->setId('gridReferers');
        $this->setDefaultSort('clicks');
        $this->setDefaultDir('DESC');
    }
    
    protected function _prepareCollection(){
    	$collection = Mage::getResourceModel('affiliateplusstatistic/statistic_collection');
    	
    	$collection->getSelect()->reset(Zend_Db_Select::COLUMNS)
    		->columns(array(
    			'referer',
    			'clicks'	=> 'COUNT(id)',
    			'uniques'	=> 'COUNT(DISTINCT ip_address)',
    		))
    		->group('referer');
    	
    	if ($storeId = $this->getRequest()->getParam('store'))
			$collection->addFieldToFilter('store_id',$storeId);
		
		$this->setCollection($collection);
		return parent::_prepareCollection();
    }
    
    protected function _prepareColumns(){
      $this->addColumn('referer', array(
          'header'    => Mage::helper('affiliateplus')->__('Referer'),
          'index'     => 'referer',
		  'sortable'  => false,
		  'renderer'  => 'affiliateplusstatistic/grids_renderer_referer',
      ));
      
      $this->addColumn('clicks', array(
          'header'    => Mage::helper('affiliateplus')->__('Total Clicks'),
          'align'     =>'left',
          'index'     => 'clicks',
          'sortable'  => false,
      ));
      
      $this->addColumn('uniques', array(
			'header'    => Mage::helper('affiliateplus')->__('Unique Clicks'),
			'index'     => 'uniques',
			'sortable'  => false,
      ));
    }
    
    public function getRowUrl($row){
    	return ;
    }
}