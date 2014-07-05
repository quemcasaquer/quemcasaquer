<?php

class Meigee_ThemeOptionsBlacknwhite_Block_Adminhtml_Restore_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'themeoptionsblacknwhite';
        $this->_controller = 'adminhtml_restore';
         
        $this->_removeButton('reset');
        $this->_updateButton('save', 'label', Mage::helper('ThemeOptionsBlacknwhite')->__('Restore'));
    }
 
    public function getHeaderText()
    {
        return Mage::helper('ThemeOptionsBlacknwhite')->__('Theme Settings Restore');
    }


    


}