<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Slider_Css extends Mage_Adminhtml_Block_Template{
    public function _construct(){
        parent::_construct();
        $this->setTemplate('am/revslider/css.phtml');
    }

    public function _prepareLayout(){
        $this->setChild('save', $this->getLayout()->createBlock('adminhtml/widget_button', '', array(
            'label'     => Mage::helper('revslider')->__('Save'),
            'type'      => 'button',
            'class'     => 'save',
            'id'        => 'btnCssSave',
            'onclick'   => "revLayer.saveCss('{$this->getUrl('*/*/saveCss')}', 'editCssWindow');"
        )));
        $this->setChild('reset', $this->getLayout()->createBlock('adminhtml/widget_button', '', array(
            'label'     => Mage::helper('revslider')->__('Reset to Default'),
            'type'      => 'button',
            'class'     => 'default',
            'onclick'   => sprintf('revLayer.resetCss(\'%s\');',
                $this->getUrl('revslider/index/getCssCaptions', array('reset' => 1))
            )
        )));
        return parent::_prepareLayout();
    }

    public function getCssContent(){
        $css = Mage::getStoreConfig('revslider/config/css');
        if (!$css){
            $file = Mage::getBaseDir().'/js/am/revslider/rs-plugin/css/captions.css';
            if (is_file($file)) $css = file_get_contents($file);
        }
        return $css;
    }
}