<?php
class Magestore_Affiliateplusprogram_Block_All extends Mage_Core_Block_Template
{
	protected $_is_empty = true;
	
	/**
	 * get Account helper
	 *
	 * @return Magestore_Affiliateplus_Helper_Account
	 */
	protected function _getAccountHelper(){
		return Mage::helper('affiliateplus/account');
	}
	
	/**
	 * get Module helper
	 *
	 * @return Magestore_Affiliateplusprogram_Helper_Data
	 */
	protected function _getHelper(){
		return Mage::helper('affiliateplusprogram');
	}
	
	protected function _construct(){
		parent::_construct();
		
		$collection = Mage::getResourceModel('affiliateplusprogram/program_collection')
			->setStoreId(Mage::app()->getStore()->getId())
			->addFieldToFilter('program_id',array('nin' => $this->_getHelper()->getJoinedProgramIds()));
		
		$group = Mage::getSingleton('customer/session')->getCustomer()->getGroupId();
		$collection->getSelect()
			->where("scope = 0 OR (scope = 1 AND FIND_IN_SET($group,customer_groups) )");
		
		//if ($name = $this->getRequest()->getParam('name'))
		//	$collection->getSelect()->where('name LIKE ?',"%$name%");
		
		$name = $this->getRequest()->getParam('name');
		foreach ($collection as $item)
			if ($item->getStatus() != 1 || ($name && strpos($item->getName(),$name) ===  false))
				$item->setIsContinueNextRow(true);
			elseif ($this->_is_empty)
				$this->_is_empty = false;
		
		$this->setCollection($collection);
	}
	
	public function isEmpty(){
		return $this->_is_empty;
	}
	
	public function _prepareLayout(){
		parent::_prepareLayout();
		$pager = $this->getLayout()->createBlock('page/html_pager','programs_pager')->setCollection($this->getCollection());
		$this->setChild('programs_pager',$pager);
		
		$grid = $this->getLayout()->createBlock('affiliateplus/grid','programs_grid');
		
		$grid->addColumn('select',array(
			'header'	=> '<input type="checkbox" onclick="selectProgram(this);" />',
			'render'	=> 'getSelectProgram',
		));
		
		$grid->addColumn('id',array(
			'header'	=> $this->__('No.'),
			'align'		=> 'left',
			'render'	=> 'getNoNumber',
		));
		
		$grid->addColumn('program_name',array(
			'header'	=> $this->__('Program Name'),
			'render'	=> 'getProgramName',
		));
		
		$grid->addColumn('details',array(
			'header'	=> $this->__('Details'),
			'render'	=> 'getProgramDetails'
		));
		
		$grid->addColumn('created_date',array(
			'header'	=> $this->__('Created Date'),
			'type'		=> 'date',
			'format'	=> 'medium',
			'index'		=> 'created_date'
		));
		
		$grid->addColumn('action',array(
			'header'	=> $this->__('Action'),
			'type'		=> 'action',
			'action'	=> array(
				'label'		=> $this->__('Join Program'),
				'url'		=> 'affiliateplusprogram/index/join',
				'name'		=> 'id',
				'field'		=> 'program_id'
			)
		));
		
		$this->setChild('programs_grid',$grid);
		return $this;
	}
	
	public function getSelectProgram($row){
		return '<input type="checkbox" name="program_ids[]" value="'.$row->getId().'" />';
	}
	
	public function getNoNumber($row){
    	return sprintf('#%d',$row->getId());
    }
    
    public function getProgramName($row){
    	return sprintf('<a href="%s" title="%s">%s</a>'
    		,$this->getUrl('affiliateplusprogram/index/detail',array('id' => $row->getId()))
    		,$this->__('View Program Product List')
    		,$row->getName()
    	);
    }
    
    public function getProgramDetails($row){
    	$standardCommission = $row->getCommission();
		
    	$discount = ($row->getDiscountType() == 'fixed') ? Mage::helper('core')->currency($row->getDiscount()) : rtrim(rtrim(sprintf("%.2f",$row->getDiscount()),'0'),'.').'%';
    	$commission = ($row->getCommissionType() == 'fixed') ? Mage::helper('core')->currency($standardCommission) : rtrim(rtrim(sprintf("%.2f",$standardCommission),'0'),'.').'%';
    	
    	$html = $this->__('Discount: ').'<strong>'.$discount.'</strong><br />';
    	$html .= $this->__('Pay-per-sales: '). '<strong>'.$commission.'</strong>';
    	
    	Mage::dispatchEvent('affiliateplus_prepare_program',array('info' => $row));
    	if ($row->getLevelCount()){
    		$popHtml  = '<table class="data-table"><tr><td><strong>'.$this->__('Level %d',1).'</strong></td><td>';
    		if ($row->getCommissionType() == 'fixed')
    			$popHtml .= $this->__('%s per sale',$commission);
    		else
    			$popHtml .= $this->__('%s of sales amount',$commission);
    		$popHtml .= '</td></tr>';
    		foreach($row->getTierCommission() as $tierCommission){
    			$popHtml .= '<tr><td><strong>'.$tierCommission['level'].'</strong></td><td>';
    			$popHtml .= $tierCommission['commission'].'</td></tr>';
    		}
			$popHtml .= '</table>';
			
			$html .= '<script type="text/javascript">var popHtml'.$row->getId().'= \''.$this->jsQuoteEscape($popHtml).'\';</script>';
    		$html .= '<br /><a href="" title="'.$this->__('View tier level commission amounts').'" onclick="TINY.box.show(popHtml'.$row->getId().',0,0,0,0);return false;">'.$this->__('Tier Commission').'?</a>';
    	}
    	
    	if ($row->getValidFrom())
			$html .= '<br />'.$this->__('From: ').'<strong>'.$this->formatDate($row->getValidFrom(),'medium',false).'</strong>';
		if ($row->getValidTo())
			$html .= '<br />'.$this->__('To: ').'<strong>'.$this->formatDate($row->getValidTo(),'medium',false).'</strong>';
    	
    	return $html;
    }
	
	public function getPagerHtml(){
    	return $this->getChildHtml('programs_pager');
    }
    
    public function getGridHtml(){
    	return $this->getChildHtml('programs_grid');
    }
    
    protected function _toHtml(){
    	$this->getChild('programs_grid')->setCollection($this->getCollection());
    	return parent::_toHtml();
    }
}