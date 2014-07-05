<?php
class Magestore_Affiliateplusprogram_Block_Program extends Mage_Core_Block_Template
{
	protected $_commission_array = array();
	protected $_is_empty = true;
	
	/**
	 * get Account helper
	 *
	 * @return Magestore_Affiliateplus_Helper_Account
	 */
	protected function _getAccountHelper(){
		return Mage::helper('affiliateplus/account');
	}
	
	protected function _construct(){
		parent::_construct();
		
		$collection = Mage::getResourceModel('affiliateplusprogram/program_collection');
		$collection->setStoreId(Mage::app()->getStore()->getId());
		$collection->getSelect()->join(
			array('account' => $collection->getTable('affiliateplusprogram/account')),
			'main_table.program_id = account.program_id',
			array(
				'joined_at'	=> 'joined',
		))->where('account.account_id = ?',$this->_getAccountHelper()->getAccount()->getId());
		
		// gather total commission
		/*$transactionCollection = Mage::getResourceModel('affiliateplusprogram/transaction_collection');
		$transactionCollection->getSelect()->join(
				array('transaction' => $transactionCollection->getTable('affiliateplus/transaction')),
				'main_table.transaction_id = transaction.transaction_id',
				array()
			)->columns('SUM(main_table.commission) as total_commission')
			->where('main_table.account_id = ?',$this->_getAccountHelper()->getAccount()->getId())
			->where('transaction.status = 1')
			->group('main_table.program_id');
		if (Mage::helper('affiliateplus/config')->getSharingConfig('balance') == 'store')
			$transactionCollection->addFieldToFilter('transaction.store_id',Mage::app()->getStore()->getId());
		
		foreach ($transactionCollection as $transaction)
			$this->_commission_array[$transaction->getProgramId()] = $transaction->getTotalCommission();
		
		$defaultTransactions = Mage::getResourceModel('affiliateplus/transaction_collection');
		$defaultTransactions->getSelect()->joinLeft(
				array('transaction'	=> $transactionCollection->getTable('affiliateplusprogram/transaction')),
				'main_table.transaction_id = transaction.transaction_id',
				array()
			)->where('transaction.id IS NULL')
			->where('main_table.account_id = ?',$this->_getAccountHelper()->getAccount()->getId())
			->where('main_table.status = 1')
			->columns('SUM(main_table.commission) as total_commission')
			->group('main_table.account_id');
		if (Mage::helper('affiliateplus/config')->getSharingConfig('balance') == 'store')
			$defaultTransactions->addFieldToFilter('main_table.store_id',Mage::app()->getStore()->getId());
		$this->_commission_array[0] += $defaultTransactions->getFirstItem()->getTotalCommission();*/
		
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
		
		// prepare column
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
		
		$grid->addColumn('joined_at',array(
			'header'	=> $this->__('Joined On'),
			'type'		=> 'date',
			'format'	=> 'medium',
			'index'		=> 'joined_at'
		));
		
		$grid->addColumn('action',array(
			'header'	=> $this->__('Action'),
			'type'		=> 'action',
			'action'	=> array(
				'label'		=> $this->__('Opt out'),
				'url'		=> 'affiliateplusprogram/index/out',
				'name'		=> 'id',
				'field'		=> 'program_id'
			)
		));
		
		$this->setChild('programs_grid',$grid);
		return $this;
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
    
    public function getAllProgramUrl(){
    	return $this->getUrl('affiliateplusprogram/index/all');
    }
    
    public function isShowDefaultProgram(){
    	return (Mage::helper('affiliateplus/config')->getGeneralConfig('commission') && Mage::helper('affiliateplus/config')->getGeneralConfig('discount'));
    }
    
    public function getDefaultProgramTotalCommission(){
    	return $this->_commission_array[0];
    }
    
    public function getDefaultProgramDetail(){
    	$row = new Varien_Object(array(
    		'id'				=> 0,
    		'discount'			=> Mage::helper('affiliateplus/config')->getGeneralConfig('discount'),
    		'discount_type'		=> Mage::helper('affiliateplus/config')->getGeneralConfig('discount_type'),
    		'commission'		=> Mage::helper('affiliateplus/config')->getGeneralConfig('commission'),
    		'commission_type'	=> Mage::helper('affiliateplus/config')->getGeneralConfig('commission_type'),
    	));
    	return $this->getProgramDetails($row);
    }
    
    protected function _toHtml(){
    	$this->getChild('programs_grid')->setCollection($this->getCollection());
    	return parent::_toHtml();
    }
}