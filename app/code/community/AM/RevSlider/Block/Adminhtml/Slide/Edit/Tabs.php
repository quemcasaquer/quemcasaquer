<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Slide_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs{
    public function __construct(){
        parent::__construct();
        $this->setId('slides_tabs');
        $this->setDestElementId('edit_form');
        $slider = Mage::registry('revslider');
        if ($slider->getId()){
            $this->setTitle(Mage::helper('revslider')->__('%s\'s Slides', $slider->getTitle()));
        }else{
            $this->setTitle(Mage::helper('revslider')->__('Revolution Slider Slides'));
        }
    }

    public function _prepareLayout(){
        $slider = Mage::registry('revslider');
        $slide = Mage::registry('revslide');
        $slides = $slider->getAllSlides();
        foreach ($slides as $item){
            if ($item->getId() == $slide->getId()){
                $this->addTab('slide_section_'.$item->getId(), array(
                    'label'     => $item->getTitle() ? $item->getTitle() : "ID: {$item->getId()}",
                    'content'   => $this->getLayout()->createBlock('revslider/adminhtml_slide_edit_tab_main')->toHtml()
                ));
                $this->_activeTab = 'slide_section_'.$item->getId();
            }else{
                $this->addTab('slide_section_'.$item->getId(), array(
                    'label'     => $item->getTitle() ? $item->getTitle() : "ID: {$item->getId()}",
                    'url'   => $this->getUrl('*/*/addSlide', array('sid' => $slider->getId(), 'id' => $item->getId())),
                ));
            }
        }
        if (!$slide->getId()){
            $this->addTab('slide_section_new', array(
                'label'     => Mage::helper('revslider')->__('New Slide'),
                'content'   => $this->getLayout()->createBlock('revslider/adminhtml_slide_edit_tab_main')->toHtml()
            ));
            $this->_activeTab = 'slide_section_new';
        }else{
            $this->addTab('slide_section_new', array(
                'label' => Mage::helper('revslider')->__('New Slide'),
                'url'   => $this->getUrl('*/*/addSlide', array('sid' => $slider->getId())),
            ));
        }
        return parent::_prepareLayout();
    }
}