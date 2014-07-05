<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Model_Slider extends Mage_Core_Model_Abstract{
    const CACHE_KEY_PREFIX  = 'AM_REVSLIDER_';
    const CACHE_TAGS        = 'AM_REVSLIDER';

    const STATUS_DISABLED   = 0;
    const STATUS_ENABLED    = 1;

    const LAYOUT_FIXED      = 'fixed';
    const LAYOUT_CUSTOM     = 'responsitive';
    const LAYOUT_AUTO       = 'fullwidth';
    const LAYOUT_FULL       = 'fullscreen';

    const OPTION_ON         = 'on';
    const OPTION_OFF        = 'off';

    const OPTION_YES        = 'true';
    const OPTION_NO         = 'false';

    const OPTION_LEFT       = 'left';
    const OPTION_CENTER     = 'center';
    const OPTION_RIGHT      = 'right';
    const OPTION_TOP        = 'top';
    const OPTION_BOTTOM     = 'bottom';

    const SHADOW_TYPE_0     = 0;
    const SHADOW_TYPE_1     = 1;
    const SHADOW_TYPE_2     = 2;
    const SHADOW_TYPE_3     = 3;

    const SHADOW_LINE_TOP       = 'top';
    const SHADOW_LINE_BOTTOM    = 'bottom';
    const SHADOW_LINE_HIDE      = 'hide';

    const NAVIGATION_TYPE_NONE      = 'none';
    const NAVIGATION_TYPE_BULLET    = 'bullet';
    const NAVIGATION_TYPE_THUMB     = 'thumb';
    const NAVIGATION_TYPE_BOTH      = 'both';

    const NAVIGATION_ARROWS_BULLET   = 'nexttobullets';
    const NAVIGATION_ARROWS_SOLO     = 'solo';
    const NAVIGATION_ARROWS_NONE     = 'none';

    const NAVIGATION_STYLE_ROUND        = 'round';
    const NAVIGATION_STYLE_NAVBAR       = 'navbar';
    const NAVIGATION_STYLE_ROUND_OLD    = 'round-old';
    const NAVIGATION_STYLE_SQUARE_OLD   = 'square-old';
    const NAVIGATION_STYLE_NAVBAR_OLD   = 'navbar-old';

    const TRANSITION_1      = 'random-static';
    const TRANSITION_2      = 'random-premium';
    const TRANSITION_3      = 'random';
    const TRANSITION_4      = 'slideup';
    const TRANSITION_5      = 'slidedown';
    const TRANSITION_6      = 'slideright';
    const TRANSITION_7      = 'slideleft';
    const TRANSITION_8      = 'slidehorizontal';
    const TRANSITION_9      = 'slidevertical';
    const TRANSITION_10     = 'boxslide';
    const TRANSITION_11     = 'slotslide-horizontal';
    const TRANSITION_12     = 'slotslide-vertical';
    const TRANSITION_13     = 'notransition';
    const TRANSITION_14     = 'fade';
    const TRANSITION_15     = 'boxfade';
    const TRANSITION_16     = 'slotfade-horizontal';
    const TRANSITION_17     = 'slotfade-vertical';
    const TRANSITION_18     = 'fadefromright';
    const TRANSITION_19     = 'fadefromleft';
    const TRANSITION_20     = 'fadefromtop';
    const TRANSITION_21     = 'fadefrombottom';
    const TRANSITION_22     = 'fadetoleftfadefromright';
    const TRANSITION_23     = 'fadetorightfadefromleft';
    const TRANSITION_24     = 'fadetotopfadefrombottom';
    const TRANSITION_25     = 'fadetobottomfadefromtop';
    const TRANSITION_26     = 'parallaxtoright';
    const TRANSITION_27     = 'parallaxtoleft';
    const TRANSITION_28     = 'parallaxtotop';
    const TRANSITION_29     = 'parallaxtobottom';
    const TRANSITION_30     = 'scaledownfromright';
    const TRANSITION_31     = 'scaledownfromleft';
    const TRANSITION_32     = 'scaledownfromtop';
    const TRANSITION_33     = 'scaledownfrombottom';
    const TRANSITION_34     = 'zoomout';
    const TRANSITION_35     = 'zoomin';
    const TRANSITION_36     = 'slotzoom-horizontal';
    const TRANSITION_37     = 'slotzoom-vertical';
    const TRANSITION_38     = 'curtain-1';
    const TRANSITION_39     = 'curtain-2';
    const TRANSITION_40     = 'curtain-3';
    const TRANSITION_41     = '3dcurtain-horizontal';
    const TRANSITION_42     = '3dcurtain-vertical';
    const TRANSITION_43     = 'cube';
    const TRANSITION_44     = 'cube-horizontal';
    const TRANSITION_45     = 'incube';
    const TRANSITION_46     = 'incube-horizontal';
    const TRANSITION_47     = 'turnoff';
    const TRANSITION_48     = 'turnoff-vertical';
    const TRANSITION_49     = 'papercut';
    const TRANSITION_50     = 'flyin';

    const BG_FIT_COVER          = 'cover';
    const BG_FIT_CONTAIN        = 'contain';
    const BG_FIT_NORMAL         = 'normal';

    const BG_REPEAT_NO          = 'no-repeat';
    const BG_REPEAT_XY          = 'repeat';
    const BG_REPEAT_X           = 'repeat-x';
    const BG_REPEAT_Y           = 'repeat-y';

    const BG_POS_LT             = 'left top';
    const BG_POS_LC             = 'left center';
    const BG_POS_LB             = 'left bottom';
    const BG_POS_RT             = 'right top';
    const BG_POS_RC             = 'right center';
    const BG_POS_RB             = 'right bottom';
    const BG_POS_CT             = 'center top';
    const BG_POS_CR             = 'center right';
    const BG_POS_CB             = 'center bottom';
    const BG_POS_CC             = 'center';

    const BG_DOTTED_1           = 'none';
    const BG_DOTTED_2           = 'twoxtwo';
    const BG_DOTTED_3           = 'twoxtwowhite';
    const BG_DOTTED_4           = 'threexthree';
    const BG_DOTTED_5           = 'threexthreewhite';

    public function _construct(){
        parent::_construct();
        $this->_init('revslider/slider');
    }

    public function getStatuses(){
        $statuses = new Varien_Object(array(
            self::STATUS_ENABLED    => Mage::helper('revslider')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('revslider')->__('Disabled')
        ));
        return $statuses->getData();
    }

    public function getLayouts(){
        $layouts = new Varien_Object(array(
            self::LAYOUT_FIXED  => Mage::helper('revslider')->__('Fixed'),
            self::LAYOUT_CUSTOM => Mage::helper('revslider')->__('Custom'),
            self::LAYOUT_AUTO   => Mage::helper('revslider')->__('Auto Responsive'),
            self::LAYOUT_FULL   => Mage::helper('revslider')->__('Full Screen')
        ));
        return $layouts->getData();
    }

    public function getOnOffOptions(){
        $options = new Varien_Object(array(
            self::OPTION_OFF   => Mage::helper('revslider')->__('Off'),
            self::OPTION_ON    => Mage::helper('revslider')->__('On')
        ));
        return $options->getData();
    }

    public function getYesNoOptions(){
        $options = new Varien_Object(array(
            self::OPTION_NO    => Mage::helper('revslider')->__('No'),
            self::OPTION_YES   => Mage::helper('revslider')->__('Yes')
        ));
        return $options->getData();
    }

    public function getLCROptions(){
        $options = new Varien_Object(array(
            self::OPTION_LEFT      => Mage::helper('revslider')->__('Left'),
            self::OPTION_CENTER    => Mage::helper('revslider')->__('Center'),
            self::OPTION_RIGHT     => Mage::helper('revslider')->__('Right')
        ));
        return $options->getData();
    }

    public function getTCBOptions(){
        $options = new Varien_Object(array(
            self::OPTION_TOP      => Mage::helper('revslider')->__('Top'),
            self::OPTION_CENTER   => Mage::helper('revslider')->__('Center'),
            self::OPTION_BOTTOM   => Mage::helper('revslider')->__('Bottom')
        ));
        return $options->getData();
    }

    public function getShadowOptions(){
        $options = new Varien_Object(array(
            self::SHADOW_TYPE_0     => Mage::helper('revslider')->__('No Shadow'),
            self::SHADOW_TYPE_1     => Mage::helper('revslider')->__('1'),
            self::SHADOW_TYPE_2     => Mage::helper('revslider')->__('2'),
            self::SHADOW_TYPE_3     => Mage::helper('revslider')->__('3')
        ));
        return $options->getData();
    }

    public function getShadowLineOptions(){
        $options = new Varien_Object(array(
            self::SHADOW_LINE_TOP       => Mage::helper('revslider')->__('Top'),
            self::SHADOW_LINE_BOTTOM    => Mage::helper('revslider')->__('Bottom'),
            self::SHADOW_LINE_HIDE      => Mage::helper('revslider')->__('Hide')
        ));
        return $options->getData();
    }

    public function getNavigationTypeOptions(){
        $options = new Varien_Object(array(
            self::NAVIGATION_TYPE_NONE      => Mage::helper('revslider')->__('None'),
            self::NAVIGATION_TYPE_BULLET    => Mage::helper('revslider')->__('Bullet'),
            self::NAVIGATION_TYPE_THUMB     => Mage::helper('revslider')->__('Thumb'),
            self::NAVIGATION_TYPE_BOTH      => Mage::helper('revslider')->__('Both')
        ));
        return $options->getData();
    }

    public function getNavigationArrowsOptions(){
        $options = new Varien_Object(array(
            self::NAVIGATION_ARROWS_BULLET  => Mage::helper('revslider')->__('With Bullets'),
            self::NAVIGATION_ARROWS_SOLO    => Mage::helper('revslider')->__('Solo'),
            self::NAVIGATION_ARROWS_NONE    => Mage::helper('revslider')->__('None')
        ));
        return $options->getData();
    }

    public function getNavigationStyleOptions(){
        $options = new Varien_Object(array(
            self::NAVIGATION_STYLE_ROUND        => Mage::helper('revslider')->__('Round'),
            self::NAVIGATION_STYLE_NAVBAR       => Mage::helper('revslider')->__('Navbar'),
            self::NAVIGATION_STYLE_ROUND_OLD    => Mage::helper('revslider')->__('Old Round'),
            self::NAVIGATION_STYLE_SQUARE_OLD   => Mage::helper('revslider')->__('Old Square'),
            self::NAVIGATION_STYLE_NAVBAR_OLD   => Mage::helper('revslider')->__('Old Navbar')
        ));
        return $options->getData();
    }

    public function getLinkSlideOptions($exclude=array()){
        $options = new Varien_Object(array(
            'nothing'       => Mage::helper('revslider')->__('-- Not Chosen --'),
            'next'          => Mage::helper('revslider')->__('-- Next Slide --'),
            'prev'          => Mage::helper('revslider')->__('-- Previous Slide --')
        ));
        $slides = $this->getAllSlides(true);
        foreach ($slides as $k => $slide){
            if (!in_array($slide->getId(), $exclude)){
                $options->setData($k+1, $slide->getTitle() ? $slide->getTitle() : "ID: {$slide->getId()}");
            }
        }
        return $options->getData();
    }

    public function getTransistionOptions(){
        return array(
            array(
                'label' => Mage::helper('revslider')->__('Random'),
                'value' => array(
                    array('value' => self::TRANSITION_1, 'label' => Mage::helper('revslider')->__('Random Flat')),
                    array('value' => self::TRANSITION_2, 'label' => Mage::helper('revslider')->__('Random Premium')),
                    array('value' => self::TRANSITION_3, 'label' => Mage::helper('revslider')->__('Random Flat and Premium'))
                )
            ),
            array(
                'label' => Mage::helper('revslider')->__('Slide'),
                'value' => array(
                    array('value' => self::TRANSITION_4, 'label' => Mage::helper('revslider')->__('Slide To Top')),
                    array('value' => self::TRANSITION_5, 'label' => Mage::helper('revslider')->__('Slide To Bottom')),
                    array('value' => self::TRANSITION_6, 'label' => Mage::helper('revslider')->__('Slide To Right')),
                    array('value' => self::TRANSITION_7, 'label' => Mage::helper('revslider')->__('Slide To Left')),
                    array('value' => self::TRANSITION_8, 'label' => Mage::helper('revslider')->__('Slide Horizontal (depending on Next/Previous)')),
                    array('value' => self::TRANSITION_9, 'label' => Mage::helper('revslider')->__('Slide Vertical (depending on Next/Previous)')),
                    array('value' => self::TRANSITION_10, 'label' => Mage::helper('revslider')->__('Slide Boxes')),
                    array('value' => self::TRANSITION_11, 'label' => Mage::helper('revslider')->__('Slide Slots Horizontal')),
                    array('value' => self::TRANSITION_12, 'label' => Mage::helper('revslider')->__('Slide Slots Vertical'))
                )
            ),
            array(
                'label' => Mage::helper('revslider')->__('Fade'),
                'value' => array(
                    array('value' => self::TRANSITION_13, 'label' => Mage::helper('revslider')->__('No Transition')),
                    array('value' => self::TRANSITION_14, 'label' => Mage::helper('revslider')->__('Fade')),
                    array('value' => self::TRANSITION_15, 'label' => Mage::helper('revslider')->__('Fade Boxes')),
                    array('value' => self::TRANSITION_16, 'label' => Mage::helper('revslider')->__('Fade Slots Horizontal')),
                    array('value' => self::TRANSITION_17, 'label' => Mage::helper('revslider')->__('Fade Slots Vertical')),
                    array('value' => self::TRANSITION_18, 'label' => Mage::helper('revslider')->__('Fade and Slide from Right')),
                    array('value' => self::TRANSITION_19, 'label' => Mage::helper('revslider')->__('Fade and Slide from Left')),
                    array('value' => self::TRANSITION_20, 'label' => Mage::helper('revslider')->__('Fade and Slide from Top')),
                    array('value' => self::TRANSITION_21, 'label' => Mage::helper('revslider')->__('Fade and Slide from Bottom')),
                    array('value' => self::TRANSITION_22, 'label' => Mage::helper('revslider')->__('Fade To Left and Fade From Right')),
                    array('value' => self::TRANSITION_23, 'label' => Mage::helper('revslider')->__('Fade To Right and Fade From Left')),
                    array('value' => self::TRANSITION_24, 'label' => Mage::helper('revslider')->__('Fade To Top and Fade From Bottom')),
                    array('value' => self::TRANSITION_25, 'label' => Mage::helper('revslider')->__('Fade To Bottom and Fade From Top'))
                )
            ),
            array(
                'label' => Mage::helper('revslider')->__('Parallax'),
                'value' => array(
                    array('value' => self::TRANSITION_26, 'label' => Mage::helper('revslider')->__('Parallax to Right')),
                    array('value' => self::TRANSITION_27, 'label' => Mage::helper('revslider')->__('Parallax to Left')),
                    array('value' => self::TRANSITION_28, 'label' => Mage::helper('revslider')->__('Parallax to Top')),
                    array('value' => self::TRANSITION_29, 'label' => Mage::helper('revslider')->__('Parallax to Bottom'))
                )
            ),
            array(
                'label' => Mage::helper('revslider')->__('Zoom'),
                'value' => array(
                    array('value' => self::TRANSITION_30, 'label' => Mage::helper('revslider')->__('Zoom Out and Fade From Right')),
                    array('value' => self::TRANSITION_31, 'label' => Mage::helper('revslider')->__('Zoom Out and Fade From Left')),
                    array('value' => self::TRANSITION_32, 'label' => Mage::helper('revslider')->__('Zoom Out and Fade From Top')),
                    array('value' => self::TRANSITION_33, 'label' => Mage::helper('revslider')->__('Zoom Out and Fade From Bottom')),
                    array('value' => self::TRANSITION_34, 'label' => Mage::helper('revslider')->__('ZoomOut')),
                    array('value' => self::TRANSITION_35, 'label' => Mage::helper('revslider')->__('ZoomIn')),
                    array('value' => self::TRANSITION_36, 'label' => Mage::helper('revslider')->__('Zoom Slots Horizontal')),
                    array('value' => self::TRANSITION_37, 'label' => Mage::helper('revslider')->__('Zoom Slots Vertical'))
                )
            ),
            array(
                'label' => Mage::helper('revslider')->__('Curtain'),
                'value' => array(
                    array('value' => self::TRANSITION_38, 'label' => Mage::helper('revslider')->__('Curtain from Left')),
                    array('value' => self::TRANSITION_39, 'label' => Mage::helper('revslider')->__('Curtain from Right')),
                    array('value' => self::TRANSITION_40, 'label' => Mage::helper('revslider')->__('Curtain from Middle'))
                )
            ),
            array(
                'label' => Mage::helper('revslider')->__('Premium'),
                'value' => array(
                    array('value' => self::TRANSITION_41, 'label' => Mage::helper('revslider')->__('3D Curtain Horizontal')),
                    array('value' => self::TRANSITION_42, 'label' => Mage::helper('revslider')->__('3D Curtain Vertical')),
                    array('value' => self::TRANSITION_43, 'label' => Mage::helper('revslider')->__('Cube Vertical')),
                    array('value' => self::TRANSITION_44, 'label' => Mage::helper('revslider')->__('Cube Horizontal')),
                    array('value' => self::TRANSITION_45, 'label' => Mage::helper('revslider')->__('In Cube Vertical')),
                    array('value' => self::TRANSITION_46, 'label' => Mage::helper('revslider')->__('In Cube Horizontal')),
                    array('value' => self::TRANSITION_47, 'label' => Mage::helper('revslider')->__('TurnOff Horizontal')),
                    array('value' => self::TRANSITION_48, 'label' => Mage::helper('revslider')->__('TurnOff Vertical')),
                    array('value' => self::TRANSITION_49, 'label' => Mage::helper('revslider')->__('Paper Cut')),
                    array('value' => self::TRANSITION_50, 'label' => Mage::helper('revslider')->__('Fly In'))
                )
            ),
        );
    }

    public function getBackgroundSizeOptions(){
        $options = new Varien_Object(array(
            self::BG_FIT_COVER      => Mage::helper('revslider')->__('cover'),
            self::BG_FIT_CONTAIN    => Mage::helper('revslider')->__('contain'),
            self::BG_FIT_NORMAL     => Mage::helper('revslider')->__('normal')
        ));
        return $options->getData();
    }

    public function getBackgroundRepeatOptions(){
        $options = new Varien_Object(array(
            self::BG_REPEAT_NO      => Mage::helper('revslider')->__('no-repeat'),
            self::BG_REPEAT_XY      => Mage::helper('revslider')->__('repeat'),
            self::BG_REPEAT_X       => Mage::helper('revslider')->__('repeat-x'),
            self::BG_REPEAT_Y       => Mage::helper('revslider')->__('repeat-y')
        ));
        return $options->getData();
    }

    public function getBackgroundDottedOptions(){
        $options = new Varien_Object(array(
            self::BG_DOTTED_1      => Mage::helper('revslider')->__('none'),
            self::BG_DOTTED_2      => Mage::helper('revslider')->__('2 x 2 Black'),
            self::BG_DOTTED_3      => Mage::helper('revslider')->__('2 x 2 White'),
            self::BG_DOTTED_4      => Mage::helper('revslider')->__('3 x 3 Black'),
            self::BG_DOTTED_5      => Mage::helper('revslider')->__('3 x 3 White'),
        ));
        return $options->getData();
    }

    public function getBackgroundPositionOptions(){
        $options = new Varien_Object(array(
            self::BG_POS_LT      => Mage::helper('revslider')->__('left top'),
            self::BG_POS_LC      => Mage::helper('revslider')->__('left center'),
            self::BG_POS_LB      => Mage::helper('revslider')->__('left bottom'),
            self::BG_POS_RT      => Mage::helper('revslider')->__('right top'),
            self::BG_POS_RC      => Mage::helper('revslider')->__('right center'),
            self::BG_POS_RB      => Mage::helper('revslider')->__('right bottom'),
            self::BG_POS_CT      => Mage::helper('revslider')->__('center top'),
            self::BG_POS_CR      => Mage::helper('revslider')->__('center right'),
            self::BG_POS_CB      => Mage::helper('revslider')->__('center bottom'),
            self::BG_POS_CC      => Mage::helper('revslider')->__('center')
        ));
        return $options->getData();
    }

    public function _afterLoad(){
        $storeIds = $this->getStoreIds();
        $id = $this->getId();
        $this->setData((array)Mage::helper('core')->jsonDecode($this->getParams()));
        $date = Mage::getModel('core/date');
        if ($this->getData('date_from')){
            $this->setData('date_from', $date->date('m/d/Y', $date->timestamp($this->getData('date_from'))));
        }
        if ($this->getData('date_to')){
            $this->setData('date_to', $date->date('m/d/Y', $date->timestamp($this->getData('date_to'))));
        }
        $this->setStoreIds($storeIds);
        $this->setId($id);
        $this->setSliderId($id);
        return parent::_afterLoad();
    }

    public function _beforeSave(){
        if (is_array($this->getData('params'))){
            $this->setData('params', Mage::helper('core')->jsonEncode($this->getParams()));
        }
        return parent::_beforeSave();
    }

    public function getSlideCount($slider){
        if ($slider){
            $collection = Mage::getModel('revslider/slide')
                ->getCollection()
                ->addSliderFilter($slider)
                ->setOrder('slide_order', 'asc');
            return $collection->getSize();
        }
        return 0;
    }

    public function getAllSlides($published=false){
        $collection = Mage::getModel('revslider/slide')
            ->getCollection()
            ->addSliderFilter($this)
            ->setOrder('slide_order', 'asc');
        $slides = array();
        foreach ($collection as $slide){
            $id = $slide->getId();
            $layers = $slide->getLayers();
            $slide->setData(Mage::helper('core')->jsonDecode($slide->getParams()));
            $slide->setLayers(Mage::helper('core')->jsonDecode($layers));
            $slide->setId($id);
            if ($published){
                if ($slide->getData('state') == 'published'){
                    $slides[] = $slide;
                }
            }else{
                $slides[] = $slide;
            }
        }
        return $slides;
    }
}