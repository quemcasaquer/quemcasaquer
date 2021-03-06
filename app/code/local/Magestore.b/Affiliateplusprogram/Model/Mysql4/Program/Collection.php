<?php

class Magestore_Affiliateplusprogram_Model_Mysql4_Program_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	protected $_store_id = null;
	
	public function setStoreId($value){
		$this->_store_id = $value;
		return $this;
	}
	
	public function getStoreId(){
		return $this->_store_id;
	}
	
    public function _construct(){
        parent::_construct();
        $this->_init('affiliateplusprogram/program');
    }
    
    protected function _afterLoad(){
    	parent::_afterLoad();
    	if ($storeId = $this->getStoreId())
    	foreach ($this->_items as $item){
    		$item->setStoreId($storeId)->loadStoreValue();
    	}
    	return $this;
    }
}