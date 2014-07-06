<?php
class OnlineBiz_Facebookstore_Block_Facebookstore extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getFanlist()     
     { 
        if (!$this->hasData('fanlist')) {
            $this->setData('fanlist', Mage::registry('fanlist'));
        }
        return $this->getData('fanlist');
        
    }
}