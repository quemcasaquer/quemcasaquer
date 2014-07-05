<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Slider_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{
    public function __construct(){
        $this->_blockGroup  = 'revslider';
        $this->_controller  = 'adminhtml_slider';
        $this->_form        = 'edit';
        parent::__construct();
        $slider = Mage::registry('revslider');
        $previewUrl = Mage::helper('revslider')->getCssFromController('revslider/index/preview', array(
            'id' => $slider->getId()
        ));
        if ($slider->getId()){
            $this->_addButton('preview', array(
                'label'     => Mage::helper('revslider')->__('Preview'),
                'title'     => Mage::helper('revslider')->__('Preview Slider'),
                'class'     => 'show-hide',
                'onclick'   => "popWin('$previewUrl')"
            ));
        }
        $exportUrl = $this->getUrl('*/*/export', array(
            'id' => $slider->getId()
        ));
        $this->_addButton('export', array(
            'label'     => Mage::helper('revslider')->__('Export'),
            'onclick'   => "setLocation('{$exportUrl}')"
        ));
        $sacUrl = $this->getUrl('*/*/save', array(
            'back'      => 'edit',
            'activeTab' => '{{tab_id}}'
        ));
        $this->_addButton('sac', array(
            'label'     => Mage::helper('revslider')->__('Save and Continue Edit'),
            'class'     => 'save',
            'onclick'   => "saveAndContinueEdit('{$sacUrl}');"
        ));
        $this->_formScripts[] = '
        function saveAndContinueEdit(urlTemplate) {
            var template = new Template(urlTemplate, /(^|.|\r|\n)({{(\w+)}})/);
            var url = template.evaluate({tab_id:sliderJsTabs.activeTab.name});
            editForm.submit(url);
        }
        ';
    }

    public function getHeaderText(){
        $model = Mage::registry('revslider');
        if ($model->getId()) {
            return Mage::helper('revslider')->__("Edit Slider '%s'", $this->escapeHtml($model->getTitle()));
        } else {
            return Mage::helper('revslider')->__('New Slider');
        }
    }
}