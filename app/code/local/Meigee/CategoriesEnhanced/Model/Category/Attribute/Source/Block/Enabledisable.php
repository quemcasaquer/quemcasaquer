 <?php
class Meigee_CategoriesEnhanced_Model_Category_Attribute_Source_Block_Enabledisable extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{	
	public function getAllOptions()
	{
		if (!$this->_options)
		{
			$this->_options = array(
				array('value' => '0',		'label' => 'Use Default Config'),
				array('value' => '1',		'label' => 'Enable'),
				array('value' => '2',		'label' => 'Disable')
			);
        }
		return $this->_options;
    }
}
