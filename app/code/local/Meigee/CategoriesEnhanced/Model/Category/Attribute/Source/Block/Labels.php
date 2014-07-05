<?php
class Meigee_CategoriesEnhanced_Model_Category_Attribute_Source_Block_Labels extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{	
	public function getAllOptions()
	{
		if (!$this->_options)
		{
			$this->_options = array(
				array('value' => '',		'label' => 'No Label'),
				array('value' => 'label_one',		'label' => 'Label #1'),
				array('value' => 'label_two',		'label' => 'Label #2'),
				array('value' => 'label_three',		'label' => 'Label #3')
			);
        }
		return $this->_options;
    }
}
