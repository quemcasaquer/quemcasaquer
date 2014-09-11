<?php
class O2TI_Onestepcheckout_Model_Entity_Tipopessoa extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
	public function getAllOptions()
	{
		if ($this->_options === null) {
			$this->_options = array();
			$this->_options[] = array(
                    'value' => 1,
                    'label' => 'Pessoa Física'
			);
			$this->_options[] = array(
                    'value' => 2,
                    'label' => 'Pessoa Jurídica'
			);			
		}

		return $this->_options;
	}
}
