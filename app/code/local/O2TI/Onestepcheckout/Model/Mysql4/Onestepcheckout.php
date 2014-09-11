<?php

class O2TI_Onestepcheckout_Model_Mysql4_Onestepcheckout extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the onestepcheckout_id refers to the key field in your database table.
        $this->_init('onestepcheckout/onestepcheckout', 'o2ti_onestepcheckout_date_id');
    }
}