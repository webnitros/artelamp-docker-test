ReadLogJson.combo.Search = function (config) {
    config = config || {}
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
    })
    ReadLogJson.combo.Search.superclass.constructor.call(this, config)
    this.on('render', function () {
        this.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
            this._triggerSearch()
        }, this)
    })
    this.addEvents('clear', 'search')
}
Ext.extend(ReadLogJson.combo.Search, Ext.form.TwinTriggerField, {

    initComponent: function () {
        Ext.form.TwinTriggerField.superclass.initComponent.call(this)
        this.triggerConfig = {
            tag: 'span',
            cls: 'x-field-search-btns',
            cn: [
                {tag: 'div', cls: 'x-form-trigger ' + this.searchBtnCls},
                {tag: 'div', cls: 'x-form-trigger ' + this.clearBtnCls}
            ]
        }
    },

    _triggerSearch: function () {
        this.fireEvent('search', this)
    },

    _triggerClear: function () {
        this.fireEvent('clear', this)
    },

})
Ext.reg('readlogjson-combo-search', ReadLogJson.combo.Search)
Ext.reg('readlogjson-field-search', ReadLogJson.combo.Search)

/**
 * Filter Active
 * @param config
 * @constructor
 */
ReadLogJson.combo.Active = function (config) {
    config = config || {}

    if (config.custm) {
        config.triggerConfig = [{
            tag: 'div',
            cls: 'x-field-search-btns',
            style: String.format('width: {0}px;', config.clear ? 62 : 31),
            cn: [{
                tag: 'div',
                cls: 'x-form-trigger x-field-readlogjson-active-go'
            }]
        }]
        if (config.clear) {
            config.triggerConfig[0].cn.push({
                tag: 'div',
                cls: 'x-form-trigger x-field-readlogjson-active-clear'
            })
        }

        config.initTrigger = function () {
            var ts = this.trigger.select('.x-form-trigger', true)
            this.wrap.setStyle('overflow', 'hidden')
            var triggerField = this
            ts.each(function (t, all, index) {
                t.hide = function () {
                    var w = triggerField.wrap.getWidth()
                    this.dom.style.display = 'none'
                    triggerField.el.setWidth(w - triggerField.trigger.getWidth())
                }
                t.show = function () {
                    var w = triggerField.wrap.getWidth()
                    this.dom.style.display = ''
                    triggerField.el.setWidth(w - triggerField.trigger.getWidth())
                }
                var triggerIndex = 'Trigger' + (index + 1)

                if (this['hide' + triggerIndex]) {
                    t.dom.style.display = 'none'
                }
                t.on('click', this['on' + triggerIndex + 'Click'], this, {
                    preventDefault: true
                })
                t.addClassOnOver('x-form-trigger-over')
                t.addClassOnClick('x-form-trigger-click')
            }, this)
            this.triggers = ts.elements
        }
    }
    Ext.applyIf(config, {
        name: config.name || 'active',
        hiddenName: config.name || 'active',
        displayField: 'name',
        valueField: 'value',
        editable: true,
        fields: ['name', 'value'],
        pageSize: 10,
        emptyText: _('readlogjson_combo_select'),
        hideMode: 'offsets',
        url: ReadLogJson.config.connector_url,
        baseParams: {
            action: 'mgr/misc/active/getlist',
            combo: true,
            addall: config.addall || 0
        },
        tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item">',
            '<small>({value})</small> <b>{name}</b></span>',
            '</div></tpl>', {
                compiled: true
            }),
        cls: 'input-combo-readlogjson-active',
        clearValue: function () {
            if (this.hiddenField) {
                this.hiddenField.value = ''
            }
            this.setRawValue('')
            this.lastSelectionText = ''
            this.applyEmptyText()
            this.value = ''
            this.fireEvent('select', this, null, 0)
        },

        getTrigger: function (index) {
            return this.triggers[index]
        },

        onTrigger1Click: function () {
            this.onTriggerClick()
        },

        onTrigger2Click: function () {
            this.clearValue()
        }
    })
    ReadLogJson.combo.Active.superclass.constructor.call(this, config)

}
Ext.extend(ReadLogJson.combo.Active, MODx.combo.ComboBox)
Ext.reg('readlogjson-combo-filter-active', ReadLogJson.combo.Active)

/**
 * Filter Processed
 * @param config
 * @constructor
 */
ReadLogJson.combo.Processed = function (config) {
    config = config || {}

    if (config.custm) {
        config.triggerConfig = [{
            tag: 'div',
            cls: 'x-field-search-btns',
            style: String.format('width: {0}px;', config.clear ? 62 : 31),
            cn: [{
                tag: 'div',
                cls: 'x-form-trigger x-field-readlogjson-active-go'
            }]
        }]
        if (config.clear) {
            config.triggerConfig[0].cn.push({
                tag: 'div',
                cls: 'x-form-trigger x-field-readlogjson-active-clear'
            })
        }

        config.initTrigger = function () {
            var ts = this.trigger.select('.x-form-trigger', true)
            this.wrap.setStyle('overflow', 'hidden')
            var triggerField = this
            ts.each(function (t, all, index) {
                t.hide = function () {
                    var w = triggerField.wrap.getWidth()
                    this.dom.style.display = 'none'
                    triggerField.el.setWidth(w - triggerField.trigger.getWidth())
                }
                t.show = function () {
                    var w = triggerField.wrap.getWidth()
                    this.dom.style.display = ''
                    triggerField.el.setWidth(w - triggerField.trigger.getWidth())
                }
                var triggerIndex = 'Trigger' + (index + 1)

                if (this['hide' + triggerIndex]) {
                    t.dom.style.display = 'none'
                }
                t.on('click', this['on' + triggerIndex + 'Click'], this, {
                    preventDefault: true
                })
                t.addClassOnOver('x-form-trigger-over')
                t.addClassOnClick('x-form-trigger-click')
            }, this)
            this.triggers = ts.elements
        }
    }
    Ext.applyIf(config, {
        name: config.name || 'processed',
        hiddenName: config.name || 'processed',
        displayField: 'name',
        valueField: 'value',
        editable: true,
        fields: ['name', 'value'],
        pageSize: 10,
        emptyText: _('readlogjson_combo_select'),
        hideMode: 'offsets',
        url: ReadLogJson.config.connector_url,
        baseParams: {
            action: 'mgr/misc/processed/getlist',
            combo: true,
            addall: config.addall || 0
        },
        tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item">',
            '<small>({value})</small> <b>{name}</b></span>',
            '</div></tpl>', {
                compiled: true
            }),
        cls: 'input-combo-readlogjson-processed',
        clearValue: function () {
            if (this.hiddenField) {
                this.hiddenField.value = ''
            }
            this.setRawValue('')
            this.lastSelectionText = ''
            this.applyEmptyText()
            this.value = ''
            this.fireEvent('select', this, null, 0)
        },

        getTrigger: function (index) {
            return this.triggers[index]
        },

        onTrigger1Click: function () {
            this.onTriggerClick()
        },

        onTrigger2Click: function () {
            this.clearValue()
        }
    })
    ReadLogJson.combo.Processed.superclass.constructor.call(this, config)

}
Ext.extend(ReadLogJson.combo.Processed, MODx.combo.ComboBox)
Ext.reg('readlogjson-combo-filter-processed', ReadLogJson.combo.Processed)

/**
 * Filter Resource
 * @param config
 * @constructor
 */
ReadLogJson.combo.Resource = function (config) {
    config = config || {}

    if (config.custm) {
        config.triggerConfig = [{
            tag: 'div',
            cls: 'x-field-search-btns',
            style: String.format('width: {0}px;', config.clear ? 62 : 31),
            cn: [{
                tag: 'div',
                cls: 'x-form-trigger x-field-readlogjson-resource-go'
            }]
        }]
        if (config.clear) {
            config.triggerConfig[0].cn.push({
                tag: 'div',
                cls: 'x-form-trigger x-field-readlogjson-resource-clear'
            })
        }

        config.initTrigger = function () {
            var ts = this.trigger.select('.x-form-trigger', true)
            this.wrap.setStyle('overflow', 'hidden')
            var triggerField = this
            ts.each(function (t, all, index) {
                t.hide = function () {
                    var w = triggerField.wrap.getWidth()
                    this.dom.style.display = 'none'
                    triggerField.el.setWidth(w - triggerField.trigger.getWidth())
                }
                t.show = function () {
                    var w = triggerField.wrap.getWidth()
                    this.dom.style.display = ''
                    triggerField.el.setWidth(w - triggerField.trigger.getWidth())
                }
                var triggerIndex = 'Trigger' + (index + 1)

                if (this['hide' + triggerIndex]) {
                    t.dom.style.display = 'none'
                }
                t.on('click', this['on' + triggerIndex + 'Click'], this, {
                    preventDefault: true
                })
                t.addClassOnOver('x-form-trigger-over')
                t.addClassOnClick('x-form-trigger-click')
            }, this)
            this.triggers = ts.elements
        }
    }
    Ext.applyIf(config, {
        name: config.name || 'resource',
        hiddenName: config.name || 'resource',
        displayField: 'pagetitle',
        valueField: 'id',
        editable: true,
        fields: ['pagetitle', 'id'],
        pageSize: 10,
        emptyText: _('readlogjson_combo_select'),
        hideMode: 'offsets',
        url: ReadLogJson.config.connector_url,
        baseParams: {
            action: 'mgr/misc/resource/getlist',
            combo: true
        },
        tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item">',
            '<small>({id})</small> <b>{pagetitle}</b>',
            '</div></tpl>',
            {
                compiled: true
            }),
        cls: 'input-combo-readlogjson-resource',
        clearValue: function () {
            if (this.hiddenField) {
                this.hiddenField.value = ''
            }
            this.setRawValue('')
            this.lastSelectionText = ''
            this.applyEmptyText()
            this.value = ''
            this.fireEvent('select', this, null, 0)
        },

        getTrigger: function (index) {
            return this.triggers[index]
        },

        onTrigger1Click: function () {
            this.onTriggerClick()
        },

        onTrigger2Click: function () {
            this.clearValue()
        }
    })
    ReadLogJson.combo.Resource.superclass.constructor.call(this, config)

}
Ext.extend(ReadLogJson.combo.Resource, MODx.combo.ComboBox)
Ext.reg('readlogjson-combo-filter-resource', ReadLogJson.combo.Resource)

ReadLogJson.combo.DateTime = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        timePosition: 'right',
        allowBlank: true,
        hiddenFormat: 'Y-m-d H:i:s',
        dateFormat: MODx.config['manager_date_format'],
        timeFormat: MODx.config['manager_time_format'],
        dateWidth: 120,
        timeWidth: 120
    })
    ReadLogJson.combo.DateTime.superclass.constructor.call(this, config)
}
Ext.extend(ReadLogJson.combo.DateTime, Ext.ux.form.DateTime)
Ext.reg('readlogjson-xdatetime', ReadLogJson.combo.DateTime)

/**
 * Filter Method
 * @param config
 * @constructor
 */
ReadLogJson.combo.Method = function (config) {
    config = config || {}

    if (config.custm) {
        config.triggerConfig = [{
            tag: 'div',
            cls: 'x-field-search-btns',
            style: String.format('width: {0}px;', config.clear ? 62 : 31),
            cn: [{
                tag: 'div',
                cls: 'x-form-trigger x-field-readlogjson-resource-go'
            }]
        }]
        if (config.clear) {
            config.triggerConfig[0].cn.push({
                tag: 'div',
                cls: 'x-form-trigger x-field-readlogjson-resource-clear'
            })
        }

        config.initTrigger = function () {
            var ts = this.trigger.select('.x-form-trigger', true)
            this.wrap.setStyle('overflow', 'hidden')
            var triggerField = this
            ts.each(function (t, all, index) {
                t.hide = function () {
                    var w = triggerField.wrap.getWidth()
                    this.dom.style.display = 'none'
                    triggerField.el.setWidth(w - triggerField.trigger.getWidth())
                }
                t.show = function () {
                    var w = triggerField.wrap.getWidth()
                    this.dom.style.display = ''
                    triggerField.el.setWidth(w - triggerField.trigger.getWidth())
                }
                var triggerIndex = 'Trigger' + (index + 1)

                if (this['hide' + triggerIndex]) {
                    t.dom.style.display = 'none'
                }
                t.on('click', this['on' + triggerIndex + 'Click'], this, {
                    preventDefault: true
                })
                t.addClassOnOver('x-form-trigger-over')
                t.addClassOnClick('x-form-trigger-click')
            }, this)
            this.triggers = ts.elements
        }
    }
    Ext.applyIf(config, {
        name: config.name || 'method',
        hiddenName: config.name || 'method',
        displayField: 'name',
        valueField: 'value',
        editable: true,
        fields: ['name', 'value'],
        pageSize: 10,
        emptyText: _('readlogjson_combo_select'),
        hideMode: 'offsets',
        url: ReadLogJson.config.connector_url,
        baseParams: {
            action: 'mgr/misc/method/getlist',
            addall: config.addall,
            combo: true
        },
        tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item">',
            '{name}',
            '</div></tpl>',
            {
                compiled: true
            }),
        cls: 'input-combo-readlogjson-resource',
        clearValue: function () {
            if (this.hiddenField) {
                this.hiddenField.value = ''
            }
            this.setRawValue('')
            this.lastSelectionText = ''
            this.applyEmptyText()
            this.value = ''
            this.fireEvent('select', this, null, 0)
        },

        getTrigger: function (index) {
            return this.triggers[index]
        },

        onTrigger1Click: function () {
            this.onTriggerClick()
        },

        onTrigger2Click: function () {
            this.clearValue()
        }
    })
    ReadLogJson.combo.Method.superclass.constructor.call(this, config)

}
Ext.extend(ReadLogJson.combo.Method, MODx.combo.ComboBox)
Ext.reg('readlogjson-combo-filter-method', ReadLogJson.combo.Method)
