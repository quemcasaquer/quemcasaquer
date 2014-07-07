<?php 
class O2TI_Moip_Model_Mysql4_Write extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('moip/write', 'order_id');
    }
}
?>