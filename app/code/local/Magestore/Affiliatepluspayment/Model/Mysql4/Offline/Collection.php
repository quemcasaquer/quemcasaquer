<?php

class Magestore_Affiliatepluspayment_Model_Mysql4_Offline_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct(){
        parent::_construct();
        $this->_init('affiliatepluspayment/offline');
    }
}