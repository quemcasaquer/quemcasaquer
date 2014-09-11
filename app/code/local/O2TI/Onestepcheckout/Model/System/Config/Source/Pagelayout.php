<?php 
class O2TI_Onestepcheckout_Model_System_Config_Source_Pagelayout
{
    public function toOptionArray()
    {
        return array(        	
             array('value'=>2, 'label'=>Mage::helper('onestepcheckout')->__('2 Colunas')),
        	 array('value'=>3, 'label'=>Mage::helper('onestepcheckout')->__('3 Colunas')),
        	 array('value'=>4, 'label'=>Mage::helper('onestepcheckout')->__('3 Colunas - revisão do pedido na lateral')),
            );
    }
    
}
