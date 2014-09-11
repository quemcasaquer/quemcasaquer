<?php 
class O2TI_Moip_Model_Mysql4_Write_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('moip/write');
    }
}