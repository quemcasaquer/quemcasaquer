<?php
/**
 * @category    AM
 * @package     AM_Extensions
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_Extensions_Block_Adminhtml_Widget_Form_Element_Browser
    extends Mage_Adminhtml_Block_Widget
    implements Varien_Data_Form_Element_Renderer_Interface{

    protected $_element;

    public function __construct(){
        parent::__construct();
        $this->setTemplate('am/extensions/adminhtml/widget/form/element/browser.phtml');
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
        $this->setElement($this->getData('element'));

        $browserBtn = $this->getLayout()->createBlock('adminhtml/widget_button', 'button',  array(
            'label'     => '...',
            'title'     => Mage::helper('amext')->__('Click to browser media'),
            'type'      => 'button',
            'onclick'   => sprintf('AM.MediabrowserUtility.openDialog(\'%s\')',
                Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index', array(
                    'static_urls_allowed'   => 1,
                    'target_element_id'     => $this->getElement()->getHtmlId()
                ))
            )
        ));
        $this->setChild('browserBtn', $browserBtn);
        $clearBtn = $this->getLayout()->createBlock('adminhtml/widget_button', 'button',  array(
            'label'     => 'x',
            'title'     => Mage::helper('amext')->__('Click to clear value'),
            'type'      => 'button',
            'onclick'   => "on_{$this->getElement()->getHtmlId()}_clear_click();"
        ));
        $this->setChild('clearBtn', $clearBtn);

        return parent::_prepareLayout();
    }
}