<?php
class Magestore_Affiliateplusstatistic_Block_Grids_Bestseller extends Mage_Adminhtml_Block_Dashboard_Grid
{
	public function __construct(){
        parent::__construct();
        $this->setId('gridBestseller');
    }
    
    protected function _prepareCollection(){
    	$collection = Mage::getResourceModel('catalog/product_collection')
    		->addAttributeToSelect('sku')
			->addAttributeToSelect('name')
			->addAttributeToSelect('type_id');
    	
    	$transactionTable = $collection->getTable('affiliateplus/transaction');
    	$collection->getSelect()->join(
    		array('ts'	=> $transactionTable),
    		'FIND_IN_SET(e.entity_id,ts.order_item_ids)',
    		array('num_order_placed' => 'COUNT(ts.transaction_id)')
    	)->where('ts.status = 1')
    	->group('e.entity_id')
    	->order('num_order_placed DESC');
    	
    	if ($storeId = $this->getRequest()->getParam('store')){
			$collection->addStoreFilter($storeId);
			$collection->getSelect()->where("ts.store_id = $storeId");
			$collection->joinAttribute('custom_name', 'catalog_product/name', 'entity_id', null, 'inner', $storeId);
			$collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner', $storeId);
			$collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left', $storeId);
    	}else{
    		$collection->addAttributeToSelect('price');
    		$collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
    	}
    	
		$this->setCollection($collection);
		return parent::_prepareCollection();
    }
    
    protected function _prepareColumns(){
      $this->addColumn('entity_id', array(
          'header'    => Mage::helper('affiliateplus')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'entity_id',
		  'type'	  => 'number',
		  'sortable'  => false,
      ));
      
      $storeId = $this->getRequest()->getParam('store');
      $store = Mage::app()->getStore($storeId);
      
      if ($storeId)
      	$this->addColumn('custom_name', array(
          'header'    => Mage::helper('affiliateplus')->__('Name in %s',$store->getName()),
          'align'     =>'left',
          'index'     => 'custom_name',
          'sortable'  => false,
      	));
      else
      	$this->addColumn('name', array(
          'header'    => Mage::helper('affiliateplus')->__('Name'),
          'align'     =>'left',
          'index'     => 'name',
          'sortable'  => false,
      	));
      
      
      $this->addColumn('type', array(
          'header'    => Mage::helper('affiliateplus')->__('Type'),
          'align'     => 'left',
          'index'     => 'type_id',
          'type'      => 'options',
          'options'   =>  Mage::getSingleton('catalog/product_type')->getOptionArray(),
		  'sortable'  => false,
      ));
      
      $this->addColumn('sku', array(
			'header'    => Mage::helper('affiliateplus')->__('SKU'),
			'index'     => 'sku',
			'sortable'  => false,
      ));
      
      $this->addColumn('price', array(
			'header'    => Mage::helper('affiliateplus')->__('Price'),
			'index'     => 'price',
			'type'		=> 'price',
			'currency_code'	=> $store->getBaseCurrencyCode(),
			'sortable'  => false,
      ));
      
      $this->addColumn('status', array(
          'header'    => Mage::helper('affiliateplus')->__('Status'),
          'align'     => 'right',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => Mage::getSingleton('catalog/product_status')->getOptionArray(),
		  'sortable'  => false,
      ));
    }
    
    public function getRowUrl($row){
    	return $this->getUrl('adminhtml/catalog_product/edit',array(
    		'id' => $row->getId(),
    		'store' => $this->getRequest()->getParam('store')
    	));
    }
}