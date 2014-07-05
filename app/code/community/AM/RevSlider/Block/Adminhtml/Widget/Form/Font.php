<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Widget_Form_Font
    extends Mage_Adminhtml_Block_Widget
    implements Varien_Data_Form_Element_Renderer_Interface{

    protected $_element;

    public function __construct(){
        parent::__construct();
        $this->setTemplate('am/revslider/widget/form/font.phtml');
    }

    public function getElement(){
        return $this->_element;
    }

    public function setElement(Varien_Data_Form_Element_Abstract $element){
        return $this->_element = $element;
    }

    public function render(Varien_Data_Form_Element_Abstract $element){
        $this->setElement($element);
        return $this->toHtml();
    }

    public function getAddButtonHtml(){
        return $this->getChildHtml('addBtn');
    }

    public function getDeleteButtonHtml(){
        return $this->getChildHtml('deleteBtn');
    }

    protected function _prepareLayout(){
        $addBtn = $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
            'label'     => Mage::helper('revslider')->__('Add Font'),
            'onclick'   => 'return revsliderFont.add()',
            'class'     => 'add'
        ));
        $this->setChild('addBtn', $addBtn);

        $delteBtn = $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
            'onclick'   => 'return revsliderFont.remove({{id}})',
            'class'     => 'delete'
        ));
        $this->setChild('deleteBtn', $delteBtn);
        parent::_prepareLayout();
    }

    public function getFonts(){
        $fonts = array();
        $data = $this->getElement()->getValue();
        if (is_array($data)){
            foreach ($data as $value){
                if ($value) $fonts[] = Mage::helper('core')->escapeHtml($value);
            }
        }
        return $fonts;
    }
}