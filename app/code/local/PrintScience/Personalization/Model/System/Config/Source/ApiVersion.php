<?php
/**
 * Personalization API URL backend
 *
 */
class PrintScience_Personalization_Model_System_Config_Source_ApiVersion
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'1.0.0', 'label'=>Mage::helper('printscience_personalization')->__('1.0.0')),
            array('value'=>'2.0.0', 'label'=>Mage::helper('printscience_personalization')->__('2.0.0')),
            array('value'=>'4.0.0', 'label'=>Mage::helper('printscience_personalization')->__('4.0.0')),
        );
    }
}
