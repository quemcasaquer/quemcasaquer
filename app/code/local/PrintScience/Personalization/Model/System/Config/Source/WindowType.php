<?php
/**
 * Personalization API URL backend
 *
 */
class PrintScience_Personalization_Model_System_Config_Source_WindowType
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'1', 'label'=>Mage::helper('printscience_personalization')->__('New Window')),
            array('value'=>'2', 'label'=>Mage::helper('printscience_personalization')->__('Modal Pop-up window')),
        );
    }
}
?>