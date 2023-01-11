Ext.namespace('userlocation.combo');
Ext.namespace('userlocation.field');

userlocation.combo.ComboBoxDefault = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        assertValue : function(){
            var val = this.getRawValue(),
                rec;
            if(this.valueField && Ext.isDefined(this.value)){
                rec = this.findRecord(this.valueField, this.value);
            }
            /* fix for https://github.com/bezumkin/miniShop2/pull/350
            if(!rec || rec.get(this.displayField) != val){
                rec = this.findRecord(this.displayField, val);
            }*/
            if(!rec && this.forceSelection){
                if(val.length > 0 && val != this.emptyText){
                    this.el.dom.value = Ext.value(this.lastSelectionText, '');
                    this.applyEmptyText();
                }else{
                    this.clearValue();
                }
            }else{
                if(rec && this.valueField){
                    if (this.value == val){
                        return;
                    }
                    val = rec.get(this.valueField || this.displayField);
                }
                this.setValue(val);
            }
        },
        onRender: function (c, a) {
            this.constructor.prototype.onRender.apply(this, arguments);
            this.el.dom.setAttribute("autocomplete", "off");
        },
    });
    userlocation.combo.ComboBoxDefault.superclass.constructor.call(this, config);
};
Ext.extend(userlocation.combo.ComboBoxDefault, MODx.combo.ComboBox);
Ext.reg('userlocation-combo-combobox-default', userlocation.combo.ComboBoxDefault);


userlocation.field.TextFieldDefault = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        onRender: function (c, a) {
            this.constructor.prototype.onRender.apply(this, arguments);
            this.el.dom.setAttribute("autocomplete", "off");
        },
    });
    userlocation.field.TextFieldDefault.superclass.constructor.call(this, config);
};
Ext.extend(userlocation.field.TextFieldDefault, Ext.form.TextField);
Ext.reg('userlocation-field-textfield-default', userlocation.field.TextFieldDefault);


userlocation.combo.Search = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        xtype: 'twintrigger',
        ctCls: 'x-field-search',
        allowBlank: true,
        msgTarget: 'under',
        emptyText: _('search'),
        name: 'query',
        triggerAction: 'all',
        clearBtnCls: 'x-field-search-clear',
        searchBtnCls: 'x-field-search-go',
        onTrigger1Click: this._triggerSearch,
        onTrigger2Click: this._triggerClear,
    });
    userlocation.combo.Search.superclass.constructor.call(this, config);
    this.on('render', function () {
        this.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
            this._triggerSearch();
        }, this);
    });
    this.addEvents('clear', 'search');
};
Ext.extend(userlocation.combo.Search, Ext.form.TwinTriggerField, {

    initComponent: function () {
        Ext.form.TwinTriggerField.superclass.initComponent.call(this);
        this.triggerConfig = {
            tag: 'span',
            cls: 'x-field-search-btns',
            cn: [
                {tag: 'div', cls: 'x-form-trigger ' + this.searchBtnCls},
                {tag: 'div', cls: 'x-form-trigger ' + this.clearBtnCls}
            ]
        };
    },

    _triggerSearch: function () {
        this.fireEvent('search', this);
    },

    _triggerClear: function () {
        this.fireEvent('clear', this);
    },

});
Ext.reg('userlocation-combo-search', userlocation.combo.Search);
Ext.reg('userlocation-field-search', userlocation.combo.Search);


userlocation.combo.Location = function (config) {
    config = config || {};

    if (config.custm) {
        config.triggerConfig = [{
            tag: 'div',
            cls: 'x-field-search-btns',
            style: String.format('width: {0}px;', config.clear ? 62 : 31),
            cn: [{
                tag: 'div',
                cls: 'x-form-trigger x-field-userlocation-location-go'
            }]
        }];
        if (config.clear) {
            config.triggerConfig[0].cn.push({
                tag: 'div',
                cls: 'x-form-trigger x-field-userlocation-location-clear'
            });
        }

        config.initTrigger = function () {
            var ts = this.trigger.select('.x-form-trigger', true);
            this.wrap.setStyle('overflow', 'hidden');
            var triggerField = this;
            ts.each(function (t, all, index) {
                t.hide = function () {
                    var w = triggerField.wrap.getWidth();
                    this.dom.style.display = 'none';
                    triggerField.el.setWidth(w - triggerField.trigger.getWidth());
                };
                t.show = function () {
                    var w = triggerField.wrap.getWidth();
                    this.dom.style.display = '';
                    triggerField.el.setWidth(w - triggerField.trigger.getWidth());
                };
                var triggerIndex = 'Trigger' + (index + 1);

                if (this['hide' + triggerIndex]) {
                    t.dom.style.display = 'none';
                }
                t.on('click', this['on' + triggerIndex + 'Click'], this, {
                    preventDefault: true
                });
                t.addClassOnOver('x-form-trigger-over');
                t.addClassOnClick('x-form-trigger-click');
            }, this);
            this.triggers = ts.elements;
        };
    }
    Ext.applyIf(config, {
        name: config.name || 'location',
        hiddenName: config.name || 'location',
        displayField: 'name',
        valueField: 'id',
        editable: true,
        fields: ['id', 'name', 'type'],
        pageSize: 10,
        //emptyText: _('userlocation_combo_select'),
        emptyText: _('userlocation_select_location'),
        hideMode: 'offsets',
        url: userlocation.config['connector_url'],
        baseParams: {
            action: 'mgr/location/getlist',
            combo: true,
            addall: config.addall || 0,
            novalue: config.novalue || 0,
        },
        tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item" ext:qtip="{description}">',
            '{name}</br><small>{id} - {type}</small>',
            '</div></tpl>',
            {
                compiled: true
            }),
        cls: 'input-combo-search-location',
        clearValue: function () {
            if (this.hiddenField) {
                this.hiddenField.value = '';
            }
            this.setRawValue('');
            this.lastSelectionText = '';
            this.applyEmptyText();
            this.value = '';
            this.fireEvent('select', this, null, 0);
        },

        getTrigger: function (index) {
            return this.triggers[index];
        },

        onTrigger1Click: function () {
            this.onTriggerClick();
        },

        onTrigger2Click: function () {
            this.clearValue();
        }
    });
    userlocation.combo.Location.superclass.constructor.call(this, config);

};
Ext.extend(userlocation.combo.Location, userlocation.combo.ComboBoxDefault);
Ext.reg('userlocation-combo-location', userlocation.combo.Location);



userlocation.combo.Type = function (config) {
    config = config || {};

    if (config.custm) {
        config.triggerConfig = [{
            tag: 'div',
            cls: 'x-field-search-btns',
            style: String.format('width: {0}px;', config.clear ? 62 : 31),
            cn: [{
                tag: 'div',
                cls: 'x-form-trigger x-field-userlocation-type-go'
            }]
        }];
        if (config.clear) {
            config.triggerConfig[0].cn.push({
                tag: 'div',
                cls: 'x-form-trigger x-field-userlocation-type-clear'
            });
        }

        config.initTrigger = function () {
            var ts = this.trigger.select('.x-form-trigger', true);
            this.wrap.setStyle('overflow', 'hidden');
            var triggerField = this;
            ts.each(function (t, all, index) {
                t.hide = function () {
                    var w = triggerField.wrap.getWidth();
                    this.dom.style.display = 'none';
                    triggerField.el.setWidth(w - triggerField.trigger.getWidth());
                };
                t.show = function () {
                    var w = triggerField.wrap.getWidth();
                    this.dom.style.display = '';
                    triggerField.el.setWidth(w - triggerField.trigger.getWidth());
                };
                var triggerIndex = 'Trigger' + (index + 1);

                if (this['hide' + triggerIndex]) {
                    t.dom.style.display = 'none';
                }
                t.on('click', this['on' + triggerIndex + 'Click'], this, {
                    preventDefault: true
                });
                t.addClassOnOver('x-form-trigger-over');
                t.addClassOnClick('x-form-trigger-click');
            }, this);
            this.triggers = ts.elements;
        };
    }
    Ext.applyIf(config, {
        name: config.name || 'type',
        hiddenName: config.name || 'type',
        displayField: 'name',
        valueField: 'id',
        editable: true,
        fields: ['id', 'name', 'type'],
        pageSize: 10,
        //emptyText: _('userlocation_combo_select'),
        emptyText: _('userlocation_select_type'),
        hideMode: 'offsets',
        url: userlocation.config['connector_url'],
        baseParams: {
            action: 'mgr/location/type/getlist',
            combo: true,
            addall: config.addall || 0,
            novalue: config.novalue || 0,
        },
        tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item" ext:qtip="{description}">',
            '{name}',
            '</div></tpl>',
            {
                compiled: true
            }),
        cls: 'input-combo-search-type',
        clearValue: function () {
            if (this.hiddenField) {
                this.hiddenField.value = '';
            }
            this.setRawValue('');
            this.lastSelectionText = '';
            this.applyEmptyText();
            this.value = '';
            this.fireEvent('select', this, null, 0);
        },

        getTrigger: function (index) {
            return this.triggers[index];
        },

        onTrigger1Click: function () {
            this.onTriggerClick();
        },

        onTrigger2Click: function () {
            this.clearValue();
        }
    });
    userlocation.combo.Type.superclass.constructor.call(this, config);

};
Ext.extend(userlocation.combo.Type, userlocation.combo.ComboBoxDefault);
Ext.reg('userlocation-combo-type', userlocation.combo.Type);


userlocation.combo.Resource = function(config) {
    config = config || {};

    if (config.custm) {
        config.triggerConfig = [{
            tag: 'div',
            cls: 'x-field-search-btns',
            style: String.format('width: {0}px;', config.clear?62:31),
            cn: [{
                tag: 'div',
                cls: 'x-form-trigger x-field-userlocation-resource-go'
            }]
        }];
        if (config.clear) {
            config.triggerConfig[0].cn.push({
                tag: 'div',
                cls: 'x-form-trigger x-field-userlocation-resource-clear'
            });
        }

        config.initTrigger = function() {
            var ts = this.trigger.select('.x-form-trigger', true);
            this.wrap.setStyle('overflow', 'hidden');
            var triggerField = this;
            ts.each(function(t, all, index) {
                t.hide = function() {
                    var w = triggerField.wrap.getWidth();
                    this.dom.style.display = 'none';
                    triggerField.el.setWidth(w - triggerField.trigger.getWidth());
                };
                t.show = function() {
                    var w = triggerField.wrap.getWidth();
                    this.dom.style.display = '';
                    triggerField.el.setWidth(w - triggerField.trigger.getWidth());
                };
                var triggerIndex = 'Trigger' + (index + 1);

                if (this['hide' + triggerIndex]) {
                    t.dom.style.display = 'none';
                }
                t.on('click', this['on' + triggerIndex + 'Click'], this, {
                    preventDefault: true
                });
                t.addClassOnOver('x-form-trigger-over');
                t.addClassOnClick('x-form-trigger-click');
            }, this);
            this.triggers = ts.elements;
        };
    }
    Ext.applyIf(config, {
        name: config.name || 'resource',
        hiddenName: config.name || 'resource',
        displayField: 'pagetitle',
        valueField: 'id',
        editable: true,
        fields: ['pagetitle', 'id'],
        pageSize: 10,
        emptyText: _('userlocation_combo_select'),
        hideMode: 'offsets',
        url: userlocation.config.connector_url,
        baseParams: {
            action: 'mgr/misc/resource/getlist',
            combo: true,
            addall: config.addall || 0,
            novalue: config.novalue || 0,
        },
        tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item">',
            '<small>({id})</small> <b>{pagetitle}</b>',
            '</div></tpl>',
            {
                compiled: true
            }),
        cls: 'input-combo-userlocation-resource',
        clearValue: function() {
            if (this.hiddenField) {
                this.hiddenField.value = '';
            }
            this.setRawValue('');
            this.lastSelectionText = '';
            this.applyEmptyText();
            this.value = '';
            this.fireEvent('select', this, null, 0);
        },

        getTrigger: function(index) {
            return this.triggers[index];
        },

        onTrigger1Click: function() {
            this.onTriggerClick();
        },

        onTrigger2Click: function() {
            this.clearValue();
        }
    });
    userlocation.combo.Resource.superclass.constructor.call(this, config);

};
Ext.extend(userlocation.combo.Resource, userlocation.combo.ComboBoxDefault);
Ext.reg('userlocation-combo-resource', userlocation.combo.Resource);


userlocation.combo.Boolean = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        name: config.name || 'boolean',
        hiddenName: config.name || 'boolean',
        store: new Ext.data.SimpleStore({
            fields: ['d','v'],
            data: [[_('yes'), 1],[_('no'), 0]]
        })
    });
    userlocation.combo.Boolean.superclass.constructor.call(this, config);
};
Ext.extend(userlocation.combo.Boolean, MODx.combo.Boolean);
Ext.reg('userlocation-combo-boolean', userlocation.combo.Boolean);


if (typeof Ext.grid.Column.types['checkcolumn'] == 'undefined') {
    Ext.ux.grid.CheckColumn = Ext.extend(Ext.grid.Column, {

        /**
         * @private
         * Process and refire events routed from the GridView's processEvent method.
         */
        processEvent : function(name, e, grid, rowIndex, colIndex){
            if (name == 'mousedown') {
                var record = grid.store.getAt(rowIndex);
                record.set(this.dataIndex, !record.data[this.dataIndex]);
                return false; // Cancel row selection.
            } else {
                return Ext.grid.ActionColumn.superclass.processEvent.apply(this, arguments);
            }
        },

        renderer : function(v, p, record){
            p.css += ' x-grid3-check-col-td';
            return String.format('<div class="x-grid3-check-col{0}">&#160;</div>', v ? '-on' : '');
        },

        // Deprecate use as a plugin. Remove in 4.0
        init: Ext.emptyFn
    });

// register ptype. Deprecate. Remove in 4.0
    Ext.preg('checkcolumn', Ext.ux.grid.CheckColumn);

    Ext.grid.Column.types['checkcolumn'] = Ext.ux.grid.CheckColumn;
}


