<?php class Meigee_MeigeewidgetsBlacknwhite_Model_Imagesformat
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'.png', 'label'=>Mage::helper('meigeewidgetsblacknwhite')->__('.png')),
            array('value'=>'.jpg', 'label'=>Mage::helper('meigeewidgetsblacknwhite')->__('.jpg')),
            array('value'=>'.gif', 'label'=>Mage::helper('meigeewidgetsblacknwhite')->__('.gif'))
        );
    }

}