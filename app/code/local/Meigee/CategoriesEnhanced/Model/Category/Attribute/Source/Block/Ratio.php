<?php
class Meigee_CategoriesEnhanced_Model_Category_Attribute_Source_Block_Ratio extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{	
	public function getAllOptions()
	{
		if (!$this->_options)
		{
			$this->_options = array(
				array('value' => '',		'label' => ' '),
		        array('value' => '10/2',	'label' => '5/1'),
                array('value' => '8/4',		'label' => '4/2'),
                array('value' => '6/6',		'label' => '3/3'),
                array('value' => '4/8',		'label' => '2/4'),
                array('value' => '2/10',	'label' => '1/5')
			);
        }
		return $this->_options;
    }
}
