<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_SliderController extends Mage_Adminhtml_Controller_Action{
    public function _initAction(){
        $this->loadLayout()->_setActiveMenu('revslider/slider');
        $this->_title(Mage::helper('revslider')->__('AM'));
        return $this;
    }

    public function indexAction(){
        $this->_initAction();
        $this->_title(Mage::helper('revslider')->__('Manage Slider'));
        $this->renderLayout();
    }

    public function newAction(){
        $this->_forward('edit');
    }

    public function editAction(){
        $model = Mage::getModel('revslider/slider');
        $id = $this->getRequest()->getParam('id', null);
        if (is_numeric($id)) $model->load($id);
        Mage::register('revslider', $model);
        $this->_initAction();
        Mage::helper('amext')->loadJsLibs(array('jscolor', 'browser'));
        $this->_title(Mage::helper('revslider')->__('Manage Slider'));
        if ($model->getId()) $this->_title($model->getTitle());
        else $this->_title(Mage::helper('revslider')->__('New Slider'));
        $this->renderLayout();
    }

    public function saveAction(){
        if ($data = $this->getRequest()->getPost()){
            $model = Mage::getModel('revslider/slider');
            if (isset($data['form_key'])) unset($data['form_key']);
            $model->setTitle($data['title']);
            $model->setStatus($data['status']);
            //$model->setAlias($data['alias']);
            //$model->setStoreIds(implode(',', $data['store_ids']));
            $model->setParams(Mage::helper('core')->jsonEncode($data));
            if (isset($data['slider_id']) && is_numeric($data['slider_id'])){
                $model->setId($data['slider_id']);
            }

            try{
                $model->save();
                Mage::app()->getCache()->clean('matchingTag', array(AM_RevSlider_Model_Slider::CACHE_TAGS));
                $this->_getSession()->addSuccess(
                    $this->__('"%s" saved successfully', $model->getTitle())
                );
                $back = $this->getRequest()->getParam('back');
                if ($back == 'edit'){
                    $this->_redirect('*/*/edit', array(
                        'id'        => $model->getId(),
                        'activeTab' => $this->getRequest()->getParam('activeTab')
                    ));
                }
                else $this->_redirect('*/*/index');
            }catch (Exception $e){}
        }
    }

    public function deleteAction(){
        $id = $this->getRequest()->getParam('id');
        if (is_numeric($id)){
            $model = Mage::getModel('revslider/slider')->load($id);
            if ($model->getId()){
               try{
                   foreach ($model->getAllSlides() as $slide){
                       $slide->delete();
                   }
                   $model->delete();
                   Mage::app()->getCache()->clean('matchingTag', array(AM_RevSlider_Model_Slider::CACHE_TAGS));
                   $this->_getSession()->addSuccess(
                       $this->__('"%s" deleted successfully', $model->getTitle())
                   );
               }catch (Exception $e){}
            }
        }
        $this->_redirect('*/*/index');
    }

    public function slideAction(){
        $this->_initAction();
        $this->renderLayout();
    }

    public function slideGridAction(){
        $this->_initAction();
        $this->renderLayout();
    }

    public function addSlideAction(){
        $slider     = Mage::getModel('revslider/slider');
        $slide      = Mage::getModel('revslider/slide');
        $sliderId   = $this->getRequest()->getParam('sid', null);
        $slideId    = $this->getRequest()->getParam('id', null);
        if (is_numeric($sliderId)) $slider->load($sliderId);
        if (is_numeric($slideId)) $slide->load($slideId);
        Mage::register('revslider', $slider);
        Mage::register('revslide', $slide);
        $this->_initAction();
        Mage::helper('amext')->loadJsLibs(array('jscolor', 'codemirror', 'browser'));
        $this->getLayout()->getBlock('head')->addItem(
            'link_rel',
            Mage::helper('revslider')->getCssFromController('revslider/index/getCssCaptions'),
            'type="text/css" rel="stylesheet"'
        );
        $this->_title(Mage::helper('revslider')->__('Manage Slider'));
        if ($slide->getId()) $this->_title($slide->getTitle());
        else $this->_title(Mage::helper('revslider')->__('New Slide'));
        $this->renderLayout();
    }

    public function saveSlideAction(){
        if ($data = $this->getRequest()->getPost()){
            if ($data['form_key']) unset($data['form_key']);
            if ($data['layers']){
                $layers = $data['layers'];
                $arrayLayers = Mage::helper('core')->jsonDecode($layers);

                // using for sorting layer order
                function order_sort($a, $b){
                    if (!isset($a['order']) || !isset($b['order'])) return 0;
                    return $a['order'] - $b['order'];
                }

                usort($arrayLayers, 'order_sort');
                unset($data['layers']);
                $model = Mage::getModel('revslider/slide');
                $model->setSliderId($data['slider_id']);
                if (isset($data['fullwidth_centering'])){
                    $data['fullwidth_centering'] = true;
                }
                $model->setParams(Mage::helper('core')->jsonEncode($data));
                $model->setLayers(Mage::helper('core')->jsonEncode($arrayLayers));
                if (isset($data['id']) && is_numeric($data['id'])){
                    $model->setId($data['id']);
                }else{
                    $numSlides = Mage::getModel('revslider/slider')->getSlideCount($data['slider_id']);
                    $model->setSlideOrder($numSlides + 1);
                }

                try{
                    $model->save();
                    Mage::app()->getCache()->clean('matchingTag', array(AM_RevSlider_Model_Slider::CACHE_TAGS));
                    $url = $this->getUrl('*/*/edit', array(
                        'id'        => $data['slider_id'],
                        'activeTab' => 'slide_section'
                    ));
                    $this->getResponse()->setBody($url);
                }catch (Exception $e){}
            }
        }
    }

    public function videoAction(){
        $this->loadLayout('overlay_popup');
        $this->renderLayout();
    }

    public function cssAction(){
        $id = $this->getRequest()->getParam('style', null);
        $model = Mage::getModel('revslider/css')->load($id);
        Mage::register('css', $model);
        $this->loadLayout('overlay_popup');
        $this->renderLayout();
    }

    public function animationAction(){
        $this->loadLayout('overlay_popup');
        $aid = $this->getRequest()->getParam('aid');
        $model = Mage::getModel('revslider/animation');
        if (strpos($aid, 'custom') === 0){
            $part = explode('-', $aid);
            $model->load($part[1]);
            $model->setAnimSpeed(500);
        }
        Mage::register('animation', $model);
        $this->renderLayout();
    }

    public function saveAnimationAction(){
        if ($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            if (isset($data['form_key'])) unset($data['form_key']);
            $model = Mage::getModel('revslider/animation');
            if (isset($data['id']) && $data['id']){
                $model->load($data['id']);
            }
            if (isset($data['name']) && $data['name']){
                $model->setName($data['name']);
                unset($data['name']);
                $model->setParams(Mage::helper('core')->jsonEncode($data));
                try{
                    $model->save();
                    $out['success'] = 1;
                    $out['data'] = array('id' => $model->getId(), 'name' => $model->getName(), 'params' => Mage::helper('core')->jsonDecode($model->getParams()));
                    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($out));
                }catch (Exception $e){

                }
            }
        }
    }

    public function deleteAnimationAction(){
        if ($this->getRequest()->isPost()){
            $id = $this->getRequest()->getParam('id');
            $model = Mage::getModel('revslider/animation')->load($id);
            if ($model->getId()){
                try{
                    $model->delete();
                    $out = array('success' => 1, 'id' => $id);
                    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($out));
                }catch (Exception $e){

                }
            }
        }
    }

    public function deleteCssAction(){
        if ($this->getRequest()->isPost()){
            $id = $this->getRequest()->getParam('id');
            $model = Mage::getModel('revslider/css')->load($id);
            if ($model->getId()){
                try{
                    $model->delete();
                    $this->getResponse()->setBody(
                        Mage::helper('core')->jsonEncode(array('success' => 1))
                    );
                }catch (Exception $e){
                    $this->getResponse()->setBody(
                        Mage::helper('core')->jsonEncode(array('error' => 1))
                    );
                }
            }
        }
    }

    public function saveCssAction(){
        if ($this->getRequest()->isPost()){
            $helper = Mage::helper('core');
            $data = $this->getRequest()->getPost();
            if (isset($data['form_key'])) unset($data['form_key']);
            if (isset($data['id'])){
                $model = Mage::getModel('revslider/css')->load($data['id']);
                if ($model->getId()){
                    try{
                        unset($data['id']);
                        if (isset($data['hover'])){
                            $hover = array();
                            $model->setSettings($helper->jsonEncode(array('hover' => 1)));
                            foreach ($data as $k => $v){
                                if (strpos($k, '__') === 0){
                                    $hover[str_replace('__', '', $k)] = $v;
                                    unset($data[$k]);
                                }
                            }
                            $model->setHover($helper->jsonEncode($hover));
                            unset($data['hover']);
                        }else $model->setSettings(null);
                        $model->setParams($helper->jsonEncode($data));
                        $model->save();
                        $this->getResponse()->setBody(
                            $helper->jsonEncode(array('success' => 1))
                        );
                    }catch (Exception $e){
                        $this->getResponse()->setBody(
                            $helper->jsonEncode(array('error' => 1))
                        );
                    }
                }
            }elseif (isset($data['name'])){
                $model = Mage::getModel('revslider/css');
                $model->setHandle('.tp-caption.' . $data['name']);
                unset($data['name']);
                try{
                    if (isset($data['hover'])){
                        $hover = array();
                        $model->setSettings($helper->jsonEncode(array('hover' => 1)));
                        foreach ($data as $k => $v){
                            if (strpos($k, '__') === 0){
                                $hover[str_replace('__', '', $k)] = $v;
                                unset($data[$k]);
                            }
                        }
                        $model->setHover($helper->jsonEncode($hover));
                        unset($data['hover']);
                    }else $model->setSettings(null);
                    $model->setParams($helper->jsonEncode($data));
                    $model->save();
                    $this->getResponse()->setBody(
                        $helper->jsonEncode(array('success' => 1, 'id' => $model->getId()))
                    );
                }catch (Exception $e){
                    $this->getResponse()->setBody(
                        $helper->jsonEncode(array('error' => 1))
                    );
                }
            }
        }
    }

    public function deleteSlideAction(){
        $id = $this->getRequest()->getParam('id');
        $sid = $this->getRequest()->getParam('sid');
        $model = Mage::getModel('revslider/slide');
        if (is_numeric($id)) $model->load($id);
        if ($model->getId()){
            $model->delete();
            Mage::app()->getCache()->clean('matchingTag', array(AM_RevSlider_Model_Slider::CACHE_TAGS));
        }
        $this->_redirect('*/*/edit', array(
            'id' => $sid,
            'activeTab' => 'slide_section'
        ));
    }

    public function ajaxSaveAction(){
        if ($data = $this->getRequest()->getPost()){
            $id = isset($data['entity']) ? (int)$data['entity'] : null;
            $attr = isset($data['attr']) ? $data['attr'] : null;
            $value = isset($data['value']) ? (int)$data['value'] : null;
            $out = array(
                'message' => '',
                'value' => $value
            );
            switch($attr){
                case 'slide_order':
                    $model = Mage::getModel('revslider/slide')->load($id);
                    if ($model->getId()){
                        $model->setData($attr, $value);
                        $model->save();
                        Mage::app()->getCache()->clean('matchingTag', array(AM_RevSlider_Model_Slider::CACHE_TAGS));
                    }else{
                        $out['message'] = Mage::helper('revslider')->__('Slide not avaiable');
                    }
            }
            $this->getResponse()->setBody(json_encode($out));
        }
    }

    public function importAction(){
        $this->loadLayout();
        $this->renderLayout();
    }

    public function importPostAction(){
        if ($this->getRequest()->isPost()){
            try{
                $uploader = new Varien_File_Uploader('file');
                $uploader->setAllowedExtensions(array('rs'));
                $uploader->setAllowCreateFolders(true);
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $uploader->save('var/slider/');

                $file = $uploader->getUploadedFileName();
                $path = Mage::getBaseDir('var') . DS . 'slider' . DS . $file;

                $data = unserialize(file_get_contents($path));
                if (!isset($data['title']) && !isset($data['layout'])){
                    throw new Exception(Mage::helper('revslider')->__('Data invalid.'));
                }

                $json = Mage::helper('core');

                $model = Mage::getModel('revslider/slider');
                $model->setData(array(
                    'title'     => $data['title'],
                    'status'    => 1,
                    'params'    => $json->jsonEncode($data)
                ));
                $model->save();
                if (isset($data['slides']) && is_array($data['slides'])){
                    foreach ($data['slides'] as $index => $slideData){
                        $layers = isset($slideData['layers']) ? $slideData['layers'] : array();
                        if (isset($slideData['layers'])) unset($slideData['layers']);

                        $slide = Mage::getModel('revslider/slide');
                        $slide->setData(array(
                            'slider_id'     => $model->getId(),
                            'slide_order'   => $index + 1,
                            'params'        => $json->jsonEncode($slideData),
                            'layers'        => $json->jsonEncode($layers)
                        ));
                        $slide->save();
                    }
                }

                $this->_getSession()->addSuccess(Mage::helper('revslider')->__('Slider import successfully.'));
            }catch (Exception $e){
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/import');
    }

    public function exportAction(){
        $id = $this->getRequest()->getParam('id');
        $slider = Mage::getModel('revslider/slider')->load($id);
        if ($slider->getId()){
            $data = $slider->getData();
            $slides = $slider->getAllSlides();
            foreach ($slides as $slide){
                $slideData = $slide->getData();
                if (isset($slideData['id'])) unset($slideData['id']);
                if (isset($slideData['slider_id'])) unset($slideData['slider_id']);
                $data['slides'][] = $slideData;
            }
            if (isset($data['slider_id'])) unset($data['slider_id']);
            if (isset($data['id'])) unset($data['id']);

            $date = Mage::getModel('core/date');
            $file = sprintf('am-revslider-%d-%s.rs', $slider->getId(), $date->date('YmdHis'));

            $this->getResponse()->setHeader('Content-Type', 'application/txt');
            $this->getResponse()->setHeader('Content-Disposition', "attachment; filename=\"{$file}\"");
            $this->getResponse()->setBody(serialize($data)."\r\n");
        }
    }
}