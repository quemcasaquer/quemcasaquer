<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Slider_Edit_Tab_Slide extends Mage_Adminhtml_Block_Widget_Grid{
    protected $_slider;

    public function __construct(){
        parent::__construct();
        $this->setId('slide_grid');
        $this->setDefaultSort('slide_order');
        $this->setDefaultDir('asc');
        $this->setUseAjax(true);
    }

    protected function _getSlider(){
        if (!$this->_slider){
            $slider = Mage::getModel('revslider/slider');
            $id = $this->getRequest()->getParam('id', null);
            if (is_numeric($id)){
                $slider->load($id);
            }
            $this->_slider = $slider;
        }
        return $this->_slider;
    }

    protected function _prepareCollection(){
        $slider = $this->_getSlider();
        $collection = Mage::getModel('revslider/slide')
            ->getCollection()
            ->addSliderFilter($slider && $slider->getId() ? $slider : 0);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns(){
        $this->addColumn('id', array(
            'header'    => Mage::helper('revslider')->__('ID'),
            'index'     => 'id',
            'width'     => '100px'
        ));
        $this->addColumn('slide_thumb', array(
            'header'    => Mage::helper('revslider')->__('Thumb'),
            'filter'    => false,
            'sortable'  => false,
            'width'     => '110px',
            'renderer'  => 'revslider/adminhtml_widget_grid_column_renderer_slide_thumb'
        ));
        $this->addColumn('slide_title', array(
            'header'    => Mage::helper('revslider')->__('Title'),
            'filter'    => false,
            'sortable'  => false,
            'renderer'  => 'revslider/adminhtml_widget_grid_column_renderer_slide_title'
        ));
        $this->addColumn('slide_order', array(
            'header'    => Mage::helper('revslider')->__('Order'),
            'index'     => 'slide_order',
            'width'     => '300px',
            'filter'    => false,
            'renderer'  => 'amext/adminhtml_widget_grid_column_renderer_text'
        ));
        $this->addColumn('edit', array(
            'header'    => Mage::helper('revslider')->__('Action'),
            'type'      => 'action',
            'getter'    => 'getId',
            'width'     => '80px',
            'actions'   => array(
                array(
                    'caption' => Mage::helper('revslider')->__('Edit'),
                    'field' => 'id',
                    'url' => array(
                        'base' => '*/*/addSlide',
                        'params' => array(
                            'sid' => $this->_slider->getId()
                        )
                    )
                )
            ),
            'filter'    => false,
            'sortable'  => false
        ));
        $this->addColumn('delete', array(
            'header'    => Mage::helper('revslider')->__('Delete'),
            'type'      => 'action',
            'getter'    => 'getId',
            'width'     => '80px',
            'actions'   => array(
                array(
                    'caption' => Mage::helper('revslider')->__('Delete'),
                    'field' => 'id',
                    'confirm' => Mage::helper('revslider')->__('Do you realy want to delete this slide?'),
                    'url' => array(
                        'base' => '*/*/deleteSlide',
                        'params' => array(
                            'sid' => $this->_slider->getId(),
                            'activeTab' => 'slide_section'
                        )
                    )
                )
            ),
            'filter'    => false,
            'sortable'  => false
        ));
    }

    public function getGridUrl(){
        return $this->getUrl('*/*/slideGrid', array('_current' => true));
    }

    public function getRowUrl($item){
        return null;
    }

    protected function _prepareLayout(){
        $slider = $this->_getSlider();
        if ($slider && $slider->getId()){
            $url = $this->getUrl('*/*/addSlide', array('sid' => $slider->getId()));
            $this->setChild('addSlideButton',
                $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
                    'label'     => Mage::helper('revslider')->__('Add Slide'),
                    'onclick'   => "setLocation('$url')",
                    'class'     => 'scale add'
                ))
            );
        }
        return parent::_prepareLayout();
    }

    public function getAddSlideButtonHtml(){
        return $this->getChildHtml('addSlideButton');
    }

    public function getMainButtonsHtml(){
        $buttons = parent::getMainButtonsHtml();
        $buttons .= $this->getAddSlideButtonHtml();
        return $buttons;
    }
}