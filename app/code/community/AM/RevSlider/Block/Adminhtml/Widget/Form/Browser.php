<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Block_Adminhtml_Widget_Form_Browser extends AM_Extensions_Block_Adminhtml_Widget_Form_Element_Browser{
    protected function _prepareLayout(){
        parent::_prepareLayout();
        $browserBtn = $this->getLayout()->createBlock('adminhtml/widget_button', 'button',  array(
            'label'     => '...',
            'title'     => Mage::helper('amext')->__('Click to browser media'),
            'type'      => 'button',
            'onclick'   => sprintf('AM.MediabrowserUtility.openDialog(\'%s\', \'browserVideoWindow\', null, null, \'%s\')',
                Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index', array(
                    'static_urls_allowed'   => 1,
                    'target_element_id'     => $this->getElement()->getHtmlId(),
                    'type'                  => 'media',
                    'onInsertCallback'      => 'revLayer.onSelectHtml5Video',
                    'onInsertCallbackParams'=> 'browserVideoWindow'
                )),
                Mage::helper('revslider')->__('Select Video')
            )
        ));
        $this->setChild('browserBtn', $browserBtn);
        return $this;
    }
}