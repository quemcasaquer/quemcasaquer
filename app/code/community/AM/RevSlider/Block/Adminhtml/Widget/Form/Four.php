<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Widget_Form_Four
    extends Mage_Adminhtml_Block_Widget
    implements Varien_Data_Form_Element_Renderer_Interface{

    protected $_element;
    protected $_items;

    public function __construct(){
        parent::__construct();
        $this->setTemplate('am/revslider/widget/form/four.phtml');
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

    public function getAll(){
        if (!$this->_items){
            $items = array();
            $count = (int)$this->getElement()->getData('count');
            $labels = $this->getElement()->getData('labels');
            if ($count && $count > 0){
                for ($i=0; $i<$count; $i++) $items[] = array('id' => $this->getElement()->getHtmlId() . '_' .$i, 'label' => isset($labels[$i]) ? $labels[$i] : '');
            }
            $this->_items = $items;
        }
        return $this->_items;
    }
}