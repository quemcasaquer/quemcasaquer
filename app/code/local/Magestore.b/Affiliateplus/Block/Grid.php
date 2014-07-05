<?php
class Magestore_Affiliateplus_Block_Grid extends Mage_Core_Block_Template
{
	/**
	 * Columns of Grid
	 *
	 * @var array
	 * 
	 * Example of element:
	 * $_columns['id'] = array(
	 * 	'header'	=> 'ID',
	 * 	'align'		=> 'right',
	 * 	'width'		=> '50px',
	 * 	'index'		=> 'account_id',
	 * 	'type'		=> 'date' | 'options' | 'action' | 'datetime' | 'price' | 'baseprice'
	 *	'format'	=> 'medium',
	 * 	'options'	=> array( 'value' => 'label'),
	 * 	'action'	=> array(
	 * 					'label' => 'Edit',
	 * 					'url' 	=> 'affiliateplus/index/index',
	 * 					'name'	=> 'id',
	 * 					'field'	=> 'account_id',
	 * 					),
	 * 	'render'	=> 'function_name_of_parent_block',
	 * );
	 */
	protected $_columns = array();
	
	/**
	 * Grid's Collection
	 */
	protected $_collection;
	
	public function getColumns(){
		return $this->_columns;
	}
	
	public function setCollection($collection){
		$this->_collection = $collection;
		return $this;
	}
	
	public function getCollection(){
		return $this->_collection;
	}
	
	public function _prepareLayout(){
		parent::_prepareLayout();
		$this->setTemplate('affiliateplus/grid.phtml');
		return $this;
    }
    
    /**
     * Add new Column to Grid
     *
     * @param string $columnId
     * @param array $params
     * @return Magestore_Affiliateplus_Block_Grid
     */
    public function addColumn($columnId, $params){
    	$this->_columns[$columnId] = $params;
    	return $this;
    }
    
    /**
     * Call Render Function
     *
     * @param string $parentFunction
     * @param mixed $params
     * @return string
     */
    public function fetchRender($parentFunction, $row){
    	$parentBlock = $this->getParentBlock();
    	
    	$fetchObj = new Varien_Object(array(
    		'function'	=> $parentFunction,
    		'html'		=> false,
    	));
    	Mage::dispatchEvent("affiliateplus_grid_fetch_render_$parentFunction",array(
    		'block'	=> $parentBlock,
    		'row'	=> $row,
    		'fetch'	=> $fetchObj,
    	));
    	
    	if ($fetchObj->getHtml()) return $fetchObj->getHtml();
    	
    	return $parentBlock->$parentFunction($row);
    }
}