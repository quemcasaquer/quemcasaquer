<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Widget_Form_Layers
    extends Mage_Adminhtml_Block_Widget
    implements Varien_Data_Form_Element_Renderer_Interface{

    protected $_element;

    public function __construct(){
        parent::__construct();
        $this->setTemplate('am/revslider/widget/form/layers.phtml');
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
        $addLayerBtn = $this->getLayout()->createBlock('adminhtml/widget_button',
            'addLayerBtn', array(
            'type'      => 'button',
            'label'     => Mage::helper('revslider')->__('Add Layer'),
            'onclick'   => 'revLayer.addLayerText()',
            'class'     => 'layer-text'
        ));
        $this->setChild('addLayerBtn', $addLayerBtn);

        $addLayerBtn = $this->getLayout()->createBlock('adminhtml/widget_button',
            'addLayerImageBtn', array(
            'type'      => 'button',
            'label'     => Mage::helper('revslider')->__('Add Layer: Image'),
            'onclick'   => sprintf('AM.MediabrowserUtility.openDialog(\'%s\', \'addLayerImageWindow\', null, null, \'%s\')',
                Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index', array(
                    'static_urls_allowed'   => 1,
                    'onInsertCallback'      => 'revLayer.addLayerImage'
                )),
                Mage::helper('revslider')->__('Add Image')
            ),
            'class'     => 'layer-image'
        ));
        $this->setChild('addLayerImageBtn', $addLayerBtn);

        $addLayerBtn = $this->getLayout()->createBlock('adminhtml/widget_button',
            'addLayerVideoBtn', array(
            'type'      => 'button',
            'label'     => Mage::helper('revslider')->__('Add Layer: Video'),
            'onclick'   => sprintf('AM.MediabrowserUtility.openDialog(\'%s\', \'addLayerVideoWindow\', null, 700, \'%s\')',
                Mage::getSingleton('adminhtml/url')->getUrl('revslideradmin/slider/video'),
                Mage::helper('revslider')->__('Add Video')
            ),
            'class'     => 'layer-video'
        ));
        $this->setChild('addLayerVideoBtn', $addLayerBtn);

        $addLayerBtn = $this->getLayout()->createBlock('adminhtml/widget_button',
            'deleteLayerBtn', array(
            'label'     => Mage::helper('revslider')->__('Delete Layer'),
            'onclick'   => 'revLayer.deleteLayer()',
            'type'      => 'button',
            'class'     => 'delete',
            'id'        => 'deleteLayerBtn'
        ));
        $this->setChild('deleteLayerBtn', $addLayerBtn);

        $addLayerBtn = $this->getLayout()->createBlock('adminhtml/widget_button',
            'deleteAllLayersBtn', array(
            'label'     => Mage::helper('revslider')->__('Delete All Layers'),
            'onclick'   => 'revLayer.deleteAllLayers()',
            'type'      => 'button',
            'class'     => 'delete'
        ));
        $this->setChild('deleteAllLayersBtn', $addLayerBtn);

        $addLayerBtn = $this->getLayout()->createBlock('adminhtml/widget_button',
            'dupLayerBtn', array(
            'label'     => Mage::helper('revslider')->__('Duplicate Layer'),
            'onclick'   => 'revLayer.duplicateLayer()',
            'type'      => 'button',
            'class'     => 'layer-dup',
            'id'        => 'dupLayerBtn'
        ));
        $this->setChild('dupLayerBtn', $addLayerBtn);

        $addLayerBtn = $this->getLayout()->createBlock('adminhtml/widget_button',
            'previewSlideBtn', array(
            'label'     => Mage::helper('revslider')->__('Preview Slide'),
            'onclick'   => 'revLayer.previewSlide()',
            'type'      => 'button',
            'class'     => 'show-hide'
        ));
        $this->setChild('previewLayerBtn', $addLayerBtn);

        $editLayerBtn = $this->getLayout()->createBlock('adminhtml/widget_button',
            'editLayerBtn', array(
                'label'     => Mage::helper('revslider')->__('Edit Layer'),
                'onclick'   => 'revLayer.editLayer()',
                'type'      => 'button',
                'class'     => 'layer-edit',
                'id'        => 'editLayerBtn'
            ));
        $this->setChild('editLayerBtn', $editLayerBtn);

        return parent::_prepareLayout();
    }

    public function getDivLayersStyle(){
        $slider = Mage::registry('revslider');
        if ($slider->getId()){
            return sprintf('width:%dpx; height:%dpx;',
                $slider->getWidth() ? $slider->getWidth() : 900,
                $slider->getHeight() ? $slider->getHeight() : 300
            );
        }
    }

    public function getAddLayerImageUrl(){
        return Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index', array(
            'static_urls_allowed'   => 1,
            'onInsertCallback'      => 'revLayer.addLayerImage'
        ));
    }

    public function getAddLayerVideoUrl(){
        return Mage::getSingleton('adminhtml/url')->getUrl('revslideradmin/slider/video');
    }
}