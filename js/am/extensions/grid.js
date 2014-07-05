/**
 * @category    AM
 * @package     AM_Extensions
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */
var AM = AM || {};

AM.InPlaceEditor = Class.create(Ajax.InPlaceEditor, {
    createControl: function(mode, handler, extraClasses) {
        var control = this.options[mode + 'Control'];
        var text = this.options[mode + 'Text'];
        if ('button' == control) {
            if ('ok' == mode)
                var btn = new Element('button', {type:'submit'});
            else
                var btn = new Element('button', {type:'button'});
            var span = new Element('span');
            span.update(text);
            btn.update(span);
            btn.addClassName('editor_' + mode + '_button');
            btn.observe('click', handler);
            this._form.appendChild(btn);
            this._controls[mode] = btn;
        } else if ('link' == control) {
            var link = document.createElement('a');
            link.href = '#';
            link.appendChild(document.createTextNode(text));
            link.onclick = 'cancel' == mode ? this._boundCancelHandler : this._boundSubmitHandler;
            link.className = 'editor_' + mode + '_link';
            if (extraClasses)
                link.className += ' ' + extraClasses;
            this._form.appendChild(link);
            this._controls[mode] = link;
        }
    },

    createEditField: function() {
        var text = this.options.loadTextURL ? this.options.loadingText : this.getText();
        var fld;
        if (1 >= this.options.rows && !/\r|\n/.test(this.getText())) {
            fld = new Element('input');
            fld.type = 'text';
            var size = this.options.size || this.options.cols || 0;
            if (0 < size) fld.size = size;
        } else {
            fld = document.createElement('textarea');
            fld.rows = (1 >= this.options.rows ? this.options.autoRows : this.options.rows);
            fld.cols = this.options.cols || 40;
        }
        fld.setStyle({
            'width': '80px',
            'verticalAlign': 'top'
        });
        fld.name = this.options.paramName;
        fld.value = text; // No HTML breaks conversion anymore
        fld.className = 'editor_field input-text';
        if (this.options.submitOnBlur)
            fld.onblur = this._boundSubmitHandler;
        this._controls.editor = fld;
        if (this.options.loadTextURL)
            this.loadExternalText();
        this._form.appendChild(this._controls.editor);
    }
});

function bindInlineEdit(input){
    var attr    = $(input).readAttribute('attr'),
        entity  = $(input).readAttribute('entity'),
        control = $(input).readAttribute('control'),
        saveUrl = $(input).readAttribute('saveUrl');

    switch (control){
        case 'text':
            new AM.InPlaceEditor(input, saveUrl, {
                callback: function(form, value){
                    return 'entity=' +entity+ '&attr=' +attr+ '&value=' +encodeURIComponent(value);
                },
                onComplete: function(transport){
                    if (typeof transport === 'object'){
                        var response = transport.responseText.evalJSON();
                        if (response.message) alert(response.message);
                        else $(input).update(response.value);
                    }
                },
                onFailure: function(){
                    alert(Translator.translate('Error communicating with the server'));
                },
                cancelControl: 'button',
                cancelText: 'x',
                okText: 'Ok',
                ajaxOptions: {loaderArea: false}
            });
            break;
    }
}