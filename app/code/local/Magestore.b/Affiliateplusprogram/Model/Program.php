<?php

class Magestore_Affiliateplusprogram_Model_Program extends Mage_Rule_Model_Rule
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
    
    public function getStoreAttributes(){
    	return array(
    		'name',
    		'affiliate_type',
    		'status',
    		'description',
    		'commission_type',
    		'commission',
    		'discount_type',
    		'discount',
    		'show_in_welcome',
    		'use_tier_config',
    		'max_level',
    		'tier_commission'
    	);
    }
    
    public function getTotalAttributes(){
    	return array(
    		'total_sales_amount',
    		//'total_clicks',
    		//'total_unique_clicks'
    	);
    }
    
    /**
     * load data for model
     *
     * @param mixed $id
     * @param string $field
     * @return Magestore_Affiliateplusprogram_Model_Program
     */
    public function load($id, $field=null){
    	parent::load($id,$field);
    	if ($this->getStoreId())
    		$this->loadStoreValue();
    	if (is_string($this->getData('tier_commission')))
    		$this->setData('tier_commission',unserialize($this->getData('tier_commission')));
    	return $this;
    }
    
    /**
     * function model value in store
     *
     * @param int $storeId
     * @return Magestore_Affiliateplusprogram_Model_Program
     */
    public function loadStoreValue($storeId = null){
    	if (!$storeId)
    		$storeId = $this->getStoreId();
   		if (!$storeId)
   			return $this;
    	$storeValues = Mage::getModel('affiliateplusprogram/value')->getCollection()
			->addFieldToFilter('program_id',$this->getId())
			->addFieldToFilter('store_id',$storeId);
		
    	foreach ($storeValues as $value){
    		$this->setData($value->getAttributeCode().'_in_store',true);
    		$this->setData($value->getAttributeCode(),$value->getValue());
    	}
		
		foreach ($this->getStoreAttributes() as $attribute)
			if (!$this->getData($attribute.'_in_store'))
				$this->setData($attribute.'_default',true);
		
		foreach ($this->getTotalAttributes() as $attribute)
			if (!$this->getData($attribute.'_in_store')){
				$this->setData($attribute.'_in_store',true);
				$this->setData($attribute,0.000000000001);
			}
		
    	return $this;
    }
    
    protected function _beforeSave(){
    	$defaultProgram = Mage::getModel('affiliateplusprogram/program')->load($this->getId());
    	if ($storeId = $this->getStoreId()){
    		$storeAttributes = $this->getStoreAttributes();
    		foreach ($storeAttributes as $attribute){
	    		if ($this->getData($attribute.'_default')){
	    			$this->setData($attribute.'_in_store',false);
	    		}else{
	    			$this->setData($attribute.'_in_store',true);
	    			$this->setData($attribute.'_value',$this->getData($attribute));
	    		}
	    		if ($defaultProgram->getId())
	    			$this->setData($attribute,$defaultProgram->getData($attribute));
	    	}
	    	if ($this->getId()){
	    		$totalAttributes = $this->getTotalAttributes();
	    		foreach ($totalAttributes as $attribute){
	    			$attributeValue = Mage::getModel('affiliateplusprogram/value')
	    				->loadAttributeValue($this->getId(),$storeId,$attribute);
	    			if ($delta = ($this->getData($attribute) - $attributeValue->getValue())){
		    			try{
		    				$attributeValue->setValue($this->getData($attribute));
		    				$attributeValue->save();
		    			}catch(Exception $e){
		    				
		    			}
		    		}
		    		$this->setData($attribute,$defaultProgram->getData($attribute)+$delta);
	    		}
	    	}
    	}
    	if (is_array($this->getData('tier_commission')))
    		$this->setData('tier_commission',serialize($this->getData('tier_commission')));
    	if (is_array($this->getData('tier_commission_value')))
    		$this->setData('tier_commission_value',serialize($this->getData('tier_commission_value')));
    	return parent::_beforeSave();
    }
    
    protected function _afterSave(){
    	if ($storeId = $this->getStoreId()){
    		$storeAttributes = $this->getStoreAttributes();
    		foreach ($storeAttributes as $attribute){
    			$attributeValue = Mage::getModel('affiliateplusprogram/value')
    				->loadAttributeValue($this->getId(),$storeId,$attribute);
    			if ($this->getData($attribute.'_in_store')){
	    			try{
	    				$attributeValue->setValue($this->getData($attribute.'_value'))->save();
	    			}catch(Exception $e){
	    				
	    			}
	    		}elseif($attributeValue && $attributeValue->getId()){
	    			try{
	    				$attributeValue->delete();
	    			}catch(Exception $e){
	    				
	    			}
	    		}
    		}
    	}
    	if (is_string($this->getData('tier_commission')))
    		$this->setData('tier_commission',unserialize($this->getData('tier_commission')));
    	return parent::_afterSave();
    }
    
    public function getAccountIds(){
    	$accountCollection = Mage::getResourceModel('affiliateplusprogram/account_collection')
    		->addFieldToFilter('program_id',$this->getId());
    	$accountIds = array();
    	foreach ($accountCollection as $account)
    		$accountIds[] = $account->getAccountId();
    	return $accountIds;
    }
    
    public function isAvailable(){
    	if (!$this->getId() || !$this->getStatus()) return false;
    	if ($this->getValidFrom()) if (strtotime($this->getValidFrom()) > time()) return false;
    	if ($this->getValidTo()) if (strtotime($this->getValidTo()) < strtotime(now(true))) return false;
    	return true;
    }
    
    public function getConditionsInstance(){
    	return Mage::getModel('salesrule/rule_condition_combine');
    }
    
    public function getActionsInstance(){
    	return Mage::getModel('salesrule/rule_condition_product_combine');
    }
    
    public function loadPost(array $rule){
    	$arr = $this->_convertFlatToRecursive($rule);
    	if (isset($arr['conditions'])){
    		$this->getConditions()->setConditions(array())->loadArray($arr['conditions'][1]);
    	}
    	if (isset($arr['actions'])){
    		$this->getActions()->setActions(array())->loadArray($arr['actions'][1], 'actions');
    	}
    	return $this;
    }
    
    public function validateOrder($order){
    	if (!$this->isAvailable()) return false;
    	if (!$order->getQuote()){
    		$order->setQuote($order);
    	}
    	return $this->validate($order);
    }
    
    public function validateItem($item){
    	if (!$this->isAvailable()) return false;
    	if ($item instanceof Mage_Catalog_Model_Product){
    		$_item = Mage::getModel('sales/quote_item')->setProduct($item);
    		$item  = $_item;
    	}
    	if (!in_array($item->getProduct()->getId(),Mage::helper('affiliateplusprogram')->getProgramProductIds($this->getId()))){
    		if ($parentItem = $item->getParentItem()){
    			if (!in_array($parentItem->getProduct()->getId(),Mage::helper('affiliateplusprogram')->getProgramProductIds($this->getId()))) return false;
    		} else {
    			return false;
    		}
    	}
    	return $this->getActions()->validate($item);
    }
    
    public function setProgramIsProcessed(){
    	$this->getResource()->setProgramIsProcessed($this);
    	return $this;
    }
    
    public function orgSave(){
    	try {
    		if (is_array($this->getData('tier_commission')))
    			$this->setData('tier_commission',serialize($this->getData('tier_commission')));
    		$this->getResource()->save($this);
    		if (is_string($this->getData('tier_commission')))
    			$this->setData('tier_commission',unserialize($this->getData('tier_commission')));
   		} catch (Exception $e){}
    	return $this;
    }
}