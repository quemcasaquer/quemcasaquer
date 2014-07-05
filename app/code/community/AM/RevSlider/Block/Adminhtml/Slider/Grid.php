<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Slider_Grid extends Mage_Adminhtml_Block_Widget_Grid{
    protected function _prepareCollection(){
        $collection = Mage::getModel('revslider/slider')->getCollection();
        //$collection->setFirstStoreFlag(true);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns(){
        $this->addColumn('id', array(
            'header'    => Mage::helper('revslider')->__('ID'),
            'index'     => 'id',
            'sortable'  => true
        ));
        $this->addColumn('title', array(
            'header'    => Mage::helper('revslider')->__('Title'),
            'index'     => 'title',
            'sortable'  => true
        ));
        $this->addColumn('status', array(
            'header'    => Mage::helper('revslider')->__('Status'),
            'index'     => 'status',
            'type'      => 'options',
            'options'   => Mage::getModel('revslider/slider')->getStatuses()
        ));
        $this->addColumn('preview', array(
            'header'    => Mage::helper('revslider')->__('Preview'),
            'type'      => 'action',
            'getter'    => 'getId',
            'width'     => '80px',
            'actions'   => array(
                array(
                    'caption' => Mage::helper('revslider')->__('Preview'),
                    'field' => 'id',
                    'popup' => true,
                    'url' => array(
                        'base' => 'revslider/index/preview'
                    )
                )
            ),
            'filter'    => false,
            'sortable'  => false
        ));
        $this->addColumn('export', array(
            'header'    => Mage::helper('revslider')->__('Export'),
            'type'      => 'action',
            'getter'    => 'getId',
            'width'     => '80px',
            'actions'   => array(
                array(
                    'caption' => Mage::helper('revslider')->__('Export'),
                    'field' => 'id',
                    'url' => array(
                        'base' => 'revslideradmin/slider/export'
                    )
                )
            ),
            'filter'    => false,
            'sortable'  => false
        ));
        return parent::_prepareColumns();
    }

    public function getRowUrl($row){
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}