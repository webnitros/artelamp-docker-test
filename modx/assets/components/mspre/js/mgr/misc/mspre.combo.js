Ext.namespace('mspre.combo')

function getDefaultConfig (config) {
    config.triggerConfig = [{
        tag: 'div',
        cls: 'x-field-search-btns',
        style: String.format('width: {0}px;', config.clear ? 62 : 31),
        cn: [{
            tag: 'div',
            cls: 'x-form-trigger x-field-mspre-region-go'
        }]
    }]
    if (config.clear) {
        config.triggerConfig[0].cn.push({
            tag: 'div',
            cls: 'x-form-trigger x-field-mspre-region-clear'
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

    config.custm = true
    config.clear = true
    config.addall = true
    config.editable = true
    config.pageSize = 10
    config.hideMode = 'offsets'
    config.combo = true
    config.addall = config.addall || 0

    config.clearValue = function () {
        if (this.hiddenField) {
            this.hiddenField.value = ''
        }
        this.setRawValue('')
        this.lastSelectionText = ''
        this.applyEmptyText()
        this.value = ''
        this.fireEvent('select', this, null, 0)
    }

    config.getTrigger = function (index) {
        return this.triggers[index]
    }

    config.onTrigger1Click = function () {
        this.onTriggerClick()
    }

    config.onTrigger2Click = function () {
        this.clearValue()
    }
    return config
}

mspre.window.defaultCombo = function (config) {
    var params = config.params

    var title, xtype, label, name, emptyText, baseParams, hiddenName = class_key = ''

    xtype = params.xtype ? params.xtype : null
    name = params.parent ? params.parent : null
    hiddenName = params.hiddenName ? params.hiddenName : null
    title = params.title ? params.title : null
    label = params.label ? params.label : null
    emptyText = params.emptyText ? params.emptyText : null
    baseParams = params.baseParams ? params.baseParams : {}

    baseParams.class_key = mspre.config.classKey
    var defaultFields = [
        {
            xtype: 'hidden',
            name: 'ids'
        },
        {
            xtype: xtype,
            fieldLabel: label,
            emptyText: emptyText,
            name: name,
            hiddenName: hiddenName,
            anchor: '90%',
        }
    ]

    var fields = params.fields ? params.fields : defaultFields

    // fields
    config = config || {}
    Ext.applyIf(config, {
        title: title,
        url: mspre.config.connector_url,
        baseParams: baseParams,
        width: 400,
        autoHeight: true,
        fields: fields
    })
    mspre.window.defaultCombo.superclass.constructor.call(this, config)
}
//Ext.extend(mspre.window.defaultCombo, MODx.Window)
Ext.extend(mspre.window.defaultCombo, mspre.window.DefaultComboExt)
Ext.reg('mspre-window-update-default-combo', mspre.window.defaultCombo)

/* Combo Filter */
// Search
mspre.combo.Search = function (config) {
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
        onTrigger2Click: this._triggerClear
    })
    mspre.combo.Search.superclass.constructor.call(this, config)
    this.on('render', function () {
        this.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
            this._triggerSearch()
        }, this)
    })
    this.addEvents('clear', 'search')
}
Ext.extend(mspre.combo.Search, Ext.form.TwinTriggerField, {
    initComponent: function () {
        Ext.form.TwinTriggerField.superclass.initComponent.call(this)
        this.triggerConfig = {
            tag: 'span',
            cls: 'x-field-search-btns',
            cn: [{
                tag: 'div',
                cls: 'x-form-trigger ' + this.searchBtnCls
            }, {
                tag: 'div',
                cls: 'x-form-trigger ' + this.clearBtnCls
            }]
        }
    },

    _triggerSearch: function () {
        this.fireEvent('search', this)
    },

    _triggerClear: function () {
        this.fireEvent('clear', this)
    }

})
Ext.reg('mspre-field-search', mspre.combo.Search)

// Status
mspre.combo.Status = function (config) {
    config = config || {}

    if (config.custm) {
        config = getDefaultConfig(config)
    }
    Ext.applyIf(config, {
        name: config.name || 'status',
        hiddenName: config.name || 'status',
        displayField: 'name',
        valueField: 'value',

        fields: ['name', 'value'],
        emptyText: _('mspre_combo_select_status'),
        url: mspre.config.connector_url,
        baseParams: {
            action: 'mgr/misc/status/getlist',
            controller: mspre.config.controller
        },
        tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item">',
            '<span><b>{name}</b> <small>({value})</small> </span>',
            '</div></tpl>', {
                compiled: true
            }),
        cls: 'input-combo-mspre-status'
    })

    config.paging = false
        config.pageLimit =50
        config.pageSize = 0
    mspre.combo.Status.superclass.constructor.call(this, config)

}
Ext.extend(mspre.combo.Status, MODx.combo.ComboBox)
Ext.reg('mspre-combo-status', mspre.combo.Status)

// Boolean
mspre.combo.Boolean = function (config) {

    config.mode = 'local'
    config = config || {}
    Ext.applyIf(config, {
        store: new Ext.data.SimpleStore({
            fields: ['d', 'v'],
            data: [[_('yes'), _('yes')], [_('no'), _('no')]]
        })
    })
    mspre.combo.Boolean.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.Boolean, MODx.combo.Boolean)
Ext.reg('mspre-combo-boolean', mspre.combo.Boolean)

// Boolean
mspre.combo.Boolean = function (config) {

    config.mode = 'local'
    config = config || {}
    Ext.applyIf(config, {
        store: new Ext.data.SimpleStore({
            fields: ['d', 'v'],
            data: [[_('yes'), true], [_('no'), false]]
        })
    })
    mspre.combo.Boolean.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.Boolean, MODx.combo.Boolean)
Ext.reg('mspre-combo-boolean-option', mspre.combo.Boolean)

// Price
mspre.combo.Price = function (config) {
    config = config || {}

    if (config.custm) {
        config = getDefaultConfig(config)
    }
    Ext.applyIf(config, {
        name: config.name || 'price',
        hiddenName: config.name || 'price',
        displayField: 'name',
        valueField: 'value',
        fields: ['name', 'value'],
        emptyText: _('mspre_combo_select_price'),
        url: mspre.config.connector_url,
        baseParams: {
            action: 'mgr/misc/price/getlist'
        },
        tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item">',
            '<b>{name}</b></span>',
            '</div></tpl>', {
                compiled: true
            }),
        cls: 'input-combo-mspre-price'
    })
    mspre.combo.Price.superclass.constructor.call(this, config)

}
Ext.extend(mspre.combo.Price, MODx.combo.ComboBox)
Ext.reg('mspre-combo-price', mspre.combo.Price)

// Autocomplete
mspre.combo.Autocomplete = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        name: config.name,
        fieldLabel: _('mspre_header_' + config.name),
        id: 'mspre-product-' + config.name,
        hiddenName: config.name,
        displayField: config.name,
        valueField: config.name,
        anchor: '99%',
        fields: [config.name],
        //pageSize: 20,
        forceSelection: false,
        url: mspre.config['connector_url'],
        typeAhead: true,
        editable: true,
        allowBlank: true,
        baseParams: {
            action: mspre.config.controllerPath + 'autocomplete',
            name: config.name,
            combo: true,
            limit: 0
        },
        hideTrigger: false,
    })
    mspre.combo.Autocomplete.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.Autocomplete, MODx.combo.ComboBox)
Ext.reg('mspre-combo-autocomplete', mspre.combo.Autocomplete)

/* Combo Update Individual */

// replace Options field
mspre.window.replaceOptionsFieldSelectValue = function (config) {

    var template = config.records['template']
    var ids = config.records['ids']
    var field = config.records['field']
    var xtype_old = config.records['xtype_old'] || 'mspre-combo-autocomplete-tv'
    var xtype_new = config.records['xtype_new'] || 'mspre-combo-autocomplete-tv'

    config = config || {}
    Ext.applyIf(config, {
        title: _('mspre_combo_tv_field_replace_title'),
        url: mspre.config.connector_url,
        baseParams: {
            action: mspre.config.controllerPath + 'multiple',
            method: 'settv',
        },
        id: 'mspre-window-replace-tv-field-select-value',
        width: 400,
        maxHeight: 250,
        autoHeight: true,
        fields: [
            {
                xtype: 'hidden',
                name: 'ids'
            },
            {
                xtype: 'hidden',
                name: 'field_name',
                value: field,
                allowBlank: false,
            },
            {
                xtype: 'displayfield',
                fieldLabel: 'Поле',
                anchor: '90%',
                value: field,
            },
            {
                xtype: xtype_old,
                fieldLabel: _('mspre_old_value'),
                id: 'mspre-window-replace-tv-field-field_value',
                name: 'field_value',
                allowBlank: false,
                hiddenName: 'field_value',
                anchor: '90%',
                records: config.records,
                whatValues: 'entered',
            },
            {
                xtype: xtype_new,
                fieldLabel: _('mspre_new_value'),
                id: 'mspre-window-replace-tv-field-field_replace',
                name: 'field_replace',
                allowBlank: false,
                hiddenName: 'field_replace',
                anchor: '90%',
                records: config.records,
                whatValues: 'possible',
            }
        ]
    })
    mspre.window.replaceOptionsFieldSelectValue.superclass.constructor.call(this, config)
}
Ext.extend(mspre.window.replaceOptionsFieldSelectValue, MODx.Window)
Ext.reg('mspre-window-replace-options-select-value', mspre.window.replaceOptionsFieldSelectValue)

// Autocomplete Options
mspre.combo.AutocompleteOptions = function (config) {
    config = config || {}

    var grid = Ext.getCmp('mspre-grid-product')
    var ids = grid._getSelectedIds()

    delete config.id
    Ext.applyIf(config, {
        name: config.name,
        fieldLabel: _('mspre_header_' + config.name),
        //id: 'mspre-product-' + config.name,
        hiddenName: config.name,
        displayField: config.name,
        valueField: config.name,
        anchor: '99%',
        fields: [config.name],
        //pageSize: 20,
        forceSelection: false,
        url: mspre.config['connector_url'],
        typeAhead: true,
        editable: false,
        allowBlank: true,
        baseParams: {
            action: 'mgr/misc/options/autocomplete',
            name: config.name,
            field: config.name,
            ids: Ext.util.JSON.encode(ids),
            whatValues: config.whatValues,
            combo: true,
            limit: 0
        },
        hideTrigger: false,
    })
    mspre.combo.AutocompleteOptions.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.AutocompleteOptions, MODx.combo.ComboBox)
Ext.reg('mspre-combo-autocomplete-options', mspre.combo.AutocompleteOptions)

/*
************
* Export product
************
* */

// Exportfields
mspre.window.ExportFields = function (config) {

    var fields = []
    var export_fields = mspre.config.export_fields

    for (var field in export_fields) {
        var checked = export_fields[field]
        fields.push({
            name: field,
            xtype: 'xcheckbox',
            width: 200,
            boxLabel: field,
            id: 'mspree-export-field-' + field,
            ctCls: 'tbar-checkbox',
            checked: checked,
            listeners: {
                check: {
                    fn: this.nestedFilter,
                    scope: this
                }
            }
        })

    }

    config = config || {}
    Ext.applyIf(config, {
        title: _('mspre_export_fields_title'),
        url: mspre.config.connector_url,
        baseParams: {
            action: 'mgr/common/export/exportfields'
        },
        id: 'mspre-window-update-export-fields',
        width: 400,
        fields: fields,
        listeners: {
            'success': {
                fn: function (r) {

                }, scope: this
            },
            'failure': {
                fn: function (r) {
                    MODx.msg.alert(_('mspre_error'), response.message)
                }, scope: this
            }
        }
    })

    mspre.window.ExportFields.superclass.constructor.call(this, config)
}
Ext.extend(mspre.window.ExportFields, MODx.Window, {
    nestedFilter: function (checkbox, checked) {
    },
})
Ext.reg('mspre-window-export-fields', mspre.window.ExportFields)

// Options Change Combo
// Действия для полей по умолчанию таких как tags,color,size
mspre.window.OptionsChangeCombo = function (config) {
    this.grid = Ext.getCmp('mspre-grid-product')
    config = config || {}

    Ext.applyIf(config, {
        title: _('mspre_combo_change_options_combo_title'),
        url: mspre.config.connector_url,
        id: 'mspre-window-change-combo-modal',
        width: 400,
        maxHeight: 250,
        fields: [
            {
                xtype: 'modx-button',
                cls: 'mspre-btn-action-tv',
                iconCls: 'icon-plus',
                text: _('mspre_combo_change_combo_add'),
                anchor: '99%',
                listeners: {
                    click: {
                        fn: function () {
                            this.destroy()
                            Ext.getCmp('mspre-grid-product').autoStartOptionsCombo('add', config.field, config.record)
                        },
                        scope: this
                    }
                }
            },
            {
                xtype: 'modx-button',
                cls: 'mspre-btn-action-tv',
                iconCls: 'icon-edit',
                text: _('mspre_combo_change_combo_replace'),
                anchor: '99%',
                listeners: {
                    click: {
                        fn: function () {
                            this.destroy()
                            Ext.getCmp('mspre-grid-product').autoStartOptionsCombo('replace', config.field, config.record)
                        },
                        scope: this
                    }
                }
            },
            {
                xtype: 'modx-button',
                cls: 'mspre-btn-action-tv',
                iconCls: 'icon-trash-o',
                text: _('mspre_combo_change_combo_remove'),
                anchor: '99%',
                listeners: {
                    click: {
                        fn: function () {
                            this.destroy()
                            Ext.getCmp('mspre-grid-product').autoStartOptionsCombo('remove', config.field, config.record)
                        },
                        scope: this
                    }
                }
            }

        ]
    })

    mspre.window.OptionsChangeCombo.superclass.constructor.call(this, config)
}
Ext.extend(mspre.window.OptionsChangeCombo, MODx.Window)
Ext.reg('mspre-window-options-change-combo', mspre.window.OptionsChangeCombo)

// Options Category Binding
mspre.window.OptionsCategoryBinding = function (config) {
    this.grid = Ext.getCmp('mspre-grid-product')
    config = config || {}

    Ext.applyIf(config, {
        title: _('mspre_combo_options_category_binding'),
        url: minishop2.config.connector_url,
        id: 'mspre-window-category-binding',
        autoHeight: true,
        baseParams: {
            action: 'mgr/category/option/add',
        },
        fields: [
            {
                xtype: 'displayfield',
                name: 'category_id',
                allowBlank: false,
            },
            {
                xtype: 'displayfield',
                name: 'option_id',
                allowBlank: false,
            },
            {
                xtype: 'displayfield',
                name: 'value',
                allowBlank: false,
            },
            {
                xtype: 'xcheckbox',
                name: 'active',
                allowBlank: false,
                value: true,
            },
            {
                xtype: 'xcheckbox',
                name: 'required',
                allowBlank: false,
                checked: false,
            },
        ],
        listeners: {
            'failure': {
                fn: function (r) {
                    MODx.msg.alert(_('mspre_error'), response.message)
                }, scope: this
            }
        }
    })

    mspre.window.OptionsCategoryBinding.superclass.constructor.call(this, config)
}
Ext.extend(mspre.window.OptionsCategoryBinding, MODx.Window)
Ext.reg('mspre-window-category-binding', mspre.window.OptionsCategoryBinding)
