/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
'use strict';

var RevLayers = Class.create();
RevLayers.prototype = {
    form: null,
    delay: null,
    container: null,
    list: null,
    slider: null,
    count: 0,
    index: 0,
    layers: {},
    layerParamsStr: 'align|style|style_custom|text|left|top|scaleX|scaleY|proportional_scale|animation|easing|speed|endanimation|endeasing|endspeed|endtime|link_enable|link_type|link|link_open_in|link_slide|corner_left|corner_right|resizeme|hiddenunder|id|classes|title|rel|alt',
    videoParamsStr: 'width|height|fullwidth|loop|control|args|autoplay|autoplayonlyfirsttime|nextslide|mute|force_rewind',
    animParamsStr: 'animation|speed|easing|endanimation|endspeed|endeasing',
    cusAnimParams: 'name|movex|movey|movez|rotax|rotay|rotaz|scalex|scaley|skewx|skewy|opacity|perspective|originx|originy'.split('|'),
    cssParams: 'font-family|color|padding|font-style|font-size|line-height|font-weight|text-decoration|background|border-color|border-style|border-width|border-radius|css'.split('|'),
    cssState: 'normal',
    cssUsingHover: 2,
    animPos: {},
    cusAnimPos: {},
    anims: new Hash(),
    autorun: true,
    layerParamsElm: {},
    selectedLayer: null,
    deleteBtn: null,
    dupLayerBtn: null,
    editLayerBtn: null,
    videoData: null,
    lastVideoId: null,
    videoSearch: false,

    initialize: function(form, delay, config){
        this.mediaUrl       = config.media_url;
        this.css_save_url   = config.css_save_url;
        this.css_delete_url = config.css_delete_url;
        this.css_url        = config.css_url;
        this.anim_save_url  = config.anim_save_url;
        this.anim_delete_url= config.anim_delete_url;

        this.form           = form;
        this.delay          = delay || 9000;

        config.anims.each(function(anim){
            this.anims.set(anim.id, anim);
        }, this);

        this.collectContainer();
        this.collectBtns();
        this.collectParamsElement();
        this.updateContainer();
        this.updateList();
        this.selectLayer();
        this.prepareAnimation();
        this.updateAnimationSelect();
    },

    deleteLayerCss: function(windowId, id){
        if (id && confirm(Translator.translate('Do you want delete this style?'))){
            new Ajax.Request(this.css_delete_url, {
                method: 'post',
                parameters: {id: id},
                onSuccess: function(transport){
                    var response = transport.responseText.evalJSON();
                    if (response.success == 1){
                        Windows.close(windowId);
                        var style = $('layer_style');
                        for (var i=0; i < style.options.length; i++){
                            if (style.options.item(i).value == id){
                                style.options.item(i).remove();
                            }
                        }
                        this.selectedLayer.removeClassName(this.selectedLayer.params['style']);
                        this.selectedLayer.params['style'] = '';
                        style.value = '';
                        this.updateCssNew();
                    }
                }.bind(this)
            });
        }
    },

    assignCssForm: function(data){
        this.cssObject = data.normal;
        if (data.hover.length === 0){
            this.cssHover = Object.clone(data.normal);
            if (data.normal['padding']) this.cssHover['padding'] = Object.clone(data.normal['padding']);
            if (data.normal['border-radius']) this.cssHover['border-radius'] = Object.clone(data.normal['border-radius']);
        }else this.cssHover = data.hover;
        this.cssUsingHover = data.settings ? data.settings.hover : 0;
        this.updateCssElementsEditor(this.cssObject);
        this.updateCssPreview();
        this.bindCssEditorEvent();
        this.toggleCssEditMode(1, true);
        this.toggleCssState(1);
    },

    updateCssElementsEditor: function(data){
        var self = this;

        function bindChangeEvent(element, param){
            element.on('slide', function(e,u){
                var index = jQuery(this).data('sid');
                if (self.cssState == 'normal'){
                    if (!self.cssObject[param]) self.cssObject[param] = [0,0,0,0];
                    self.cssObject[param][index] = u.value;
                }else{
                    if (!self.cssHover[param]) self.cssHover[param] = [0,0,0,0];
                    self.cssHover[param][index] = u.value;
                }
                self.updateCssPreview();
            });
        }

        function bindBgChangeEvent(color, alpha){
            var style = {},
                rgba = color.val() ? hexToRgba(color.val(), parseInt(alpha) / 100) : '';

            style['background-color'] = rgba ? 'rgba(' + rgba + ')' : 'transparent';
            if (self.cssState == 'normal') self.cssObject['background-color'] = style['background-color'];
            else self.cssHover['background-color'] = style['background-color'];
            self.updateCssPreview();
        }

        function hexToRgba(hex, alpha){
            var bigint = parseInt(hex, 16),
                r = (bigint >> 16) & 255,
                g = (bigint >> 8) & 255,
                b = bigint & 255,
                a = alpha < 0.0 ? 0 : (alpha > 1.0 ? 1 : alpha);

            return [r, g, b, a].join();
        }

        function rgbToHex(r, g, b){
            return ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
        }

        this.cssParams.each(function(param){
            switch (param){
                case 'padding':
                case 'border-radius':
                    if (data[param]){
                        for (var i in data[param]){
                            var elm = jQuery('#css_' + param + '_' + i);
                            if (elm.length){
                                elm.slider('option', 'value', data[param][i]);
                                if (!elm.data('binded')){
                                    elm.data('binded', true);
                                    bindChangeEvent(elm, param);
                                }
                            }
                        }
                    }else{
                        for (var i=0; i <= 4; i++){
                            var elm = jQuery('#css_' + param + '_' + i);
                            if (elm.length){
                                elm.slider('option', 'value', 0);
                                if (!elm.data('binded')){
                                    elm.data('binded', true);
                                    bindChangeEvent(elm, param);
                                }
                            }
                        }
                    }
                    break;
                case 'font-size':
                case 'line-height':
                case 'font-weight':
                case 'border-width':
                    var elm = jQuery('#css_' + param);
                    if (elm.length){
                        data[param] = data[param] || 0;
                        elm.slider('option', 'value', data[param]);
                        if (!elm.data('binded')){
                            elm.data('binded', true);
                            elm.on('slide', function(e,u){
                                var style = {};
                                style[param] = u.value;
                                self.updateCssObject(param, u.value);
                                self.updateCssPreview();
                            });
                        }
                    }
                    break;
                case 'font-style':
                case 'text-decoration':
                    var elm = $('css_' + param);
                    if (elm){
                        if (data[param]) elm.value = data[param];
                        if (!elm.binded){
                            elm.binded = true;
                            elm.observe('change', function(){
                                var style = {};
                                style[param] = elm.value;
                                this.updateCssObject(param, elm.value);
                                this.updateCssPreview();
                            }.bind(this));
                        }
                    }
                    break;
                case 'background':
                    var alpha = jQuery('#css_background-transparency'),
                        color = jQuery('#css_background-color');

                    if (alpha.length && color.length){
                        if (!data['background-color']){
                            color.val('');
                            alpha.slider('option', 'value', 100);
                        }else{
                            if (data['background-color'].indexOf('rgb') === 0){
                                var str = data['background-color'].replace(/[rgba\(\)]/g, ''),
                                    rgb = str.split(','),
                                    hex = rgbToHex(parseInt(rgb[0]), parseInt(rgb[1]), parseInt(rgb[2]));

                                color.val(hex);
                                if (rgb[3]) alpha.slider('option', 'value', parseFloat(rgb[3]) * 100);
                                else alpha.slider('option', 'value', 100);
                            }else{
                                color.val(data['background-color']);
                                alpha.slider('option', 'value', 100);
                            }
                        }
                        if (!color.binded){
                            color.binded = true;
                            color.on('change', function(){
                                bindBgChangeEvent(color, alpha.slider('value'));
                            });
                        }
                        $('css_background-color').color && $('css_background-color').color.importColor();
                        if (!alpha.data('binded')){
                            alpha.data('binded', true);
                            alpha.on('slide', function(e,u){
                                bindBgChangeEvent(color, u.value);
                            });
                        }
                    }
                    break;
                case 'color':
                case 'border-color':
                    var elm = $('css_' + param);
                    if (elm){
                        if (data[param]){
                            if (data[param].indexOf('rgb') === 0){
                                var str = data[param].replace(/[rgba\(\)]/g, ''),
                                    rgb = str.split(','),
                                    hex = rgbToHex(parseInt(rgb[0]), parseInt(rgb[1]), parseInt(rgb[2]));

                                elm.value = hex.toUpperCase();
                            }else{
                                elm.value = data[param];
                            }
                        }
                        if (!elm.binded){
                            elm.binded = true;
                            elm.observe('change', function(){
                                var style = {};
                                style[param] = '#' + elm.value;
                                this.updateCssObject(param, style[param]);
                                this.updateCssPreview();
                            }.bind(this));
                        }
                        elm.color && elm.color.importColor();
                    }
                    break;
                case 'css':
                    var elm = $('css_css');
                    if (elm){
                        var css = this.getCssFromObject(data, false);
                        if (CodeMirror){
                            if (!elm.cm){
                                elm.value = css;
                                this.cssCM = CodeMirror.fromTextArea(elm, {
                                    mode: 'css'
                                });
                                this.cssCM.on('blur', function(instance){
                                    var content = instance.getValue(),
                                        data = this.getStyleFromCss(content);

                                    if (this.cssState == 'normal') this.cssObject = data;
                                    else this.cssHover = data;
                                }.bind(this));
                                elm.cm = this.cssCM;
                            }
                        }
                    }
                    break;
                default:
                    var elm = $('css_' + param);
                    if (elm){
                        elm.value = data[param];
                        if (!elm.binded){
                            elm.binded = true;
                            elm.observe('change', function(){
                                var style = {};
                                style[param] = elm.value;
                                this.updateCssObject(param, elm.value);
                                this.updateCssPreview();
                            }.bind(this));
                        }
                    }
                    break;
            }
        }, this);
    },

    updateCssObject: function(param, value){
        if (param && value){
            if (this.cssState == 'normal'){
                this.cssObject[param] = value;
            }
            else this.cssHover[param] = value;
        }
    },

    getCssFromObject: function(obj, array){
        var rules = [];
        if (typeof obj === 'object'){
            for (var i in obj){
                if (typeof obj[i] === 'object' && obj[i].length){
                    rules.push(i + ": " + obj[i].invoke('toString').invoke('concat', 'px').join(' '));
                }else{
                    switch (i){
                        case 'font-size':
                        case 'line-height':
                        case 'border-width':
                            rules.push(i + ": " + obj[i] + 'px');
                            break;
                        default:
                            rules.push(i + ": " + obj[i]);
                            break;
                    }
                }
            }
        }
        if (array) return rules;
        else return '{\n' + rules.join(';\n') + '\n}';
    },

    bindCssEditorEvent: function(){
        $$('input[name="css_hover"]').each(function(input){
            input.observe('click', function(){
                this.cssUsingHover = input.value;
            }.bind(this));
        }, this);
        $$('input[name="css_mode"]').each(function(input){
            input.observe('click', function(){
                this.toggleCssEditMode(parseInt(input.value), false);
            }.bind(this));
        }, this);
        $$('input[name="css_state"]').each(function(input){
            input.observe('click', function(){
                this.toggleCssState(parseInt(input.value));
            }.bind(this));
        }, this);
    },

    toggleCssState: function(mode){
        switch (mode){
            case 1:
                this.cssState = 'normal';
                if (this.cssMode == 1) this.updateCssElementsEditor(this.cssObject);
                else{
                    var content = this.getCssFromObject(this.cssObject, false);
                    this.cssCM.setValue(content);
                }
                break;
            case 2:
                this.cssState = 'hover';
                if (this.cssMode == 1) this.updateCssElementsEditor(this.cssHover);
                else{
                    var content = this.getCssFromObject(this.cssHover, false);
                    this.cssCM.setValue(content);
                }
                break;
        }
        this.updateCssPreview();
    },

    toggleCssEditMode: function(mode, init){
        var simple = $('css_container_fieldset'),
            advance = $('css_advance_fieldset');

        this.cssMode = mode;
        switch (mode){
            case 1:
                simple && simple.show();
                simple && this.updateCssEditor(init);
                advance && advance.hide();
                break;
            case 2:
                simple && simple.hide();
                advance && advance.show();
                if (this.cssCM){
                    var content = this.cssState == 'normal' ? this.getCssFromObject(this.cssObject, false) : this.getCssFromObject(this.cssHover, false);
                    this.cssCM.setValue(content);
                }
                break;
        }
    },

    updateCssEditor: function(init){
        if (init) return;

        if (this.cssCM){
            var content = this.cssCM.getValue(),
                data = this.getStyleFromCss(content);

            this.updateCssElementsEditor(data);
            this.updateCssPreview();
            if (this.cssState == 'normal') this.cssObject = data;
            else this.cssHover = data;
        }
    },

    /**
     * Get css object from css text
     */
    getStyleFromCss: function(content){
        content = '#dummy' + content;
        var doc = document.implementation.createHTMLDocument(''),
            styleElement = document.createElement('style');

        styleElement.textContent = content;
        doc.body.appendChild(styleElement);

        var style = styleElement.sheet.cssRules[0].style,
            data = {};

        style.cssText.split(';').each(function(rule){
            var parts = rule.split(':'),
                ruleName = parts[0] ? parts[0].trim() : '';

            if (ruleName){
                if (ruleName == 'padding'){
                    data['padding'] = [
                        style.paddingTop ? parseInt(style.paddingTop) : 0,
                        style.paddingRight ? parseInt(style.paddingRight) : 0,
                        style.paddingBottom ? parseInt(style.paddingBottom) : 0,
                        style.paddingLeft ? parseInt(style.paddingLeft) : 0
                    ];
                }else if (ruleName == 'font-size'){
                    data['font-size'] = parseInt(style.fontSize);
                }else if (ruleName == 'font-weight'){
                    data['font-weight'] = parseInt(style.fontWeight);
                }else if (ruleName == 'line-height'){
                    data['line-height'] = parseInt(style.lineHeight);
                }else{
                    data[parts[0].trim()] = parts[1].trim();
                }
            }
        });

        /*data['font-family']     = style.fontFamily;
        data['color']           = style.color;
        data['padding']         = [
            style.paddingTop ? parseInt(style.paddingTop) : 0,
            style.paddingRight ? parseInt(style.paddingRight) : 0,
            style.paddingBottom ? parseInt(style.paddingBottom) : 0,
            style.paddingLeft ? parseInt(style.paddingLeft) : 0
        ];
        data['font-style']      = style.fontStyle;
        data['font-size']       = parseInt(style.fontSize);
        data['line-height']     = parseInt(style.lineHeight);
        data['font-weight']     = parseInt(style.fontWeight);
        data['text-decoration'] = style.textDecoration;
        data['background-color'] = style.backgroundColor;*/

        data['border-color']    = style.borderColor;
        data['border-style']    = style.borderStyle;
        data['border-width']    = parseInt(style.borderWidth);
        data['border-radius']   = [
            style.borderTopLeftRadius ? parseInt(style.borderTopLeftRadius) : 0,
            style.borderTopRightRadius ? parseInt(style.borderTopRightRadius) : 0,
            style.borderBottomRightRadius ? parseInt(style.borderBottomRightRadius) : 0,
            style.borderBottomLeftRadius ? parseInt(style.borderBottomLeftRadius) : 0
        ];

        return data;
    },

    updateCssPreview: function(){
        var target = $('css_preview'),
            data = this.cssState == 'normal' ? this.cssObject : this.cssHover;

        if (target){
            var style = {};
            if (this.cssState == 'normal'){
                style.fontFamily = data['font-family'] || 'inherit';
                style.fontSize = data['font-size'] != null ? data['font-size'] + 'px' : 'inherit';
                style.fontStyle = data['font-style'] || 'normal';
                style.fontWeight = data['font-weight'] || 'normal';

                style.color = data['color'] || '000';
                style.lineHeight = data['line-height'] != null ? data['line-height'] + 'px' : '100%';
                style.textDecoration = data['text-decoration'] || 'none';
                style.backgroundColor = data['background-color'] || 'transparent';

                style.borderColor = data['border-color'] || '000';
                style.borderStyle = data['border-style'] || 'solid';
                style.borderWidth = data['border-width'] != null ?data['border-width'] + 'px' : '0';

                if (data['padding']){
                    if (data['padding'][0] != null) style.paddingTop = data['padding'][0] + 'px';
                    if (data['padding'][1] != null) style.paddingRight = data['padding'][1] + 'px';
                    if (data['padding'][2] != null) style.paddingBottom = data['padding'][2] + 'px';
                    if (data['padding'][3] != null) style.paddingLeft = data['padding'][3] + 'px';
                }else{
                    style.paddingTop = 0;
                    style.paddingRight = 0;
                    style.paddingBottom = 0;
                    style.paddingLeft = 0;
                }

                if (data['border-radius']){
                    if (data['border-radius'][0] != null) style.borderTopLeftRadius = data['border-radius'][0] + 'px';
                    if (data['border-radius'][1] != null) style.borderTopRightRadius = data['border-radius'][1] + 'px';
                    if (data['border-radius'][2] != null) style.borderBottomRightRadius = data['border-radius'][2] + 'px';
                    if (data['border-radius'][3] != null) style.borderBottomLeftRadius = data['border-radius'][3] + 'px';
                }else{
                    style.borderTopLeftRadius = 0;
                    style.borderTopRightRadius = 0;
                    style.borderBottomRightRadius = 0;
                    style.borderBottomLeftRadius = 0;
                }
            }else{
                if (data['font-family']) style.fontFamily = data['font-family'];
                if (data['font-size'] != null) style.fontSize = data['font-size'] + 'px';
                if (data['font-style']) style.fontStyle = data['font-style'];
                if (data['font-weight']) style.fontWeight = data['font-weight'];

                if (data['color']) style.color = data['color'];
                if (data['line-height'] != null) style.lineHeight = data['line-height'] + 'px';
                if (data['text-decoration']) style.textDecoration = data['text-decoration'];
                if (data['background-color']) style.backgroundColor = data['background-color'];

                if (data['border-color']) style.borderColor = data['border-color'];
                if (data['border-style']) style.borderStyle = data['border-style'];
                if (data['border-width'] != null) style.borderWidth = data['border-width'] + 'px';

                if (data['padding']){
                    if (data['padding'][0] != null) style.paddingTop = data['padding'][0] + 'px';
                    if (data['padding'][1] != null) style.paddingRight = data['padding'][1] + 'px';
                    if (data['padding'][2] != null) style.paddingBottom = data['padding'][2] + 'px';
                    if (data['padding'][3] != null) style.paddingLeft = data['padding'][3] + 'px';
                }

                if (data['border-radius']){
                    if (data['border-radius'][0] != null) style.borderTopLeftRadius = data['border-radius'][0] + 'px';
                    if (data['border-radius'][1] != null) style.borderTopRightRadius = data['border-radius'][1] + 'px';
                    if (data['border-radius'][2] != null) style.borderBottomRightRadius = data['border-radius'][2] + 'px';
                    if (data['border-radius'][3] != null) style.borderBottomLeftRadius = data['border-radius'][3] + 'px';
                }
            }

            target.setStyle(style);
        }
    },

    openCssDialog: function(url, id, title){
        var style = $('layer_style');
        if (style.value){
            url += 'style/' + style.value;
            AM.MediabrowserUtility.openDialog(url, id, 1000, null, title);
        }else alert(Translator.translate('Please choose a style.'));
    },

    openAnimationDialog: function(url, id, title, type){
        url += 'type/' + type;
        if (type == 'in'){
            url += '/aid/' + $('layer_animation').value;
        }else if(type == 'out'){
            var anim = $('layer_endanimation').value;
            if (anim == 'auto') anim = $('layer_animation').value;
            url += '/aid/' + anim;
        }
        AM.MediabrowserUtility.openDialog(url, id, null, null, title);
    },

    updateAnimationSelect: function(){
        var inAnimSelect = $('layer_animation'),
            endAnimSelect = $('layer_endanimation'),
            data = arguments.length === 1 ? arguments[0] : this.anims;

        function hasAnimation(anim, collection){
            for (var i=0; i<collection.length; i++) if (collection.item(i).value === anim) return collection.item(i);
            return false;
        }

        data.each(function(anim){
            var animation = 'custom-' + anim.key,
                inAnimItem = hasAnimation(animation, inAnimSelect.options),
                endAnimItem = hasAnimation(animation, endAnimSelect.options);

            if (!inAnimItem) inAnimSelect.options.add(new Option(anim.value.name, animation));
            else inAnimItem.update(anim.value.name);

            if (!endAnimItem) endAnimSelect.options.add(new Option(anim.value.name, animation));
            else endAnimItem.update(anim.value.name);
        });
    },

    removeCustomAnimation: function(windowId, id){
        if (id.indexOf('custom') === 0){
            if (!confirm(Translator.translate("Really delete this animation?"))) return;
            var animId = id.split('-')[1];
            new Ajax.Request(this.anim_delete_url, {
                parameters: {id: animId},
                method: 'post',
                onComplete: function(){
                    Windows.close(windowId);
                },
                onSuccess: function(transport){
                    try{
                        var response = transport.responseText.evalJSON();
                        if (response.success == 1){
                            var index = $('layer_animation').selectedIndex;
                            $('layer_animation').options.item(index).remove();
                            if ($('layer_endanimation').selectedIndex != 0)
                                $('layer_endanimation').options.item($('layer_endanimation').selectedIndex).remove();
                            else
                                $('layer_endanimation').options.item(index + 1).remove();
                        }
                    }catch (e){}
                }.bind(this)
            });
        }else{
            alert(Translator.translate("Default animations can't be deleted"));
        }
    },

    addCustomAnimation: function(windowId, type){
        if (editForm.validator.validate()){
            var params = {};
            this.cusAnimParams.each(function(param){
                var elm = $$('input[name="anim-' + param + '"]')[0];
                if (elm) params[param] = elm.value;
            });
            if ($('anim_id')) params.id = $('anim_id').value;
            new Ajax.Request(this.anim_save_url, {
                method: 'post',
                parameters: params,
                onComplete: function(){
                    Windows.close(windowId);
                },
                onSuccess: function(transport){
                    try{
                        var response = transport.responseText.evalJSON();
                        if (response.success == 1){
                            if (response.data){
                                this.anims.set(response.data.id, response.data);
                                var tmpHash = new Hash();
                                tmpHash.set(response.data.id, response.data);
                                this.updateAnimationSelect(tmpHash);
                                setTimeout(function(){
                                    var animation = 'custom-' + response.data.id;
                                    if (type == 'in'){
                                        $('layer_animation').value = animation;
                                        this.selectedLayer.params.animation = animation;
                                    }else{
                                        $('layer_endanimation').value = animation;
                                        this.selectedLayer.params.endanimation = animation;
                                    }
                                }.bind(this));
                            }
                        }
                    }catch (e){}
                }.bind(this)
            });
        }
    },

    initCustomAnimSlider: function(items){
        items.each(function(item){
            var slider = $(item.id);
            if (slider){
                if (!margin){
                    var d = slider.up('div.anim-slider-wrapper').getDimensions(),
                        width = Math.floor(d.width / items.length),
                        margin = Math.floor(width/2) - 1;
                }

                slider.setStyle({
                    marginLeft: margin + 'px',
                    marginRight: margin + 'px',
                    backgroundColor: item.color
                });

                slider.insert({after:'<span class="anim-label">' +item.label+ '</span>'});

                slider.slider = jQuery('#' + item.id).slider({
                    orientation: 'vertical',
                    min: item.range[0],
                    max: item.range[1],
                    value: item.value,
                    create: function(){
                        jQuery(this).find('a.ui-slider-handle').css({backgroundColor: item.color}).attr('title', item.label);
                        jQuery(this).find('input.anim-value').val(item.value);
                    },
                    slide: function(e,u){
                        jQuery(this).find('input.anim-value').val(u.value);
                    }
                });

                slider.down('input').observe('change', function(ev){
                    var value = parseInt(Event.findElement(ev, 'input').value);
                    if (isNaN(value)) return;
                    value = value < item.range[0] ? item.range[0] : (value > item.range[1] ? item.range[1] : value);
                    slider.slider.slider('value', value);
                });
            }
        });
    },

    runCustomAnimation: function(type){
        setTimeout(function(){
            this.cusAnimPos = this.prepareAnimationTarget('custom_animation_preview_wrapper');
            if (type == 'in') this.setInCustomAnimation('custom_animation_preview_wrapper');
            else this.setOutCustomAnimation('custom_animation_preview_wrapper');
        }.bind(this));
    },

    setScale: function(useX, reset){
        if (this.selectedLayer && this.selectedLayer.params.type === 'image'){
            var layer = this.selectedLayer.params,
                imgUrl = layer.image_url.indexOf('http') === 0 ? layer.image_url : this.mediaUrl + layer.image_url,
                img = new Element('img', {'src': imgUrl}),
                x = img.width, y = img.height;

            if (!reset && useX){
                x = parseInt(layer.scaleX);
                if (isNaN(x)) x = img.width;
                y = layer['proportional_scale'] ? Math.round(100 / img.width * x / 100 * img.height) : (!isNaN(layer.scaleY) ? layer.scaleY : img.height);
            }else if (!reset && !useX){
                y = parseInt(layer.scaleY);
                if(isNaN(y)) y = img.height;
                x = layer['proportional_scale'] ? Math.round(100 / img.height * y / 100 * img.width) : (!isNaN(layer.scaleX) ? layer.scaleX : img.width);
            }

            layer.scaleX = x;
            layer.scaleY = y;
            this.layerParamsElm['scaleX'].value = x;
            this.layerParamsElm['scaleY'].value = y;
            this.updateLayerHtmlScale(layer);
            this.updateAlign(this.selectedLayer);
        }
    },

    toggleAutotun: function(){
        var parent = $('animation_control'),
            control = parent.down('span');

        if (control){
            if (arguments.length === 1){
                if (this.autorun){
                    var flag = arguments[0];
                    if (flag){
                        control.addClassName('on');
                        this.toggleAnimPreview(true);
                    }else{
                        control.removeClassName('on');
                        this.toggleAnimPreview(false);
                    }
                }
            }else{
                if (this.selectedLayer){
                    if (control.hasClassName('on')){
                        control.removeClassName('on');
                        this.toggleAnimPreview(false);
                        this.autorun = false;
                    }else{
                        control.addClassName('on');
                        this.toggleAnimPreview(true);
                        this.autorun = true;
                    }
                }
            }
        }
    },

    prepareAnimation: function(){
        this.animParamsStr.split('|').each(function(param){
            var elm = $('layer_' + param);
            if (elm){
                elm.observe('change', function(){
                    if (this.autorun) this.setInAnimation();
                }.bind(this));
            }
        }, this);
        setTimeout(function(){
            this.animPos = this.prepareAnimationTarget('layer_animation_preview');
        }.bind(this), 1000);
    },

    prepareAnimationTarget: function(parent){
        var parent = $(parent),
            target = parent.down('div.animation_preview_target');

        if (target){
            var d1 = target.getDimensions(),
                d2 = parent.getDimensions(),
                x = parseInt(d2.width / 2 - d1.width / 2),
                y = parseInt(d2.height / 2 - d1.height / 2);

            target.setStyle({
                top: y + 'px',
                left: x + 'px',
                visibility: 'visible'
            });

            return {top: y, left: x};
        }
    },

    toggleAnimPreview: function(flag){
        var target = $('animation_preview');

        if (target){
            TweenLite.killTweensOf(target, false);

            if (flag){
                target.timer && clearTimeout(target.timer);
                target.removeClassName('reset');
                this.setInAnimation();
            }else{
                target.timer && clearTimeout(target.timer);
                target.addClassName('reset');
                this.animPos.top && target.setStyle({
                    top: this.animPos.top + 'px',
                    left: this.animPos.left + 'px'
                });
            }
        }
    },

    setInCustomAnimation: function(parent){
        if (!$(parent)) return;
        var target = $(parent).down('div.animation_preview_target'),
            params = {};
        this.cusAnimParams.each(function(param){
            var elm = $$('input[name="anim-' + param + '"]')[0];
            if (elm) params[param] = elm.value;
        });
        params['easing'] = $('anim_easing').value;
        params['speed'] = $('anim_speed').value;
        if (isNaN(params['speed'])) params['speed'] = 500;
        TweenLite.killTweensOf(target, false);
        TweenLite.fromTo(target, parseInt(params['speed']) / 1000,
            {
                scaleX:     parseInt(params['scalex']) / 100,
                scaleY:     parseInt(params['scaley']) / 100,
                rotationX:  parseInt(params['rotax']),
                rotationY:  parseInt(params['rotay']),
                rotationZ:  parseInt(params['rotaz']),
                x:          parseInt(params['movex']),
                y:          parseInt(params['movey']),
                z:          parseInt(params['movez']) + 1,
                skewX:      parseInt(params['skewx']),
                skewY:      parseInt(params['skewy']),
                left:       this.cusAnimPos.left,
                top:        this.cusAnimPos.top,
                opacity:    parseInt(params['opacity']) / 100,
                transformPerspective: parseInt(params['perspective']),
                transformOrigin: parseInt(params['originx']) + '% ' + parseInt(params['originy']) + '%',
                visibility: 'hidden'
            },
            {
                left:       this.cusAnimPos.left,
                top:        this.cusAnimPos.top,
                x:          0,
                y:          0,
                z:          1,
                scaleX:     1,
                scaleY:     1,
                rotationX:  0,
                rotationY:  0,
                rotationZ:  0,
                skewX:      0,
                skewY:      0,
                visibility: 'visible',
                opacity:    1,
                ease:       params['easing'],
                overwrite:  'all',
                onComplete: function(){
                    setTimeout(function(){
                        this.setInCustomAnimation('custom_animation_preview_wrapper');
                    }.bind(this), 500);
                }.bind(this)
            }
        );
    },

    setOutCustomAnimation: function(parent){
        if (!$(parent)) return;
        var target = $(parent).down('div.animation_preview_target'),
            params = {};
        this.cusAnimParams.each(function(param){
            var elm = $$('input[name="anim-' + param + '"]')[0];
            if (elm) params[param] = elm.value;
        });
        params['easing'] = $('anim_easing').value;
        params['speed'] = $('anim_speed').value;
        if (isNaN(params['speed'])) params['speed'] = 500;
        TweenLite.killTweensOf(target, false);
        TweenLite.fromTo(target, parseInt(params['speed']) / 1000,
            {
                left:       this.cusAnimPos.left,
                top:        this.cusAnimPos.top,
                x:          0,
                y:          0,
                z:          1,
                scaleX:     1,
                scaleY:     1,
                rotationX:  0,
                rotationY:  0,
                rotationZ:  0,
                skewX:      0,
                skewY:      0,
                visibility: 'visible',
                opacity:    1
            },
            {
                scaleX:     parseInt(params['scalex']) / 100,
                scaleY:     parseInt(params['scaley']) / 100,
                rotationX:  parseInt(params['rotax']),
                rotationY:  parseInt(params['rotay']),
                rotationZ:  parseInt(params['rotaz']),
                x:          parseInt(params['movex']),
                y:          parseInt(params['movey']),
                z:          parseInt(params['movez']) + 1,
                skewX:      parseInt(params['skewx']),
                skewY:      parseInt(params['skewy']),
                left:       this.cusAnimPos.left,
                top:        this.cusAnimPos.top,
                opacity:    parseInt(params['opacity']) / 100,
                transformPerspective: parseInt(params['perspective']),
                transformOrigin: parseInt(params['originx']) + '% ' + parseInt(params['originy']) + '%',
                overwrite:  'all',
                delay:     0.3,
                onComplete: function(){
                    setTimeout(function(){
                        this.setOutCustomAnimation('custom_animation_preview_wrapper');
                    }.bind(this), 500);
                }.bind(this)
            }
        );
    },

    getCustomAnimation: function(id){
        return this.anims.get(id).params;
    },

    setInAnimation: function(){
        var target = $('animation_preview'),
            p = this.animPos,
            anim = $('layer_animation').value || 'fade',
            speed = $('layer_speed').value / 1000,
            ease = $('layer_easing').value,
            tlxx = p.left, tlyy = p.top,
            sc = 1,
            ro = 0,
            skwX = 0,
            calcx = p.left,
            calcy = p.top;

        TweenLite.killTweensOf(target, false);
        target.timer && clearTimeout(target.timer);

        if (anim == 'randomrotate'){
            sc = Math.random() * 3 + 1;
            ro = Math.round(Math.random() * 200 - 100);
            tlxx = calcx + Math.round(Math.random() * 200 - 100);
            tlyy = calcy + Math.round(Math.random() * 200 - 100);
        }
        if (anim == 'lfr' || anim == 'skewfromright') tlxx = 560;
        if (anim == 'lfl' || anim == 'skewfromleft') tlxx = -100;
        if (anim == 'sfl' || anim == 'skewfromleftshort') tlxx = calcx - 50;
        if (anim == 'sfr' || anim == 'skewfromrightshort') tlxx = calcx + 50;
        if (anim == 'lft') tlyy = -50;
        if (anim == 'lfb') tlyy = 250;
        if (anim == 'sft') tlyy = calcy - 50;
        if (anim == 'sfb') tlyy = calcy + 50;
        if (anim == 'skewfromright' || anim == 'skewfromrightshort') skwX = -85;
        if (anim == 'skewfromleft' || anim == 'skewfromleftshort') skwX =  85;

        if (anim.split('custom').length > 1){
            var animId = anim.split('-')[1],
                params = this.getCustomAnimation(animId);
            target.tween = TweenLite.fromTo(target, speed,
                {
                    scaleX:     parseInt(params.scalex) / 100,
                    scaleY:     parseInt(params.scaley) / 100,
                    rotationX:  parseInt(params.rotax),
                    rotationY:  parseInt(params.rotay),
                    rotationZ:  parseInt(params.rotaz),
                    x:          parseInt(params.movex),
                    y:          parseInt(params.movey),
                    z:          parseInt(params.movez) + 1,
                    skewX:      parseInt(params.skewx),
                    skewY:      parseInt(params.skewy),
                    left:       calcx,
                    top:        calcy,
                    opacity:    parseInt(params.opacity) / 100,
                    transformPerspective:   parseInt(params.perspective),
                    transformOrigin:    parseInt(params.originx) + '% ' + parseInt(params.originy) + '%',
                    visibility: 'hidden'
                },
                {
                    left:       calcx,
                    top:        calcy,
                    x:          0,
                    y:          0,
                    scaleX:     1,
                    scaleY:     1,
                    rotationX:  0,
                    rotationY:  0,
                    rotationZ:  0,
                    skewX:      0,
                    skewY:      0,
                    z:          1,
                    visibility: 'visible',
                    opacity:    1,
                    ease:       ease,
                    overwrite:  'all',
                    onComplete: function(){
                        target.timer = setTimeout(function(){
                            this.setOutAnimation();
                        }.bind(this), 500);
                    }.bind(this)
                }
            );
        }else{
            target.tween = TweenLite.fromTo(target, speed,
                {
                    scale:      sc,
                    rotation:   ro,
                    rotationX:  0,
                    rotationY:  0,
                    rotationZ:  0,
                    x:          0,
                    y:          0,
                    left:       tlxx,
                    top:        tlyy,
                    opacity:    0,
                    z:          1,
                    skewX:      skwX,
                    skewY:      0,
                    transformPerspective: 600,
                    transformOrigin: '50% 50%',
                    visibility: 'visible'
                },
                {
                    left:       calcx,
                    top:        calcy,
                    scale:      1,
                    skewX:      0,
                    rotation:   0,
                    z:          1,
                    x:          0,
                    y:          0,
                    visibility: 'visible',
                    opacity:    1,
                    ease:       ease,
                    overwrite:  'all',
                    onComplete: function(){
                        target.timer = setTimeout(function(){
                            this.setOutAnimation();
                        }.bind(this), 500);
                    }.bind(this)
                }
            );
        }
    },

    setOutAnimation: function(){
        var target = $('animation_preview'),
            p = this.animPos,
            anim = $('layer_endanimation').value || 'auto',
            speed = $('layer_endspeed').value / 1000,
            ease = $('layer_endeasing').value,
            xx = p.left,
            yy = p.top,
            skwX = 0;

        if (anim == 'fadeout' ||
            anim == 'ltr' ||
            anim == 'ltl' ||
            anim == 'str' ||
            anim == 'stl' ||
            anim == 'ltt' ||
            anim == 'ltb' ||
            anim == 'stt' ||
            anim == 'stb' ||
            anim == 'skewtoright' ||
            anim == 'skewtorightshort' ||
            anim == 'skewtoleft' ||
            anim == 'skewtoleftshort'){

            if (anim == 'skewtoright' || anim == 'skewtorightshort'){
                skwX = 35;
            }
            if (anim == 'skewtoleft' || anim == 'skewtoleftshort'){
                skwX =  -35;
            }
            if (anim == 'ltr' || anim == 'skewtoright'){
                xx = 560;
            }else if (anim == 'ltl' || anim == 'skewtoleft'){
                xx = -100;
            }else if (anim == 'ltt'){
                yy = -50;
            }else if (anim == 'ltb'){
                yy = 250;
            }else if (anim == 'str' || anim == 'skewtorightshort'){
                xx = xx + 50;
            } else if (anim == 'stl' || anim == 'skewtoleftshort'){
                xx = xx - 50;
            }else if (anim == 'stt'){
                yy = yy - 50;
            }else if (anim == 'stb'){
                yy = yy + 50;
            }
            if (anim == 'skewtorightshort'){
                xx = 400;
            }
            if (anim == 'skewtoleftshort'){
                xx =  0;
            }

            target.tween = TweenLite.to(target, speed, {
                left:       xx,
                top:        yy,
                scale:      1,
                rotation:   0,
                skewX:      skwX,
                opacity:    0,
                z:          2,
                overwrite:  'auto',
                ease:       ease,
                onComplete: function() {
                    target.timer = setTimeout(function(){
                        this.setInAnimation();
                    }.bind(this), 500);
                }.bind(this)
            });
        }else{
            if (anim.split('custom').length > 1){
                var id = anim.split('-')[1],
                    params = this.getCustomAnimation(id);
                target.tween = TweenLite.fromTo(target, speed,
                    {
                        left:       xx,
                        top:        yy,
                        x:          0,
                        y:          0,
                        scaleX:     1,
                        scaleY:     1,
                        rotationX:  0,
                        rotationY:  0,
                        rotationZ:  0,
                        skewX:      0,
                        skewY:      0,
                        transformPerspective: parseInt(params.perspective),
                        transformOrigin: parseInt(params.originx) + '% ' + parseInt(params.originy) + '%',
                        z:          1,
                        visibility: 'visible',
                        opacity:    1
                    },
                    {
                        scaleX:     parseInt(params.scalex) / 100,
                        scaleY:     parseInt(params.scaley) / 100,
                        rotationX:  parseInt(params.rotax),
                        rotationY:  parseInt(params.rotay),
                        rotationZ:  parseInt(params.rotaz),
                        x:          parseInt(params.movex),
                        y:          parseInt(params.movey),
                        z:          parseInt(params.movez) + 1,
                        skewX:      parseInt(params.skewx),
                        skewY:      parseInt(params.skewy),
                        left:       xx,
                        top:        yy,
                        opacity:    parseInt(params.opacity) / 100,
                        transformPerspective: parseInt(params.perspective),
                        transformOrigin: parseInt(params.originx) + '% ' + parseInt(params.originy) + '%',
                        ease:       ease,
                        onComplete: function() {
                            target.timer = setTimeout(function(){
                                this.setInAnimation();
                            }.bind(this), 500);
                        }.bind(this)
                    }
                );
            }else{
                target.tween.reverse();
                target.timer = setTimeout(function(){
                    this.setInAnimation();
                }.bind(this), speed * 1000 + 1000);
            }
        }
    },

    collectContainer: function(){
        this.container = $('divLayers');
        Event.observe(this.container, 'click', function(ev){
            var elm = Event.element(ev);
            if (elm == this.container){
                this.selectLayer();
            }
        }.bind(this));
        this.list = $('listLayers');
        this.list.sort = 'depth';
    },

    collectBtns: function(){
        this.deleteBtn          = $('deleteLayerBtn') || null;
        this.dupLayerBtn        = $('dupLayerBtn') || null;
        this.editLayerBtn       = $('editLayerBtn') || null;
        this.cInAnimation       = $('cInAnimation') || null;
        this.cNewInAnimation    = $('cNewInAnimation') || null;
        this.cOutAnimation      = $('cOutAnimation') || null;
        this.cNewOutAnimation   = $('cNewOutAnimation') || null;
        this.editStyleBtn       = $('editStyleBtn') || null;
    },

    collectParamsElement: function(){
        this.layerParamsStr.split('|').each(function(param){
            var elm = $('layer_' + param);
            if (elm){
                this.layerParamsElm[param] = elm;
                if (elm.tagName === 'TABLE'){
                    elm.select('a').each(function(a){
                       Event.observe(a, 'click', function(ev){
                           ev.stop();
                           if (elm.hasClassName('disabled')) return false;
                           var e = Event.findElement(ev, 'a');
                           elm.select('a').each(function(aa){
                               aa.removeClassName('selected');
                           });
                           e.addClassName('selected');
                           this.selectedLayer.params.align_hor = e.readAttribute('data-hor');
                           this.selectedLayer.params.align_vert = e.readAttribute('data-ver');
                           this.selectedLayer.params.align = e.readAttribute('data-id');
                           switch (this.selectedLayer.params.align_hor){
                               case 'left':
                               case 'right':
                                   this.selectedLayer.params.left = 10;
                                   this.layerParamsElm.left.value = 10;
                                   break;
                               case 'center':
                                   this.selectedLayer.params.left = 0;
                                   this.layerParamsElm.left.value = 0;
                                   break;
                           }
                           switch (this.selectedLayer.params.align_vert){
                               case 'top':
                               case 'bottom':
                                   this.selectedLayer.params.top = 10;
                                   this.layerParamsElm.top.value = 10;
                                   break;
                               case 'middle':
                                   this.selectedLayer.params.top = 0;
                                   this.layerParamsElm.top.value = 0;
                                   break;
                           }
                           this.checkFullWidthVideo(this.selectedLayer.params);
                           this.updateAlign(this.selectedLayer);
                       }.bind(this));
                    }.bind(this));
                }else{
                    elm.observe('change', function(ev){
                        var e = Event.element(ev);
                        if (this.selectedLayer){
                            var oldValue = this.selectedLayer.params[param];
                            this.selectedLayer.params[param] = e.value;
                            this.updateListItem(this.selectedLayer.params);
                            if (param === 'text'){
                                if (this.selectedLayer.params.type === 'text'){
                                    this.selectedLayer.innerHTML = e.value;
                                }else if (this.selectedLayer.params.type === 'video'){
                                    this.selectedLayer.params.video_data.title = e.value.escapeHTML();
                                    var title = this.selectedLayer.down('span');
                                    if (title) title.update(e.value.escapeHTML());
                                }
                            }else if (param === 'left' || param === 'top'){
                                this.updateAlign(this.selectedLayer);
                            }else if (param === 'style'){
                                var style = e.options[e.selectedIndex].innerHTML;
                                this.selectedLayer.params[param] = style;
                                this.selectedLayer.params['style_id'] = e.value;
                                this.selectedLayer.removeClassName(oldValue);
                                this.selectedLayer.addClassName(style);
                            }else if (param === 'endtime'){
                                this.slider.setValue(parseInt(e.value), 1);
                            }else if (param === 'style_custom'){
                                this.selectedLayer.removeClassName(oldValue);
                                this.selectedLayer.addClassName(e.value);
                            }else if (param === 'hiddenunder' || param == 'resizeme' || param == 'proportional_scale'){
                                this.selectedLayer.params[param] = e.checked;
                            }else if (param === 'corner_left' || param === 'corner_right'){
                                this.updateLayerHtmlCorners(this.selectedLayer.params);
                            }else if (param === 'scaleX'){
                                this.setScale(true, false);
                            }else if (param === 'scaleY'){
                                this.setScale(false, false);
                            }else if (param === 'animation'){
                                if (e.value.indexOf('custom') === 0) enableElement(this.cInAnimation);
                                else disableElement(this.cInAnimation);
                                if (fireEvent) fireEvent($('layer_endanimation'), 'change');
                            }else if (param === 'endanimation'){
                                if (e.value === 'auto'){
                                    if ($('layer_animation').value.indexOf('custom') === 0) enableElement(this.cOutAnimation);
                                    else disableElement(this.cOutAnimation);
                                }else if (e.value.indexOf('custom') === 0) enableElement(this.cOutAnimation);
                                else disableElement(this.cOutAnimation);
                            }
                        }
                    }.bind(this));
                }
            }
        }.bind(this));
    },

    selectLayer: function(){
        var layer;
        if (arguments.length === 1){
            layer = $(arguments[0]);
        }
        if (layer){
            this.selectedLayer = layer;
            this.container.select('.slide_layer').each(function(layer){
                layer.removeClassName('selected');
            });
            layer.addClassName('selected');
            this.list.select('.item').each(function(item){
                item.removeClassName('selected');
            });
            var item = this.list.down('#item_' + layer.params.serial);
            if (item) item.addClassName('selected');
            this.layerParamsStr.split('|').each(function(param){
                this.layerParamsElm[param].checked = layer.params[param];
                this.layerParamsElm[param].value = layer.params[param];
                switch (layer.params.type){
                    case 'video':
                        switch (param){
                            case 'style':
                            case 'link_enable':
                            case 'link_type':
                            case 'link':
                            case 'link_open_in':
                            case 'link_slide':
                            case 'hiddenunder':
                            case 'corner_left':
                            case 'corner_right':
                            case 'alt':
                            case 'scaleX':
                            case 'scaleY':
                            case 'proportional_scale':
                                disableElement(this.layerParamsElm[param]);
                                break;
                            default:
                                enableElement(this.layerParamsElm[param]);
                                break;
                        }
                        break;
                    case 'image':
                        switch (param){
                            case 'style':
                            case 'hiddenunder':
                            case 'corner_left':
                            case 'corner_right':
                                disableElement(this.layerParamsElm[param]);
                                break;
                            default:
                                enableElement(this.layerParamsElm[param]);
                                break;
                        }
                        break;
                    default:
                        switch (param){
                            case 'alt':
                            case 'scaleX':
                            case 'scaleY':
                            case 'proportional_scale':
                                disableElement(this.layerParamsElm[param]);
                                break;
                            default:
                                enableElement(this.layerParamsElm[param]);
                                break;
                        }
                        break;
                }
                if (param === 'link_enable' || param === 'link_type'){
                    if (fireEvent){
                        fireEvent(this.layerParamsElm[param], 'change');
                    }
                }else if (param == 'align'){
                    var table = this.layerParamsElm[param];
                    table.removeClassName('disabled');
                    if (layer.params.align){
                        table.select('a').each(function(a){
                            if (a.readAttribute('data-id') == layer.params.align){
                                a.addClassName('selected');
                            }else a.removeClassName('selected');
                        });
                    }
                }else if (param == 'style'){
                    if (layer.params['style_id']) this.layerParamsElm[param].value = layer.params['style_id'];
                }
            }.bind(this));
            if (this.slider){
                var timeTarget = this.list.down('#item_'+layer.params.serial).down('.time');
                this.slider.setValue(this.selectedLayer.params.endtime || this.delay, 1);
                this.slider.setValue(this.selectedLayer.params.time, 0);
                this.slider.options.onSlide = function(value){
                    if (this.slider.activeHandleIdx === 0){
                        var time = Math.round(value[0]);
                        this.selectedLayer.params.time = time;
                        if (timeTarget) timeTarget.value = time;
                    }else if (this.slider.activeHandleIdx === 1){
                        var end = Math.round(value[1]);
                        this.selectedLayer.params.endtime = end;
                        this.layerParamsElm.endtime.value = end;
                    }
                }.bind(this);
                this.slider.setEnabled();
            }
            this.toggleAutotun(true);
            this.toogleDelete(true);
            this.toogleCustomAnim(true);
            this.toggleEditStyle(true);
        }else{
            this.selectedLayer = null;
            this.toggleAutotun(false);
            this.toogleDelete(false);
            this.toogleCustomAnim(false);
            this.toggleEditStyle(false);
            this.layerParamsStr.split('|').each(function(param){
                this.layerParamsElm[param].disabled = true;
                if (param === 'align'){
                    var table = $('layer_align');
                    table.addClassName('disabled');
                    table.select('a').each(function(a){
                        a.removeClassName('selected');
                    });
                }
            }.bind(this));
            this.container.select('.slide_layer').each(function(elm){
                elm.removeClassName('selected');
            });
            this.list.select('.item').each(function(elm){
                elm.removeClassName('selected');
            });
            if (this.slider) this.slider.setDisabled();
        }
    },

    toggleEditStyle: function(flag){
        if (this.editStyleBtn){
            if (flag){
                if (this.selectedLayer && this.selectedLayer.params.type == 'text') enableElement(this.editStyleBtn);
                else disableElement(this.editStyleBtn);
            }else{
                disableElement(this.editStyleBtn);
            }
        }
    },

    toogleDelete: function(flag){
        if (this.deleteBtn && this.dupLayerBtn && this.editLayerBtn){
            if (flag){
                enableElement(this.deleteBtn);
                enableElement(this.dupLayerBtn);
                if (this.selectedLayer && this.selectedLayer.params.type != 'text'){
                    enableElement(this.editLayerBtn);
                }else{
                    disableElement(this.editLayerBtn);
                }
            }else{
                disableElement(this.deleteBtn);
                disableElement(this.dupLayerBtn);
                disableElement(this.editLayerBtn);
            }
        }
    },

    toogleCustomAnim: function(flag){
        if (this.cInAnimation && this.cOutAnimation){
            if (flag){
                enableElement(this.cInAnimation);
                enableElement(this.cNewInAnimation);
                enableElement(this.cOutAnimation);
                enableElement(this.cNewOutAnimation);
            }else{
                disableElement(this.cInAnimation);
                disableElement(this.cNewInAnimation);
                disableElement(this.cOutAnimation);
                disableElement(this.cNewOutAnimation);
            }
        }
    },

    updateContainer: function(){
        var bgType = $('background_type');
        if (bgType){
            switch (bgType.value){
                case 'external':
                case 'image':
                    this.container.removeClassName('trans_bg');
                    var bgImage = bgType.value === 'image' ? $('image_url') : $('bg_external');
                    if (bgImage && bgImage.value){
                        var url = bgImage.value.indexOf('http') === 0 ? bgImage.value : this.mediaUrl + bgImage.value;
                        this.container.setStyle({
                            backgroundColor: 'transparent',
                            backgroundImage: 'url(' + url + ')'
                        });
                    }else{
                        this.container.setStyle({'background':'transparent'});
                    }
                    break;
                case 'trans':
                    this.container.addClassName('trans_bg');
                    break;
                case 'solid':
                    this.container.removeClassName('trans_bg');
                    var bgColor = $('slide_bg_color');
                    if (bgColor){
                        this.container.setStyle({
                            backgroundImage: '',
                            backgroundColor: '#' + bgColor.value
                        });
                    }
                    break;
            }
        }
        this.updateContainerOpts();
    },

    updateContainerOpts: function(){
        var bgFit = $('bg_fit'),
            bgRepeat = $('bg_repeat'),
            bgPosition = $('bg_position');
        if (bgFit){
            switch (bgFit.value){
                case 'percentage':
                    var bgFitX = $('bg_fit_x'),
                        bgFitY = $('bg_fit_y');
                    if (bgFitX && bgFitY){
                        this.container.setStyle({
                            backgroundSize: parseInt(bgFitX.value) + '% ' + parseInt(bgFitY.value) + '%'
                        });
                    }
                    break;
                default:
                    this.container.setStyle({ backgroundSize: bgFit.value });
                    break;
            }
        }
        if (bgRepeat) this.container.setStyle({ backgroundRepeat: bgRepeat.value });
        if (bgPosition){
            switch (bgPosition.value){
                case 'percentage':
                    var bgPosX = $('bg_position_x'),
                        bgPosY = $('bg_position_y');
                    if (bgPosX && bgPosY){
                        this.container.setStyle({
                            backgroundPosition: parseInt(bgPosX.value) + '% ' + parseInt(bgPosY.value) + '%'
                        });
                    }
                    break;
                default:
                    this.container.setStyle({ backgroundPosition: bgPosition.value });
                    break;
            }
        }
    },

    updateList: function(){
        setTimeout(function(){
            var slider = $('timeline');
            slider.down('.min').innerHTML = '0ms';
            slider.down('.max').innerHTML = this.delay + 'ms';
            this.slider = new Control.Slider(slider.select('.handle'), slider, {
                range: $R(0, this.delay),
                sliderValue: [0, 0],
                restricted: true,
                disabled: true,
                spans: slider.select('.span')
            });
        }.bind(this), 300);
    },

    editLayer: function(){
        if (this.selectedLayer){
            switch (this.selectedLayer.params.type){
                case 'image':
                    var addLayerImageUrl = $('addLayerImageUrl');
                    if (addLayerImageUrl){
                        var url = addLayerImageUrl.value;
                        url = url + 'onInsertCallbackParams/' + this.selectedLayer.params.serial;
                        AM.MediabrowserUtility.openDialog(url, 'editLayerImageWindow', null, null, Translator.translate('Add Image'));
                    }
                    break;
                case 'video':
                    var addLayerVideoUrl = $('addLayerVideoUrl');
                    if (addLayerVideoUrl){
                        var url = addLayerVideoUrl.value;
                        url += 'serial/' + this.selectedLayer.params.serial;
                        AM.MediabrowserUtility.openDialog(url, 'editLayerVideoWindow', null, null, Translator.translate('Add Video'));
                    }
                    break;
            }
        }
    },

    deleteLayer: function(){
        if (this.selectedLayer){
            delete this.layers[this.selectedLayer.params.serial];
            this.selectedLayer.remove();
            var item = this.getItem(this.selectedLayer.params.serial);
            if (item) item.remove();
            delete this.selectedLayer;
            this.count--;
            this.selectLayer();
        }
    },

    deleteAllLayers: function(){
        if (confirm(Translator.translate('Do you really want to delete all the layers?'))){
            this.deleteLayer();
            this.container.update('');
            this.list.update('');
            this.layers = {};
            this.count = 0;
            this.selectLayer();
        }
    },

    getCssForSave: function(obj){
        var array = this.getCssFromObject(this.cssObject, true), params = {};
        array.each(function(i){
            var tmp = i.split(':');
            params[tmp[0]] = tmp[1].toString().trim();
        });
        if (typeof obj === 'object') Object.extend(params, obj);
        if (this.cssUsingHover == 1){
            params['hover'] = 1;
            var array2 = this.getCssFromObject(this.cssHover, true);
            array2.each(function(i){
                var tmp = i.split(':');
                params['__' + tmp[0]] = tmp[1].toString().trim();
            });
        }
        return params;
    },

    saveAsLayerCss: function(windowId){
        if (this.cssCM){
            var name = prompt(Translator.translate('Please enter name (alphabet only, no whitespace)'));
            if (name){
                if (Validation.get('validate-alpha').test(name)){
                    disableElement($('btnCssSaveAs'));
                    disableElement($('btnCssSave'));
                    var params = this.getCssForSave({name: name});
                    new Ajax.Request(this.css_save_url, {
                        method: 'post',
                        parameters: params,
                        onSuccess: function(transport){
                            var response = transport.responseText.evalJSON();
                            if (response.success){
                                Windows.close(windowId);
                                this.updateCssNew();
                                var style = $('layer_style');
                                style.options.add(new Option(name, response.id));
                                this.selectedLayer.params['style_id'] = response.id;
                                this.selectedLayer.params['style'] = name;
                                this.layerParamsElm['style'].value = response.id;
                            }
                        }.bind(this)
                    });
                }else{
                    alert(Translator.translate('Name invalid, try again'));
                    this.saveAsLayerCss();
                }
            }
        }
    },

    saveLayerCss: function(windowId, id){
        if (this.cssCM){
            disableElement($('btnCssSave'));
            var params = this.getCssForSave({id: id});
            new Ajax.Request(this.css_save_url, {
                method: 'post',
                parameters: params,
                onSuccess: function(){
                    Windows.close(windowId);
                    this.updateCssNew();
                }.bind(this)
            });
        }
    },

    updateCssNew: function(){
        var css = new Element('link', {
            rel: 'stylesheet',
            type: 'text/css',
            href: this.css_url
        });
        var head = $$('head')[0];
        head.appendChild(css);
    },

    addLayerText: function(){
        var layer = {
            text: 'Text ' + (this.index + 1),
            type: 'text',
            style: this.layerParamsElm.style.options[0].innerHTML,
            style_id: this.layerParamsElm.style.options[0].value
        };
        this.addLayer(layer);
    },

    addLayerImage: function(url, id){
        if (id){
            var layer = this.selectedLayer.params;
            layer.image_url = url;
            this.updateLayerHtml(layer);
        }else{
            var layer = {
                style: '',
                text: 'Image ' + (this.index + 1),
                type: 'image',
                image_url: url
            };
            this.addLayer(layer);
        }
    },

    addLayerVideo: function(windowId){
        if (editForm && editForm.validate()){
            var videoData           = this.videoData || {};
            this.videoParamsStr.split('|').each(function(param){
                var elm = $('video_' + param);
                if (elm && elm.readAttribute('type') == 'checkbox') videoData[param] = elm.checked;
                else if (elm) videoData[param] = elm.value.trim();
            });
            videoData.title         = $('video_title').value;
            videoData.video_type    = $('video_type').value;

            var serial = $('video_serial'),
                layer = {};

            if (serial && serial.value){
                layer = this.layers[serial.value];
                Object.extend(layer.video_data, videoData);
                layer.video_type    = layer.video_data.video_type;
                layer.video_width   = layer.video_data.width;
                layer.video_height  = layer.video_data.height;
                switch (layer.video_type){
                    case 'youtube':
                    case 'vimeo':
                        layer.video_id      = layer.video_data.id;
                        layer.video_title   = layer.video_data.title;
                        layer.video_image_url = layer.video_data.thumb_medium.url;
                        break;
                    case 'html5':
                        videoData.urlPoster = $('video_poster').value;
                        videoData.urlMp4    = $('video_mp4').value;
                        videoData.urlWebm   = $('video_webm').value;
                        videoData.urlOgv    = $('video_ogv').value;
                        if (!videoData.urlMp4 && !videoData.urlOgv && !videoData.urlWebm){
                            alert(Translator.translate('No video source found!'));
                            return;
                        }
                        videoData.title     = videoData.title || Translator.translate('HTML5 Video');
                        layer.video_image_url = videoData.urlPoster;
                        break;
                }
                Object.extend(layer.video_data, videoData);
                layer.video_args    = layer.video_data.args;
                layer.text          = layer.video_data.title;
                this.checkFullWidthVideo(layer);
                this.updateLayerHtml(layer);
                this.updateListItem(layer);
            }else{
                layer.type          = 'video';
                layer.style         = '';
                layer.video_type    = videoData.video_type;
                switch (layer.video_type){
                    case 'youtube':
                    case 'vimeo':
                        layer.video_id      = videoData.id;
                        layer.video_title   = videoData.title;
                        layer.video_image_url = videoData.thumb_medium.url;
                        break;
                    case 'html5':
                        videoData.urlPoster = $('video_poster').value;
                        videoData.urlMp4    = $('video_mp4').value;
                        videoData.urlWebm   = $('video_webm').value;
                        videoData.urlOgv    = $('video_ogv').value;
                        if (!videoData.urlMp4 && !videoData.urlOgv && !videoData.urlWebm){
                            alert(Translator.translate('No video source found!'));
                            return;
                        }
                        videoData.title     = videoData.title || Translator.translate('HTML5 Video');
                        layer.video_image_url = videoData.urlPoster;
                        break;
                }
                layer.video_width   = videoData.width;
                layer.video_height  = videoData.height;
                layer.video_data    = videoData;
                layer.video_args    = videoData.args;
                layer.text          = videoData.title;
                this.addLayer(layer);
            }
            this.videoData = null;
            Windows.close(windowId);
        }
    },

    onYoutubeCallback: function(obj){
        var entry = obj.entry;
        var data = {};

        data.id = this.lastVideoId;
        data.video_type = 'youtube';
        data.title = entry.title.$t;
        data.author = entry.author[0].name.$t;
        data.link = entry.link[0].href;
        var thumbnails = entry.media$group.media$thumbnail;
        data.thumb_small = {url:thumbnails[0].url, width:thumbnails[0].width, height:thumbnails[0].height};
        data.thumb_medium = {url:thumbnails[1].url, width:thumbnails[1].width, height:thumbnails[1].height};
        data.thumb_big = {url:thumbnails[2].url, width:thumbnails[2].width, height:thumbnails[2].height};

        this.videoData = data;
        this.updateVideoView(data);
        this.updateVieoControl(data);
    },

    onVimeoCallback: function(obj){
        obj = obj[0];
        var data = {};

        data.video_type = "vimeo";
        data.id = obj.id;
        data.title = obj.title;
        data.link = obj.url;
        data.author = obj.user_name;
        data.thumb_large = {url:obj.thumbnail_large, width:640, height:360};
        data.thumb_medium = {url:obj.thumbnail_medium, width:200, height:150};
        data.thumb_small = {url:obj.thumbnail_small, width:100, height:75};

        this.videoData = data;
        this.updateVideoView(data);
        this.updateVieoControl(data);
    },

    changeVideoType: function(){
        var container = $('videoControl');
        var content = $('videoView');
        container.hide();
        content.update('');
        $('videoLoading').hide();
    },

    onChangeVideoType: function(elm){
        if (elm.value === 'html5')
            this.toggleVideoForm(true);
        else
            this.toggleVideoForm(false);
        $('video_src').value = '';
        this.updateVideoView(null);
    },

    onChangeVideoFullWidth: function(elm){
        var fullwidth = elm.checked;
        'width|height'.split('|').each(function(param){
            var elm = $('video_' + param);
            if (elm){
                if (fullwidth) disableElement(elm);
                else enableElement(elm);
            }
        });
    },

    toggleVideoForm: function(flag){
        this.videoParamsStr.split('|').each(function(param){
            var elm = $('video_' + param);
            if (elm){
                if (flag) enableElement(elm);
                else disableElement(elm);
                if (fireEvent) fireEvent(elm, 'change');
            }
        });
    },

    assignVideoForm: function(serial){
        if (serial){
            var layer = this.layers[serial].video_data;
            $('video_type').value = layer.video_type;
            if (fireEvent) fireEvent($('video_type'), 'change');
            switch (layer.video_type){
                case 'youtube':
                case 'vimeo':
                    this.toggleVideoForm(true);
                    break;
                case 'html5':
                    $('video_poster').value = layer.urlPoster;
                    $('video_mp4').value = layer.urlMp4;
                    $('video_webm').value = layer.urlWebm;
                    $('video_ogv').value = layer.urlOgv;
                    break;
            }
            this.videoParamsStr.split('|').each(function(param){
                var elm = $('video_' + param);
                if (elm){
                    elm.value = layer[param];
                    elm.checked = layer[param];
                    if (fireEvent) fireEvent(elm, 'change');
                }
            }.bind(this));
            $('video_src').value = layer.id;
            this.updateVideoView(layer);
        }
    },

    updateVieoControl: function(data){
        switch (data.video_type){
            case 'youtube':
                $('video_args').value = 'hd=1&wmode=opaque&controls=1&showinfo=0;rel=0;';
                break;
            case 'vimeo':
                $('video_args').value = 'title=0&byline=0&portrait=0;api=1;';
                break;
        }
        $('videoLoading').hide();
        this.toggleVideoForm(true);
    },

    updateVideoView: function(data){
        var content = $('video_thumb_wrapper');
        var title = $('video_title');
        if (data){
            title.value = data.title;
            content.update('');
            switch (data.video_type){
                case 'youtube':
                case 'vimeo':
                    var thumb = data.thumb_medium;
                    var img = new Element('img', {src:thumb.url, width:thumb.width+'px', height:thumb.height+'px'});
                    img.setStyle({border:'1px solid #000'});
                    content.insert(img);
                    break;
                case 'html5':
                    break;
            }

        }else{
            content.update('');
            title.value = '';
        }
    },

    onSelectHtml5Video: function(url, id){
        Windows.close(id);
    },

    searchVideo: function(){
        var videoType = $('video_type').value;
        var videoSrc = $('video_src').value.trim();
        var videoId = this.getVideoId(videoType, videoSrc);
        if (videoId){
            this.lastVideoId = videoId;
            var head = $$('head')[0];
            var script = new Element('script', {type:'text/javascript'});
            switch (videoType){
                case 'youtube':
                    var urlAPI = 'https://gdata.youtube.com/feeds/api/videos/'+
                        videoId + '?v=2&alt=json-in-script&callback=revLayer.onYoutubeCallback';
                    script.src = urlAPI;
                    head.appendChild(script);
                    break;
                case 'vimeo':
                    var urlAPI = 'http://vimeo.com/api/v2/video/'+
                        videoId + '.json?callback=revLayer.onVimeoCallback';
                    script.src = urlAPI;
                    head.appendChild(script);
                    break;
            }
            setTimeout(function(){
                $('videoLoading') && $('videoLoading').hide();
            }, 5000);
            $('videoLoading').show();
        }
    },

    getVideoId: function(service, url){
        switch (service){
            case 'youtube':
                var video_id = url.split('v=')[1];
                if(video_id){
                    var ampersandPosition = video_id.indexOf('&');
                    if(ampersandPosition != -1) {
                        video_id = video_id.substring(0, ampersandPosition);
                    }
                }else{
                    video_id = url;
                }
                return video_id;
                break;
            case 'vimeo':
                var video_id = url.replace(/[^0-9]+/g, '');
                return video_id;
                break;
        }
        return null;
    },

    duplicateLayer: function(){
        if (this.selectedLayer){
            var layer = Object.clone(this.selectedLayer.params);
            layer.left += 20;
            layer.top += 20;
            layer.serial = undefined;
            layer.time = undefined;
            layer.order = this.count + 1;
            this.addLayer(layer);
        }
    },

    previewSlide: function(){

    },

    save: function(back){
        if (this.form && this.form.validate()){
            var form = this.form.validator.form;
            var url = form.action;
            var data = form.serialize(true);
            if (data['transitions[]']){
                data['slide_transition'] = data['transitions[]'].join(',');
                delete data['transitions[]'];
            }
            data.layers = JSON.stringify(this.layers);
            new Ajax.Request(url, {
                method: 'post',
                parameters: data,
                onSuccess: function(transport){
                    if (back) window.location.reload();
                    else if (transport.responseText.indexOf('http://') === 0){
                        window.location.href = transport.responseText;
                    }
                }
            });
        }
    },

    addLayer: function(layer){
        if (layer.order         == undefined) layer.order = this.index + 1;
        if (layer.left          == undefined) layer.left = 10;
        if (layer.top           == undefined) layer.top = 10;
        if (layer.scaleX        == undefined) layer.scaleX = '';
        if (layer.scaleY        == undefined) layer.scaleY = '';
        if (layer.animation     == undefined) layer.animation = $('layer_animation').value;
        if (layer.easing        == undefined) layer.easing = $('layer_easing').value;
        if (layer.speed         == undefined) layer.speed = 500;
        if (layer.align_hor     == undefined) layer.align_hor = 'left';
        if (layer.align_vert    == undefined) layer.align_vert = 'top';
        if (layer.align         == undefined) layer.align = '1';
        if (layer.hiddenunder   == undefined) layer.hiddenunder = false;
        if (layer.resizeme      == undefined) layer.resizeme = true;
        if (layer.link_enable   == undefined) layer.link_enable = 'false';
        if (layer.link_type     == undefined) layer.link_type = 'regular';
        if (layer.link          == undefined) layer.link = '';
        if (layer.link_open_in  == undefined) layer.link_open_in = 'same';
        if (layer.link_slide    == undefined) layer.link_slide = 'nothing';
        if (layer.scrollunder_offset == undefined) layer.scrollunder_offset = '';
        if (layer.style         == undefined) layer.style = $('layer_style').options.item(0).innerHTML;
        if (layer.style_custom  == undefined) layer.style_custom = '';
        if (layer.time          == undefined){
            var time = 500 + (300 * this.count);
            layer.time = time > this.delay ? this.delay : time;
        }
        if (layer.endtime       == undefined) layer.endtime = '';
        if (layer.endspeed      == undefined) layer.endspeed = 500;
        if (layer.endanimation  == undefined) layer.endanimation = $('layer_endanimation').value;
        if (layer.endeasing     == undefined) layer.endeasing = $('layer_endeasing').value;
        if (layer.corner_left   == undefined) layer.corner_left = $('layer_corner_left').value;
        if (layer.corner_right  == undefined) layer.corner_right = $('layer_corner_right').value;
        if (layer.id            == undefined) layer.id = '';
        if (layer.classes       == undefined) layer.classes = '';
        if (layer.title         == undefined) layer.title = '';
        if (layer.rel           == undefined) layer.rel = '';
        if (layer.alt           == undefined) layer.alt = '';
        layer.serial = this.index + 1;
        layer.top = Math.round(layer.top);
        layer.left = Math.round(layer.left);
        layer.sort = null;
        this.layers[layer.serial] = layer;
        this.checkFullWidthVideo(layer);
        var htmlLayer = this.renderLayerHtml(layer);
        htmlLayer.params = layer;
        this.container.insert(htmlLayer);
        this.bindLayerEvent(htmlLayer);
        setTimeout(function(){
            this.updateAlign(htmlLayer);
            this.updateLayerHtmlCorners(layer);
            //this.selectLayer(htmlLayer);
        }.bind(this), 200);
        this.addListItem(layer);
        this.index++;
        this.count++;
    },

    checkFullWidthVideo: function(layer){
        if (layer.type == 'video' && layer.video_data && layer.video_data.fullwidth === true){
            layer.top = 0;
            layer.left = 0;
            layer.align_hor = 'left';
            layer.align_vert = 'top';
            layer.video_height = this.container.getHeight();
            layer.video_width = this.container.getWidth();
            return layer;
        }
    },

    getLayer: function(id){
        return this.container.down('#slide_layer_' + id);
    },

    getItem: function(id){
        return this.list.down('#item_' + id);
    },

    sortLayerItem: function(btn, by){
        this.list.sort = by;
        btn = $(btn);
        btn.up().select('button').invoke('addClassName', 'normal');
        btn.removeClassName('normal');
        var items = [];
        for(var i in this.layers){
            var item = this.layers[i];
            item.sort = by;
            items.push(item);
        }
        switch (by){
            case 'time':
                items.sort(function(a, b){
                    return a.time - b.time;
                });
                break;
            case 'depth':
                items.sort(function(a, b){
                    return a.order - b.order;
                });
                break;
        }
        this.list.update('');
        items.each(function(item){
            this.addListItem(item);
        }, this);
    },

    updateListItem: function(layer){
        var item = this.list.down('#item_' + layer.serial);
        if (item){
            item.down('.name').innerHTML = this.getListItemName(layer);
        }
    },

    getListItemName: function(layer){
        switch (layer.video_type){
            case 'youtube':
                return 'Youtube: ' + layer.text.escapeHTML();
                break;
            case 'vimeo':
                return 'Vimeo: ' + layer.text.escapeHTML();
                break;
            case 'html5':
                return 'Video: ' + layer.text.escapeHTML();
                break;
            default:
                return layer.text.escapeHTML();
                break;
        }
    },

    addListItem: function(layer){
        layer.sort = layer.sort || this.list.sort || 'depth';

        var item = new Element('div', {
            'class': 'item',
            id: 'item_'+layer.serial,
            title: Translator.translate('Drag to change layer depth')
        });

        var name = new Element('div', {'class':'name'});
        name.innerHTML = this.getListItemName(layer);

        var order = new Element('input', {
            type: 'text',
            readonly: 'readonly',
            'class':'input-text order validate-number',
            title: Translator.translate('Layer Depth')
        });
        order.value = layer.order;
        if (layer.sort === 'time') order.addClassName('fade');

        var time = new Element('input', {
            type: 'text',
            'class': 'input-text time validate-number',
            title: Translator.translate('Edit Start Time')
        });
        time.value = layer.time;
        if (layer.sort === 'depth'){
            time.addClassName('fade');
        }

        var arrow = new Element('span', {'class': 'arrow'});
        Event.observe(time, 'change', function(ev){
            var elm = Event.findElement(ev, 'input');
            layer.time = Number(elm.value);
            this.slider.setValue(parseInt(elm.value), 0);
        }.bind(this));

        var eye = new Element('span', {
            'class': 'eye',
            title: Translator.translate('Click to Show / Hide layer')
        });
        Event.observe(eye, 'click', function(ev){
            var elm = Event.findElement(ev, 'div.item');
            var target = this.getLayer(elm.params.serial);
            if (target){
                elm.toggleClassName('hide');
                target.toggleClassName('hide');
            }
        }.bind(this));

        var right = new Element('div', {'class': 'right'});
        right.insert(order);
        right.insert(time);
        right.insert(eye);
        right.insert(arrow);
        item.insert(name);
        item.insert(right);
        item.params = layer;

        this.list.insert(item);
        this.bindListItemEvent(item);
        this.bindListEvent(this.list);
    },

    bindListItemEvent: function(item){
        Event.observe(item, 'click', function(ev){
            var elm = Event.findElement(ev, 'div.item');
            var target = this.getLayer(elm.params.serial);
            if (target){
                this.selectLayer(target);
            }
        }.bind(this));
    },

    bindListEvent: function(list){
        Sortable.create(list, {
            tag: 'div',
            onUpdate: function(){
                this.reorderLayers();
            }.bind(this)
        });
    },

    reorderLayers: function(){
        switch (this.list.sort){
            case 'depth':
                var order = 1;
                this.list.select('.item').each(function(item){
                    var layerHtml = this.getLayer(item.params.serial);
                    if (layerHtml){
                        layerHtml.params.order = order++;
                        this.updateLayerHtml(layerHtml.params);
                        this.updateListHtml(layerHtml.params);
                    }
                }, this);
                break;
            case 'time':
                var times = [];
                for(var i in this.layers) times.push(this.layers[i].time);
                times.sort(function(a, b){ return a - b;});
                this.list.select('.item').each(function(item, i){
                    var layer = this.layers[item.params.serial];
                    layer.time = times[i];
                    this.updateListHtml(layer);
                }, this);
                break;
        }
    },

    bindLayerEvent: function(elm){
        if (elm){
            var cDimens = this.container.getDimensions();
            var dimens = elm.getDimensions();
            new Draggable(elm, {
                snap: [1,1],
                change: function(drag){
                    var element = drag.element;
                    var position = element.positionedOffset();
                    var posTop = position[1];
                    var posLeft = position[0];
                    var updateX, updateY;
                    switch (element.params.align_hor){
                        case 'left':
                            updateX = posLeft;
                            break;
                        case 'right':
                            updateX = cDimens.width - posLeft - dimens.width;
                            break;
                        case 'center':
                            updateX = posLeft - (cDimens.width - dimens.width) / 2;
                            updateX = Math.round(updateX);
                            break;
                    }
                    switch (element.params.align_vert){
                        case 'top':
                            updateY = posTop;
                            break;
                        case 'bottom':
                            updateY = cDimens.height - posTop - dimens.height;
                            break;
                        case 'middle':
                            updateY = posTop - (cDimens.height - dimens.height) / 2;
                            updateY = Math.round(updateY);
                            break;
                    }
                    this.layerParamsElm.left.value = updateX;
                    this.layerParamsElm.top.value = updateY;
                    element.params.left = updateX;
                    element.params.top = updateY;
                    elm.setStyle({
                        'right': 'auto',
                        'bottom': 'auto'
                    });
                }.bind(this)
            });
            Event.observe(elm, 'mousedown', function(ev){
                var layer = Event.findElement(ev, 'div.slide_layer');
                if (this.selectedLayer != layer){
                    this.selectLayer(layer);
                }
            }.bind(this));
        }
    },

    updateAlign: function(layer){
        if (layer){
            var target = layer.getDimensions();
            var container = this.container.getDimensions();
            var css = {};

            switch (layer.params.align_hor){
                default:
                case 'left':
                    css.right = 'auto';
                    css.left = layer.params.left + 'px';
                    break;
                case 'right':
                    css.left = 'auto';
                    css.right = layer.params.left + 'px';
                    break;
                case 'center':
                    var realLeft = Math.round((container.width - target.width) / 2) + parseInt(layer.params.left);
                    css.left = realLeft + 'px';
                    css.right = 'auto';
                    break;
            }
            switch (layer.params.align_vert){
                default:
                case 'top':
                    css.bottom = 'auto';
                    css.top = layer.params.top + 'px';
                    break;
                case 'bottom':
                    css.top = 'auto';
                    css.bottom = layer.params.top + 'px';
                    break;
                case 'middle':
                    var realTop = Math.round((container.height - target.height) / 2) + parseInt(layer.params.top);
                    css.top = realTop + 'px';
                    css.bottom = 'auto';
                    break;
            }

            this.layerParamsElm.left.value = layer.params.left;
            this.layerParamsElm.top.value = layer.params.top;
            layer.setStyle(css);
        }
    },

    renderLayerHtml: function(layer){
        var elm = new Element('div', {
            id: 'slide_layer_' + layer.serial,
            'class': 'slide_layer tp-caption '+ layer.style +' '+ layer.style_custom
        });
        elm.setStyle({
            zIndex: Number(layer.order),
            position: 'absolute'
        });
        switch(layer.type){
            case 'image':
                var url = layer.image_url.indexOf('http') === 0 ? layer.image_url : this.mediaUrl + layer.image_url;
                var img = new Element('img', {src: url});
                layer.scaleX && img.setStyle({width: layer.scaleX + 'px'});
                layer.scaleY && img.setStyle({height: layer.scaleY + 'px'});
                elm.insert(img);
                break;
            case 'text':
            default:
                elm.innerHTML = layer.text;
                break;
            case 'video':
                var video = this.renderVideoLayerHtml(layer);
                elm.insert(video);
                break;
        }
        return elm;
    },

    updateListHtml: function(layer){
        var itemHtml = this.getItem(layer.serial);
        if (itemHtml){
            itemHtml.down('input.order').value = layer.order;
            itemHtml.down('input.time').value = layer.time;
        }
    },

    updateLayerHtml: function(layer){
        var layerHtml = this.getLayer(layer.serial);
        if (layerHtml){
            layerHtml.setStyle({zIndex:layer.order});
            switch (layer.type){
                case 'image':
                    var url = layer.image_url.indexOf('http') === 0 ? layer.image_url : this.mediaUrl + layer.image_url;
                    layerHtml.down('img').src = url;
                    setTimeout(function(){
                        this.updateAlign(layerHtml);
                    }.bind(this), 100);
                    break;
                case 'video':
                    var video = this.renderVideoLayerHtml(layer);
                    layerHtml.update(video);
                    setTimeout(function(){
                        this.updateAlign(layerHtml);
                    }.bind(this), 100);
                    break;
            }
        }
    },

    updateLayerHtmlScale: function(layer){
        var layerHtml = this.getLayer(layer.serial),
            img = layerHtml.down('img');

        if (img){
            img.setStyle({
                width: layer.scaleX + 'px',
                height: layer.scaleY + 'px'
            });
        }
    },

    updateLayerHtmlCorners: function(layer){
        var layerHtml = this.getLayer(layer.serial),
            ncch = layerHtml.offsetHeight,
            bgcol = layerHtml.getStyle('backgroundColor');

        if (layerHtml.down('.frontcorner')) layerHtml.down('.frontcorner').remove();
        if (layerHtml.down('.frontcornertop')) layerHtml.down('.frontcornertop').remove();
        switch(layer.corner_left){
            case "curved":
                if (!layerHtml.down('.frontcorner')) layerHtml.insert({bottom: '<div class="frontcorner"></div>'});
                break;
            case "reverced":
                if (!layerHtml.down('.frontcornertop')) layerHtml.insert({bottom: '<div class="frontcornertop"></div>'});
                break;
        }

        if (layerHtml.down('.backcorner')) layerHtml.down('.backcorner').remove();
        if (layerHtml.down('.backcornertop')) layerHtml.down('.backcornertop').remove();
        switch(layer.corner_right){
            case "curved":
                if (!layerHtml.down('.backcorner')) layerHtml.insert({bottom: '<div class="backcorner"></div>'});
                break;
            case "reverced":
                if (!layerHtml.down('.backcornertop')) layerHtml.insert({bottom: '<div class="backcornertop"></div>'});
                break;
        }


        layerHtml.down('.frontcorner') && layerHtml.down('.frontcorner').setStyle({
            'borderWidth':      ncch + "px",
            'left':             (0-ncch) + 'px',
            'borderRight':      '0px solid transparent',
            'borderTopColor':   bgcol
        });

        layerHtml.down(".frontcornertop") && layerHtml.down(".frontcornertop").setStyle({
            'borderWidth':      ncch + "px",
            'left':             (0-ncch) + 'px',
            'borderRight':      '0px solid transparent',
            'borderBottomColor':bgcol
        });

        layerHtml.down('.backcorner') && layerHtml.down('.backcorner').setStyle({
            'borderWidth':      ncch + "px",
            'right':            (0-ncch) + 'px',
            'borderLeft':       '0px solid transparent',
            'borderBottomColor':bgcol
        });

        layerHtml.down('.backcornertop') && layerHtml.down('.backcornertop').setStyle({
            'borderWidth':      ncch + "px",
            'right':            (0-ncch) + 'px',
            'borderLeft':       '0px solid transparent',
            'borderTopColor':   bgcol
        });
    },

    renderVideoLayerHtml: function(layer){
        if (layer){
            var style = {
                width: layer.video_width+'px',
                height: layer.video_height+'px'
            };
            if (layer.video_image_url) style.background = '#000 url('+layer.video_image_url+') no-repeat center center';
            else style.background = '#000';
            var video = new Element('div', {'class': 'slide_layer_video'});
            video.setStyle(style);
            switch (layer.video_type){
                case 'html5':
                    if (!layer.video_image_url){
                        var span = new Element('span');
                        span.update(layer.text);
                        video.update(span);
                    }
                    break;
            }
            return video;
        }
        return null;
    }
}