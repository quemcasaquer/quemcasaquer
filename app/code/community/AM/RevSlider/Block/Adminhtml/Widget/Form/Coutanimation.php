<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Widget_Form_Coutanimation
    extends Mage_Adminhtml_Block_Widget
    implements Varien_Data_Form_Element_Renderer_Interface{

    protected $_element;

    public function __construct(){
        parent::__construct();
        $this->setTemplate('am/revslider/widget/form/coutanimation.phtml');
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

    protected function _prepareLayout(){
        $this->setChild('edit', $this->getLayout()->createBlock('adminhtml/widget_button', '', array(
            'label'     => Mage::helper('revslider')->__('Custom Animation'),
            'type'      => 'button',
            'id'        => 'cOutAnimation',
            'onclick'   => sprintf('revLayer.openAnimationDialog(\'%s\', \'editAnimationWindow\', \'%s\', \'out\')',
                $this->getUrl('revslideradmin/slider/animation', array('type' => 'out')),
                Mage::helper('revslider')->__('Edit Animation')
            )
        )));
        $this->setChild('new', $this->getLayout()->createBlock('adminhtml/widget_button', '', array(
            'label'     => Mage::helper('revslider')->__('New Animation'),
            'type'      => 'button',
            'id'        => 'cNewOutAnimation',
            'onclick'   => sprintf('revLayer.openAnimationDialog(\'%s\', \'editAnimationWindow\', \'%s\', \'new\')',
                $this->getUrl('revslideradmin/slider/animation'),
                Mage::helper('revslider')->__('New Animation')
            )
        )));

        return parent::_prepareLayout();
    }
}