<?php
class Meigee_CategoriesEnhanced_Model_Category_Attribute_Source_Block_Grid extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{	
	public function getAllOptions()
	{
		if (!$this->_options)
		{
			$this->_options = array(
				array('value' => '',		'label' => 'Use Default Config'),
				array('value' => '2',		'label' => '2'),
		        array('value' => '3',		'label' => '3'),
				array('value' => '4',		'label' => '4'),
				array('value' => '5',		'label' => '5'),
				array('value' => '6',		'label' => '6'),
				array('value' => '7',		'label' => '7'),
				array('value' => '8',		'label' => '8')
			);
        }
		return $this->_options;
    }
}
