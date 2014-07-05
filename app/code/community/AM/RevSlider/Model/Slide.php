<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
class AM_RevSlider_Model_Slide extends Mage_Core_Model_Abstract{
    const STATUS_PUBLISHED      = 'published';
    const STATUS_UNPUBLISHED    = 'unpublished';

    const LINK_TYPE_REGULAR     = 'regular';
    const LINK_TYPE_SLIDE       = 'slide';

    const LINK_TARGET_SAME      = 'same';
    const LINK_TARGET_NEW       = 'new';

    const LINK_POS_FRONT        = 'front';
    const LINK_POS_BACK         = 'back';

    const BG_TYPE_IMAGE         = 'image';
    const BG_TYPE_TRANS         = 'trans';
    const BG_TYPE_SOLID         = 'solid';
    const BG_TYPE_URL           = 'external';

    const BG_FIT_COVER          = 'cover';
    const BG_FIT_CONTAIN        = 'contain';
    const BG_FIT_PERCENTAGE     = 'percentage';
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
    const BG_POS_PE             = 'percentage';

    const LAYER_ANI_FADE        = 'tp-fade';
    const LAYER_ANI_SFT         = 'sft';
    const LAYER_ANI_SFB         = 'sfb';
    const LAYER_ANI_SFR         = 'sfr';
    const LAYER_ANI_SFL         = 'sfl';
    const LAYER_ANI_LFT         = 'lft';
    const LAYER_ANI_LFB         = 'lfb';
    const LAYER_ANI_LFL         = 'lfl';
    const LAYER_ANI_LFR         = 'lfr';
    const LAYER_ANI_SKFR        = 'skewfromright';
    const LAYER_ANI_SKFL        = 'skewfromleft';
    const LAYER_ANI_SKFLS       = 'skewfromleftshort';
    const LAYER_ANI_SKFRS       = 'skewfromrightshort';
    const LAYER_ANI_RND         = 'randomrotate';

    const LAYER_ANI_FADEOUT     = 'fadeout';
    const LAYER_ANI_STT         = 'stt';
    const LAYER_ANI_STB         = 'stb';
    const LAYER_ANI_STR         = 'str';
    const LAYER_ANI_STL         = 'stl';
    const LAYER_ANI_LTT         = 'ltt';
    const LAYER_ANI_LTB         = 'ltb';
    const LAYER_ANI_LTL         = 'ltl';
    const LAYER_ANI_LTR         = 'ltr';
    const LAYER_ANI_SKTR        = 'skewtoright';
    const LAYER_ANI_SKTL        = 'skewtoleft';
    const LAYER_ANI_SKTRS       = 'skewtorightshort';
    const LAYER_ANI_SKTLS       = 'skewtoleftshort';
    const LAYER_ANI_RNDOUT      = 'randomrotateout';

    const LAYER_EASE_1          = "Linear.easeNone";
    const LAYER_EASE_2          = "Power0.easeIn";
    const LAYER_EASE_3          = "Power0.easeInOut";
    const LAYER_EASE_4          = "Power0.easeOut";
    const LAYER_EASE_5          = "Power1.easeIn";
    const LAYER_EASE_6          = "Power1.easeInOut";
    const LAYER_EASE_7          = "Power1.easeOut";
    const LAYER_EASE_8          = "Power2.easeIn";
    const LAYER_EASE_9          = "Power2.easeInOut";
    const LAYER_EASE_10         = "Power2.easeOut";
    const LAYER_EASE_11         = "Power3.easeIn";
    const LAYER_EASE_12         = "Power3.easeInOut";
    const LAYER_EASE_13         = "Power3.easeOut";
    const LAYER_EASE_14         = "Power4.easeIn";
    const LAYER_EASE_15         = "Power4.easeInOut";
    const LAYER_EASE_16         = "Power4.easeOut";
    const LAYER_EASE_17         = "Quad.easeIn";
    const LAYER_EASE_18         = "Quad.easeInOut";
    const LAYER_EASE_19         = "Quad.easeOut";
    const LAYER_EASE_20         = "Cubic.easeIn";
    const LAYER_EASE_21         = "Cubic.easeInOut";
    const LAYER_EASE_22         = "Cubic.easeOut";
    const LAYER_EASE_23         = "Quart.easeIn";
    const LAYER_EASE_24         = "Quart.easeInOut";
    const LAYER_EASE_25         = "Quart.easeOut";
    const LAYER_EASE_26         = "Quint.easeIn";
    const LAYER_EASE_27         = "Quint.easeInOut";
    const LAYER_EASE_28         = "Quint.easeOut";
    const LAYER_EASE_29         = "Strong.easeIn";
    const LAYER_EASE_30         = "Strong.easeInOut";
    const LAYER_EASE_31         = "Strong.easeOut";
    const LAYER_EASE_32         = "Back.easeIn";
    const LAYER_EASE_33         = "Back.easeInOut";
    const LAYER_EASE_34         = "Back.easeOut";
    const LAYER_EASE_35         = "Bounce.easeIn";
    const LAYER_EASE_36         = "Bounce.easeInOut";
    const LAYER_EASE_37         = "Bounce.easeOut";
    const LAYER_EASE_38         = "Circ.easeIn";
    const LAYER_EASE_39         = "Circ.easeInOut";
    const LAYER_EASE_40         = "Circ.easeOut";
    const LAYER_EASE_41         = "Elastic.easeIn";
    const LAYER_EASE_42         = "Elastic.easeInOut";
    const LAYER_EASE_43         = "Elastic.easeOut";
    const LAYER_EASE_44         = "Expo.easeIn";
    const LAYER_EASE_45         = "Expo.easeInOut";
    const LAYER_EASE_46         = "Expo.easeOut";
    const LAYER_EASE_47         = "Sine.easeIn";
    const LAYER_EASE_48         = "Sine.easeInOut";
    const LAYER_EASE_49         = "Sine.easeOut";
    const LAYER_EASE_50         = "SlowMo.ease";
    const LAYER_EASE_51         = 'easeOutBack';
    const LAYER_EASE_52         = 'easeInQuad';
    const LAYER_EASE_53         = 'easeOutQuad';
    const LAYER_EASE_54         = 'easeInOutQuad';
    const LAYER_EASE_55         = 'easeInCubic';
    const LAYER_EASE_56         = 'easeOutCubic';
    const LAYER_EASE_57         = 'easeInOutCubic';
    const LAYER_EASE_58         = 'easeInQuart';
    const LAYER_EASE_59         = 'easeOutQuart';
    const LAYER_EASE_60         = 'easeInOutQuart';
    const LAYER_EASE_61         = 'easeInQuint';
    const LAYER_EASE_62         = 'easeOutQuint';
    const LAYER_EASE_63         = 'easeInOutQuint';
    const LAYER_EASE_64         = 'easeInSine';
    const LAYER_EASE_65         = 'easeOutSine';
    const LAYER_EASE_66         = 'easeInOutSine';
    const LAYER_EASE_67         = 'easeInExpo';
    const LAYER_EASE_68         = 'easeOutExpo';
    const LAYER_EASE_69         = 'easeInOutExpo';
    const LAYER_EASE_70         = 'easeInCirc';
    const LAYER_EASE_71         = 'easeOutCirc';
    const LAYER_EASE_72         = 'easeInOutCirc';
    const LAYER_EASE_73         = 'easeInElastic';
    const LAYER_EASE_74         = 'easeOutElastic';
    const LAYER_EASE_75         = 'easeInOutElastic';
    const LAYER_EASE_76         = 'easeInBack';
    const LAYER_EASE_77         = 'easeInOutBack';
    const LAYER_EASE_78         = 'easeInBounce';
    const LAYER_EASE_79         = 'easeOutBounce';
    const LAYER_EASE_80         = 'easeInOutBounce';

    const CORNOR_NONE           = 'nothing';
    const CORNOR_CURVED         = 'curved';
    const CORNOR_REVERSED       = 'reverced';

    const CSS_DECORATION_1      = 'none';
    const CSS_DECORATION_2      = 'underline';
    const CSS_DECORATION_3      = 'overline';
    const CSS_DECORATION_4      = 'line-through';

    const CSS_B_STYLE_1 = 'none';
    const CSS_B_STYLE_2 = 'dotted';
    const CSS_B_STYLE_3 = 'dashed';
    const CSS_B_STYLE_4 = 'solid';
    const CSS_B_STYLE_5 = 'double';

    const CSS_F_STYLE_1 = 'normal';
    const CSS_F_STYLE_2 = 'italic';

    public function _construct(){
        parent::_construct();
        $this->_init('revslider/slide');
    }

    public function getCssFontStyle(){
        $options = new Varien_Object(array(
            self::CSS_F_STYLE_1     => Mage::helper('revslider')->__('none'),
            self::CSS_F_STYLE_2     => Mage::helper('revslider')->__('italic')
        ));
        return $options->getData();
    }

    public function getCssBorderStyle(){
        $options = new Varien_Object(array(
            self::CSS_B_STYLE_1      => Mage::helper('revslider')->__('none'),
            self::CSS_B_STYLE_2      => Mage::helper('revslider')->__('dotted'),
            self::CSS_B_STYLE_3      => Mage::helper('revslider')->__('dashed'),
            self::CSS_B_STYLE_4      => Mage::helper('revslider')->__('solid'),
            self::CSS_B_STYLE_5      => Mage::helper('revslider')->__('double')
        ));
        return $options->getData();
    }

    public function getCssDecoration(){
        $options = new Varien_Object(array(
            self::CSS_DECORATION_1      => Mage::helper('revslider')->__('none'),
            self::CSS_DECORATION_2      => Mage::helper('revslider')->__('underline'),
            self::CSS_DECORATION_3      => Mage::helper('revslider')->__('overline'),
            self::CSS_DECORATION_4      => Mage::helper('revslider')->__('line-through')
        ));
        return $options->getData();
    }

    public function getPublishOptions(){
        $options = new Varien_Object(array(
            self::STATUS_PUBLISHED      => Mage::helper('revslider')->__('Published'),
            self::STATUS_UNPUBLISHED    => Mage::helper('revslider')->__('Unpublished')
        ));
        return $options->getData();
    }

    public function getLinkTypeOptions(){
        $options = new Varien_Object(array(
            self::LINK_TYPE_REGULAR     => Mage::helper('revslider')->__('Regular'),
            self::LINK_TYPE_SLIDE       => Mage::helper('revslider')->__('To Slide')
        ));
        return $options->getData();
    }

    public function getLinkTargetOptions(){
        $options = new Varien_Object(array(
            self::LINK_TARGET_SAME      => Mage::helper('revslider')->__('Same Window'),
            self::LINK_TARGET_NEW       => Mage::helper('revslider')->__('New Window')
        ));
        return $options->getData();
    }

    public function getLinkPosOptions(){
        $options = new Varien_Object(array(
            self::LINK_POS_FRONT      => Mage::helper('revslider')->__('Front'),
            self::LINK_POS_BACK       => Mage::helper('revslider')->__('Back')
        ));
        return $options->getData();
    }

    public function getBackgroundTypeOptions(){
        $options = new Varien_Object(array(
            self::BG_TYPE_IMAGE     => Mage::helper('revslider')->__('Image'),
            self::BG_TYPE_TRANS     => Mage::helper('revslider')->__('Transparent'),
            self::BG_TYPE_SOLID     => Mage::helper('revslider')->__('Solid Color'),
            self::BG_TYPE_URL       => Mage::helper('revslider')->__('External URL')
        ));
        return $options->getData();
    }

    public function getBackgroundSizeOptions(){
        $options = new Varien_Object(array(
            self::BG_FIT_COVER      => Mage::helper('revslider')->__('cover'),
            self::BG_FIT_CONTAIN    => Mage::helper('revslider')->__('contain'),
            self::BG_FIT_PERCENTAGE => Mage::helper('revslider')->__('(%, %)'),
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
            self::BG_POS_CC      => Mage::helper('revslider')->__('center'),
            self::BG_POS_PE      => Mage::helper('revslider')->__('(x%, y%)')
        ));
        return $options->getData();
    }

    public function getLayerStyleOptions(){
        $options = new Varien_Object(array(
            'big_yellow'                => 'big_yellow',
            'big_blue'                  => 'big_blue',
            'big_white'                 => 'big_white',
            'big_orange'                => 'big_orange',
            'big_black'                 => 'big_black',
            'medium_grey'               => 'medium_grey',
            'small_text'                => 'small_text',
            'medium_text'               => 'medium_text',
            'large_text'                => 'large_text',
            'very_large_text'           => 'very_large_text',
            'very_big_white'            => 'very_big_white',
            'very_big_black'            => 'very_big_black',
            'modern_medium_fat'         => 'modern_medium_fat',
            'modern_medium_fat_white'   => 'modern_medium_fat_white',
            'modern_medium_light'       => 'modern_medium_light',
            'modern_big_bluebg'         => 'modern_big_bluebg',
            'modern_big_redbg'          => 'modern_big_redbg',
            'modern_small_text_dark'    => 'modern_small_text_dark',
            'boxshadow'                 => 'boxshadow',
            'black'                     => 'black',
            'noshadow'                  => 'noshadow'
        ));
        return $options->getData();
    }

    public function getLayerAnimationOptions(){
        $options = new Varien_Object(array(
            self::LAYER_ANI_FADE    => Mage::helper('revslider')->__('Fade'),
            self::LAYER_ANI_SFT     => Mage::helper('revslider')->__('Short from Top'),
            self::LAYER_ANI_SFB     => Mage::helper('revslider')->__('Short from Bottom'),
            self::LAYER_ANI_SFL     => Mage::helper('revslider')->__('Short from Left'),
            self::LAYER_ANI_SFR     => Mage::helper('revslider')->__('Short from Right'),
            self::LAYER_ANI_LFT     => Mage::helper('revslider')->__('Long from Top'),
            self::LAYER_ANI_LFB     => Mage::helper('revslider')->__('Long from Bottom'),
            self::LAYER_ANI_LFL     => Mage::helper('revslider')->__('Long from Left'),
            self::LAYER_ANI_LFR     => Mage::helper('revslider')->__('Long from Right'),
            self::LAYER_ANI_SKFR    => Mage::helper('revslider')->__('Skew From Long Right'),
            self::LAYER_ANI_SKFL    => Mage::helper('revslider')->__('Skew From Long Left'),
            self::LAYER_ANI_SKFRS   => Mage::helper('revslider')->__('Skew From Short Right'),
            self::LAYER_ANI_SKFLS   => Mage::helper('revslider')->__('Skew From Short Left'),
            self::LAYER_ANI_RND     => Mage::helper('revslider')->__('Random Rotate')
        ));
        return $options->getData();
    }

    public function getLayerEndAnimationOptions(){
        $options = new Varien_Object(array(
            self::LAYER_ANI_FADEOUT => Mage::helper('revslider')->__('Fade Out'),
            self::LAYER_ANI_STT     => Mage::helper('revslider')->__('Short to Top'),
            self::LAYER_ANI_STB     => Mage::helper('revslider')->__('Short to Bottom'),
            self::LAYER_ANI_STL     => Mage::helper('revslider')->__('Short to Left'),
            self::LAYER_ANI_STR     => Mage::helper('revslider')->__('Short to Right'),
            self::LAYER_ANI_LTT     => Mage::helper('revslider')->__('Long to Top'),
            self::LAYER_ANI_LTB     => Mage::helper('revslider')->__('Long to Bottom'),
            self::LAYER_ANI_LTL     => Mage::helper('revslider')->__('Long to Left'),
            self::LAYER_ANI_LTR     => Mage::helper('revslider')->__('Long to Right'),
            self::LAYER_ANI_SKTR    => Mage::helper('revslider')->__('Skew To Right'),
            self::LAYER_ANI_SKTL    => Mage::helper('revslider')->__('Skew To Left'),
            self::LAYER_ANI_SKTRS   => Mage::helper('revslider')->__('Skew To Right Short'),
            self::LAYER_ANI_SKTLS   => Mage::helper('revslider')->__('Skew To Left Short'),
            self::LAYER_ANI_RNDOUT  => Mage::helper('revslider')->__('Random Rotate Out')
        ));
        return $options->getData();
    }

    public function getLayerEaseOptions(){
        $options = new Varien_Object(array(
            self::LAYER_EASE_1          => "Linear.easeNone",
            self::LAYER_EASE_2          => "Power0.easeIn",
            self::LAYER_EASE_3          => "Power0.easeInOut",
            self::LAYER_EASE_4          => "Power0.easeOut",
            self::LAYER_EASE_5          => "Power1.easeIn",
            self::LAYER_EASE_6          => "Power1.easeInOut",
            self::LAYER_EASE_7          => "Power1.easeOut",
            self::LAYER_EASE_8          => "Power2.easeIn",
            self::LAYER_EASE_9          => "Power2.easeInOut",
            self::LAYER_EASE_10         => "Power2.easeOut",
            self::LAYER_EASE_11         => "Power3.easeIn",
            self::LAYER_EASE_12         => "Power3.easeInOut",
            self::LAYER_EASE_13         => "Power3.easeOut",
            self::LAYER_EASE_14         => "Power4.easeIn",
            self::LAYER_EASE_15         => "Power4.easeInOut",
            self::LAYER_EASE_16         => "Power4.easeOut",
            self::LAYER_EASE_17         => "Quad.easeIn",
            self::LAYER_EASE_18         => "Quad.easeInOut",
            self::LAYER_EASE_19         => "Quad.easeOut",
            self::LAYER_EASE_20         => "Cubic.easeIn",
            self::LAYER_EASE_21         => "Cubic.easeInOut",
            self::LAYER_EASE_22         => "Cubic.easeOut",
            self::LAYER_EASE_23         => "Quart.easeIn",
            self::LAYER_EASE_24         => "Quart.easeInOut",
            self::LAYER_EASE_25         => "Quart.easeOut",
            self::LAYER_EASE_26         => "Quint.easeIn",
            self::LAYER_EASE_27         => "Quint.easeInOut",
            self::LAYER_EASE_28         => "Quint.easeOut",
            self::LAYER_EASE_29         => "Strong.easeIn",
            self::LAYER_EASE_30         => "Strong.easeInOut",
            self::LAYER_EASE_31         => "Strong.easeOut",
            self::LAYER_EASE_32         => "Back.easeIn",
            self::LAYER_EASE_33         => "Back.easeInOut",
            self::LAYER_EASE_34         => "Back.easeOut",
            self::LAYER_EASE_35         => "Bounce.easeIn",
            self::LAYER_EASE_36         => "Bounce.easeInOut",
            self::LAYER_EASE_37         => "Bounce.easeOut",
            self::LAYER_EASE_38         => "Circ.easeIn",
            self::LAYER_EASE_39         => "Circ.easeInOut",
            self::LAYER_EASE_40         => "Circ.easeOut",
            self::LAYER_EASE_41         => "Elastic.easeIn",
            self::LAYER_EASE_42         => "Elastic.easeInOut",
            self::LAYER_EASE_43         => "Elastic.easeOut",
            self::LAYER_EASE_44         => "Expo.easeIn",
            self::LAYER_EASE_45         => "Expo.easeInOut",
            self::LAYER_EASE_46         => "Expo.easeOut",
            self::LAYER_EASE_47         => "Sine.easeIn",
            self::LAYER_EASE_48         => "Sine.easeInOut",
            self::LAYER_EASE_49         => "Sine.easeOut",
            self::LAYER_EASE_50         => "SlowMo.ease",
            self::LAYER_EASE_51         => 'easeOutBack',
            self::LAYER_EASE_52         => 'easeInQuad',
            self::LAYER_EASE_53         => 'easeOutQuad',
            self::LAYER_EASE_54         => 'easeInOutQuad',
            self::LAYER_EASE_55         => 'easeInCubic',
            self::LAYER_EASE_56         => 'easeOutCubic',
            self::LAYER_EASE_57         => 'easeInOutCubic',
            self::LAYER_EASE_58         => 'easeInQuart',
            self::LAYER_EASE_59         => 'easeOutQuart',
            self::LAYER_EASE_60         => 'easeInOutQuart',
            self::LAYER_EASE_61         => 'easeInQuint',
            self::LAYER_EASE_62         => 'easeOutQuint',
            self::LAYER_EASE_63         => 'easeInOutQuint',
            self::LAYER_EASE_64         => 'easeInSine',
            self::LAYER_EASE_65         => 'easeOutSine',
            self::LAYER_EASE_66         => 'easeInOutSine',
            self::LAYER_EASE_67         => 'easeInExpo',
            self::LAYER_EASE_68         => 'easeOutExpo',
            self::LAYER_EASE_69         => 'easeInOutExpo',
            self::LAYER_EASE_70         => 'easeInCirc',
            self::LAYER_EASE_71         => 'easeOutCirc',
            self::LAYER_EASE_72         => 'easeInOutCirc',
            self::LAYER_EASE_73         => 'easeInElastic',
            self::LAYER_EASE_74         => 'easeOutElastic',
            self::LAYER_EASE_75         => 'easeInOutElastic',
            self::LAYER_EASE_76         => 'easeInBack',
            self::LAYER_EASE_77         => 'easeInOutBack',
            self::LAYER_EASE_78         => 'easeInBounce',
            self::LAYER_EASE_79         => 'easeOutBounce',
            self::LAYER_EASE_80         => 'easeInOutBounce'
        ));
        return $options->getData();
    }

    public function getCornorOptions(){
        $options = new Varien_Object(array(
            self::CORNOR_NONE       => Mage::helper('revslider')->__('No Corner'),
            self::CORNOR_CURVED     => Mage::helper('revslider')->__('Sharp'),
            self::CORNOR_REVERSED   => Mage::helper('revslider')->__('Sharp Reversed')
        ));
        return $options->getData();
    }

    public function _afterLoad(){
        $id         = $this->getId();
        $sliderId   = $this->getSliderId();
        $slideOrder = $this->getSlideOrder();
        $layers     = Mage::helper('core')->jsonDecode($this->getLayers());
        $this->setData((array)Mage::helper('core')->jsonDecode($this->getParams()));
        $this->setData('slide_transition', explode(',', $this->getData('slide_transition')));
        $this->setData('layers', $layers);
        $date = Mage::getModel('core/date');
        if ($this->getData('date_from')){
            $this->setData('date_from', $date->date('m/d/Y', $date->timestamp($this->getData('date_from'))));
        }
        if ($this->getData('date_to')){
            $this->setData('date_to', $date->date('m/d/Y', $date->timestamp($this->getData('date_to'))));
        }
        $this->setId($id);
        $this->setSliderId($sliderId);
        $this->setSlideOrder($slideOrder);
        return parent::_afterLoad();
    }

    public function _beforeSave(){
        if (is_array($this->getData('layers'))){
            $this->setData('layers', Mage::helper('core')->jsonEncode($this->getLayers()));
        }
        return parent::_beforeSave();
    }
}