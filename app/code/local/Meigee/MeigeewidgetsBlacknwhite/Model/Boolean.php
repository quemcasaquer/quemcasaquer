<?php class Meigee_MeigeewidgetsBlacknwhite_Model_Boolean
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'1', 'label'=>Mage::helper('meigeewidgetsblacknwhite')->__('True')),
            array('value'=>'0', 'label'=>Mage::helper('meigeewidgetsblacknwhite')->__('False'))
        );
    }

}