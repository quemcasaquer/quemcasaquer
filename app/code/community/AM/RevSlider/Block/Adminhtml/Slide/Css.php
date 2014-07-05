<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Slide_Css extends Mage_Adminhtml_Block_Widget_Form_Container{
    public function __construct(){
        $this->_blockGroup      = 'revslider';
        $this->_controller      = 'adminhtml_slide';
        $this->_mode            = 'css';

        parent::__construct();

        $model = Mage::registry('css');

        $this->setId('editCssForm');
        $this->_headerText      = $model->getId() ? Mage::helper('revslider')->__('Edit Style "%s"', $model->getPrettyName()) : Mage::helper('revslider')->__('Edit Style');

        $this->removeButton('back');
        $this->removeButton('reset');
        $this->_updateButton('save', 'label', Mage::helper('revslider')->__('Save'));
        $this->_updateButton('save', 'id', 'btnCssSave');
        $windowId = $this->getRequest()->getParam('windowId');
        $this->_updateButton('save', 'onclick', "revLayer.saveLayerCss('{$windowId}', '{$model->getId()}')");
        $this->_addButton('delete', array(
            'label' => Mage::helper('revslider')->__('Delete'),
            'class' => 'delete',
            'onclick' => "revLayer.deleteLayerCss('{$windowId}', '{$model->getId()}')"
        ));
        $this->_addButton('saveas', array(
            'id'    => 'btnCssSaveAs',
            'label' => Mage::helper('revslider')->__('Save as'),
            'class' => 'save',
            'onclick' => "revLayer.saveAsLayerCss('{$windowId}')"
        ));

        if ($model->getId()){
            $this->_formScripts[] = sprintf("revLayer.assignCssForm(%s)", Mage::helper('core')->jsonEncode($model->getStyle()));
        }

        $this->_formScripts[] = "setTimeout(function(){jscolor.init();});";
    }
}