/******
 * select combo
 * */

// Context
mspre.combo.Context = function (config) {
    config = config || {}

    if (config.custm) {
        config = getDefaultConfig(config)
    }
    Ext.applyIf(config, {
        name: config.name || 'context',
        hiddenName: config.name || 'context',
        displayField: 'name',
        valueField: 'key',
        fields: ['name', 'key'],
        emptyText: _('mspre_combo_select_context'),

        pageSize: 5,

        url: mspre.config.connector_url,
        baseParams: {
            action: 'mgr/misc/context/getlist',
        },
        tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item">',
            '<small>({key})</small> <b>{name}</b></span>',
            '</div></tpl>', {
                compiled: true
            }),
        cls: 'input-combo-mspre-context',
        clear: {
            fn: function (field) {
                field.setValue('web')
                this._filterSet('filter_value', 'web')
            },
            scope: this
        }
    })
    mspre.combo.Context.superclass.constructor.call(this, config)

}
Ext.extend(mspre.combo.Context, MODx.combo.ComboBox)
Ext.reg('mspre-combo-context', mspre.combo.Context)

// mspre-combo-groupresource
mspre.combo.ResourceGroup = function (config) {
    config = config || {}

    if (config.custm) {
        config = getDefaultConfig(config)
    }
    Ext.applyIf(config, {
        name: config.name || 'resourcegroup',
        hiddenName: config.name || 'resourcegroup',
        displayField: 'name',
        valueField: 'key',
        fields: ['name', 'key'],
        emptyText: _('mspre_combo_select_context'),
        pageSize: 5,
        url: mspre.config.connector_url,
        baseParams: {
            action: 'mgr/misc/resourcegroup/getlist',
        },
        tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item">',
            '<small>({key})</small> <b>{name}</b></span>',
            '</div></tpl>', {
                compiled: true
            }),
        cls: 'input-combo-mspre-resourcegroup',
        clear: {
            fn: function (field) {
                field.setValue('web')
                this._filterSet('filter_value', 'web')
            },
            scope: this
        }
    })
    mspre.combo.ResourceGroup.superclass.constructor.call(this, config)

}
Ext.extend(mspre.combo.ResourceGroup, MODx.combo.ComboBox)
Ext.reg('mspre-combo-resourcegroup', mspre.combo.ResourceGroup)

// Resource
mspre.combo.Resource = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        name: 'resource'
        , hiddenName: 'resource'
        , displayField: 'pagetitle'
        , valueField: 'id'
        , editable: true
        , fields: ['id', 'pagetitle']
        , pageSize: 20
        , emptyText: _('mspre_combo_select')
        , hideMode: 'offsets'
        , url: mspre.config.connector_url
        , baseParams: {
            action: 'mgr/system/element/resource/getlist'
            //,context_key: mspre.store.state.context
            , combo: true
        }
    })
    mspre.combo.Resource.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.Resource, MODx.combo.ComboBox)
Ext.reg('mspre-combo-parent', mspre.combo.Resource)

// Template
mspre.combo.Template = function (config) {
    config = config || {}

    if (config.custm) {
        config = getDefaultConfig(config)
    }
    Ext.applyIf(config, {
        name: config.name || 'template',
        hiddenName: 'template',
        displayField: 'templatename',
        allowBlank: true,
        editable: true,
        valueField: 'id',
        fields: ['id', 'templatename'],
        emptyText: _('mspre_combo_template_select'),
        url: mspre.config.connector_url,
        baseParams: {
            action: 'mgr/system/element/template/getlist',
            combo: true
        }
    })
    mspre.combo.Template.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.Template, MODx.combo.ComboBox)
Ext.reg('mspre-combo-template', mspre.combo.Template)

// Template
mspre.combo.ResourceGroup = function (config) {
    config = config || {}

    if (config.custm) {
        config = getDefaultConfig(config)
    }
    Ext.applyIf(config, {
        name: config.name || 'name',
        hiddenName: 'name',
        displayField: 'name',
        allowBlank: true,
        editable: true,
        valueField: 'id',
        fields: ['id', 'name'],
        emptyText: _('mspre_combo_resource_group_select'),
        url: mspre.config.connector_url,
        baseParams: {
            action: 'mgr/system/element/resourcegroup/getlist',
            combo: true
        }
    })
    mspre.combo.ResourceGroup.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.ResourceGroup, MODx.combo.ComboBox)
Ext.reg('mspre-combo-resource-group', mspre.combo.ResourceGroup)

// Link
mspre.combo.Link = function (config) {
    config = config || {}

    if (config.custm) {
        config = getDefaultConfig(config)
    }
    Ext.applyIf(config, {
        name: config.name || 'product_link',
        hiddenName: 'product_link',
        displayField: 'name',
        allowBlank: true,
        editable: true,
        valueField: 'id',
        fields: ['id', 'name'],
        emptyText: _('ms2_combo_select'),
        url: miniShop2.config['connector_url'],
        baseParams: {
            action: 'mgr/settings/link/getlist',
            combo: true
        }
    })
    mspre.combo.Link.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.Link, MODx.combo.ComboBox)
Ext.reg('mspre-combo-link', mspre.combo.Link)

// Class map
mspre.combo.ClassMap = function (config) {
    config = config || {}

    if (config.custm) {
        config = getDefaultConfig(config)
    }
    Ext.applyIf(config, {
        name: config.name || 'class_key',
        hiddenName: 'class_key',
        displayField: 'class_key',
        allowBlank: true,
        valueField: 'class_key',
        fields: ['class_key', 'class_key'],
        emptyText: _('mspre_combo_classname'),
        url: mspre.config.connector_url,
        baseParams: {
            action: 'mgr/system/element/classname/getlist',
            combo: true,
        }
    })
    mspre.combo.ClassMap.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.ClassMap, MODx.combo.ComboBox)
Ext.reg('mspre-combo-class-map', mspre.combo.ClassMap)

// FilterField
mspre.combo.FilterField = function (config) {
    config = config || {}

    if (config.custm) {
        config = getDefaultConfig(config)
    }

    config.paging = false
    config.pageLimit = 50
    config.pageSize = 0
    Ext.applyIf(config, {
        name: config.name || 'filter_field',
        hiddenName: 'filter_field',
        displayField: 'value',
        allowBlank: true,
        value: '',
        valueField: 'key',
        fields: ['key', 'value'],
        emptyText: _('mspre_filter_field'),
        fieldLabel: _('mspre_filter_field'),
        url: mspre.config.connector_url,
        baseParams: {
            action: 'mgr/misc/filtres/getlist',
            controller: mspre.config.controller,
            combo: true
        }

    })
    mspre.combo.FilterField.superclass.constructor.call(this, config)

}
Ext.extend(mspre.combo.FilterField, MODx.combo.ComboBox)
Ext.reg('mspre-combo-filterfield', mspre.combo.FilterField)

// filtertype
mspre.combo.FilterType = function (config) {
    config = config || {}

    if (config.custm) {
        config = getDefaultConfig(config)
    }
    config.paging = false
    config.pageLimit = 50
    config.pageSize = 0
    Ext.applyIf(config, {
        name: config.name || 'filter_type',
        hiddenName: 'filter_type',
        allowBlank: true,
        fields: ['value', 'text'],
        emptyText: _('mspre_filter_type'),
        fieldLabel: _('mspre_filter_type'),
        store: new Ext.data.SimpleStore({
            data: [
                ['=', '='],
                ['!=', '≠'],
                ['>', '>'],
                ['<', '<'],
                ['>=', '≥'],
                ['<=', '≤'],
                ['IN', 'IN'],
                ['LIKE', 'LIKE'],
                ['LIKE%%', 'LIKE %%'],
                ['BETWEEN', 'BETWEEN'],
                ['IS NULL', 'IS NULL'],
                ['IS NOT NULL', 'IS NOT NULL']
            ],
            id: 'id',
            fields: ['value', 'text']
        }),
        valueField: 'value',
        displayField: 'text',
        mode: 'local',
    })

    mspre.combo.FilterType.superclass.constructor.call(this, config)

}
Ext.extend(mspre.combo.FilterType, MODx.combo.ComboBox)
Ext.reg('mspre-combo-filtertype', mspre.combo.FilterType)

// TextReplace
mspre.combo.TextReplace = function (config) {
    config = config || {}

    if (config.custm) {
        config = getDefaultConfig(config)
    }
    config.paging = false
    config.pageLimit = 50
    config.pageSize = 0
    Ext.applyIf(config, {
        name: config.name || 'field',
        hiddenName: 'field',
        allowBlank: true,
        fields: ['field', 'text'],
        emptyText: _('mspre_combo_text_replace'),
        fieldLabel: _('mspre_combo_text_replace'),
        store: new Ext.data.SimpleStore({
            data: [
                ['pagetitle', 'pagetitle'],
                ['logntitle', 'logntitle'],
                ['menutitle', 'menutitle'],
                ['link_attributes', 'link_attributes'],
                ['description', 'description'],
                ['introtext', 'introtext'],
            ],
            id: 'id',
            fields: ['field', 'text']
        }),
        valueField: 'field',
        displayField: 'text',
        mode: 'local',
    })

    mspre.combo.TextReplace.superclass.constructor.call(this, config)

}
Ext.extend(mspre.combo.TextReplace, MODx.combo.ComboBox)
Ext.reg('mspre-combo-text-replace', mspre.combo.TextReplace)

// Price Increase
mspre.combo.PriceIncrease = function (config) {
    config = config || {}

    if (config.custm) {
        config = getDefaultConfig(config)
    }
    config.paging = false
    config.pageLimit = 50
    config.pageSize = 0
    Ext.applyIf(config, {
        name: config.name || 'increase',
        hiddenName: 'increase',
        allowBlank: true,
        fields: ['value', 'name'],
        fieldLabel: _('mspre_price_increase'),
        url: mspre.config.connector_url,
        baseParams: {
            action: 'mgr/misc/increase/getlist',
            combo: true
        },
        valueField: 'value',
        displayField: 'name',
    })
    mspre.combo.PriceIncrease.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.PriceIncrease, MODx.combo.ComboBox)
Ext.reg('mspre-combo-price-increase', mspre.combo.PriceIncrease)

// Price round
mspre.combo.PriceRound = function (config) {
    config = config || {}

    if (config.custm) {
        config = getDefaultConfig(config)
    }
    config.paging = false
    config.pageLimit = 50
    config.pageSize = 0
    Ext.applyIf(config, {
        name: config.name || 'round',
        hiddenName: 'round',
        allowBlank: true,
        fields: ['value', 'name'],
        fieldLabel: _('mspre_price_round'),
        url: mspre.config.connector_url,
        baseParams: {
            action: 'mgr/misc/round/getlist',
            combo: true
        },
        valueField: 'value',
        displayField: 'name',
    })
    mspre.combo.PriceRound.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.PriceRound, MODx.combo.ComboBox)
Ext.reg('mspre-combo-price-round', mspre.combo.PriceRound)

// Vendor
mspre.combo.Vendor = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        name: config.name || 'vendor',
        fieldLabel: _('mspre_header_' + config.name || 'vendor'),
        hiddenName: config.name || 'vendor',
        displayField: 'name',
        valueField: 'id',
        anchor: '99%',
        fields: ['name', 'id'],
        pageSize: 20,
        url: mspre.config['connector_url'],
        typeAhead: true,
        editable: true,
        allowBlank: true,
        emptyText: _('mspre_vendor'),
        baseParams: {
            action: 'mgr/misc/vendor/getlist',
            combo: true,
            id: config.value,
        }
    })
    mspre.combo.Vendor.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.Vendor, MODx.combo.ComboBox)
Ext.reg('mspre-combo-vendor', mspre.combo.Vendor)

// Vendors
mspre.combo.Vendors = function (config) {
    config = config || {}
    if (config.custm) {
        config = getDefaultConfig(config)
    }
    Ext.applyIf(config, {
        name: config.name || 'vendor',
        hiddenName: 'vendor',
        displayField: 'name',
        allowBlank: true,
        valueField: 'id',
        fields: ['id', 'name'],
        emptyText: _('mspre_combo_vendor_select'),
        url: mspre.config.connector_url,
        baseParams: {
            action: 'mgr/misc/vendor/getlist',
            combo: true
        }
    })
    mspre.combo.Vendors.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.Vendors, MODx.combo.ComboBox)
Ext.reg('mspre-combo-vendors', mspre.combo.Vendors)

/*mspre.combo.VendorsMany = function (config) {
    config = config || {}
    if (config.custm) {
        config = getDefaultConfig(config)
    }

    Ext.applyIf(config, {
        xtype: 'superboxselect',
        allowBlank: true,
        msgTarget: 'under',
        allowAddNewData: true,
        addNewDataOnBlur: true,
        pinList: false,
        resizable: true,
        lazyInit: false,
        name: config.name || 'vendor',
        anchor: '100%',
        minChars: 1,
        pageSize: 10,
        store: new Ext.data.JsonStore({
            //id: config.id,
            id: (config.name || 'vendor') + '-store',
            root: 'results',
            autoLoad: false,
            autoSave: false,
            totalProperty: 'total',
            fields: ['id', 'name'],
            url: mspre.config.connector_url,
            baseParams: {
                action: 'mgr/misc/vendor/getlist',
                combo: true,
            },
        }),
        mode: 'remote',
        displayField: 'name',
        valueField: 'id',
        triggerAction: 'all',
        extraItemCls: 'x-tag',
        expandBtnCls: 'x-form-trigger',
        clearBtnCls: 'x-form-trigger',
        displayFieldTpl: config.displayFieldTpl || '{name}',
    })
   config.name += '[]'

    Ext.apply(config, {
        listeners: {
            'beforeadditem': {
                fn: function (e) {
                }, scope: this
            }
            , 'beforeremoveitem': {
                fn: function () {
                    console.log('beforeremoveitem')
                }, scope: this
            }
        },
    })
    mspre.combo.VendorsMany.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.VendorsMany, Ext.ux.form.SuperBoxSelect)
Ext.reg('mspre-combo-vendors-many', mspre.combo.VendorsMany)*/

// User
mspre.combo.User = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        name: 'user',
        fieldLabel: config.name || 'createdby',
        hiddenName: config.name || 'createdby',
        displayField: 'fullname',
        valueField: 'id',
        anchor: '99%',
        fields: ['username', 'id', 'fullname'],
        pageSize: 20,
        typeAhead: false,
        editable: true,
        allowBlank: false,
        url: mspre.config['connector_url'],
        baseParams: {
            action: 'mgr/system/user/getlist',
            combo: true,
        },
        tpl: new Ext.XTemplate('\
            <tpl for=".">\
                <div class="x-combo-list-item">\
                    <span>\
                        <small>({id})</small>\
                        <b>{username}</b>\
                        <tpl if="fullname"> - {fullname}</tpl>\
                    </span>\
                </div>\
            </tpl>',
            {compiled: true}
        ),
    })
    mspre.combo.User.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.User, MODx.combo.ComboBox)
Ext.reg('mspre-combo-user', mspre.combo.User)
/* -- the end select combo*/

// format Time
mspre.combo.DateTime = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        timePosition: 'right',
        allowBlank: true,
        hiddenFormat: 'Y-m-d H:i:s',
        dateFormat: MODx.config['manager_date_format'],
        timeFormat: MODx.config['manager_time_format'],
        dateWidth: 120,
        timeWidth: 120,
        listeners: {
            change: {fn: this.saveValue, scope: this}
        }
    })
    mspre.combo.DateTime.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.DateTime, Ext.ux.form.DateTime, {
    saveValue: function (e, b) {
        /// Ext.getCmp(mspre.config.grid_id).refresh()
        return true
    },

})
Ext.reg('mspre-xdatetime', mspre.combo.DateTime)

mspre.combo.Resource = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        id: 'modx-combo-resource'
        , name: 'resourceID'
        , hiddenName: 'resourceID'
        , displayField: 'pagetitle'
        , valueField: 'id'
        , mode: 'remote'
        , fields: ['id', 'pagetitle']
        , forceSelection: true
        , editable: false
        , enableKeyEvents: true
        , pageSize: 20,
        url: mspre.config.connector_url,
        baseParams: {
            action: 'mgr/system/element/resource/getlist',
            combo: true
        }
    })
    mspre.combo.Resource.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.Resource, MODx.combo.ComboBox)
Ext.reg('mspre-combo-resource', mspre.combo.Resource)

mspre.combo.Category = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        id: 'modx-combo-category'
        , name: 'categoryID'
        , hiddenName: 'categoryID'
        , displayField: 'pagetitle'
        , valueField: 'id'
        , width: 400
        , mode: 'remote'
        , fields: ['id', 'pagetitle', 'count']
        , forceSelection: true
        , editable: true
        , typeAhead: true
        , clear: true
        , custm: true
        , enableKeyEvents: true
        , pageSize: 20,

        tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item">',
            '<small>({id})</small> <b>{pagetitle}</b> <br><small>' + _('mspre_selected_count_product') + ' {count}</small></span>',
            '</div></tpl>', {
                compiled: true
            }),
        url: mspre.config.connector_url,
        baseParams: {
            action: 'mgr/misc/category/getlist',
            combo: true,
            ids: config.ids || ''
        }
    })
    mspre.combo.Category.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.Category, MODx.combo.ComboBox)
Ext.reg('mspre-combo-category', mspre.combo.Category)

mspre.combo.Option = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        id: 'modx-combo-option'
        , name: 'key'
        , hiddenName: 'key'
        , displayField: 'key'
        , valueField: 'key'
        , width: 400
        , mode: 'remote'
        , fields: ['key', 'caption']
        , forceSelection: true
        , editable: true
        , enableKeyEvents: true
        , pageSize: 20,

        tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item">',
            '<small>({key})</small> <b>{caption}</b></span>',
            '</div></tpl>', {
                compiled: true
            }),
        url: mspre.config.connector_url,
        baseParams: {
            action: 'mgr/misc/option/getlist',
            combo: true,
            category: 0,
            show_all: false
        }
    })
    mspre.combo.Option.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.Option, MODx.combo.ComboBox)
Ext.reg('mspre-combo-option', mspre.combo.Option)

mspre.combo.Source = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        name: config.name || 'source-cmb',
        hiddenName: 'source-cmb',
        displayField: 'name',
        valueField: 'id',
        width: 300,
        listWidth: 300,
        fieldLabel: _('mspre_combo_source_title'),
        anchor: '99%',
        allowBlank: false,
        url: mspre.config.connector_url,
        baseParams: {
            action: 'mgr/system/element/source/getlist',
            combo: true
        }
    })
    mspre.combo.Source.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.Source, MODx.combo.MediaSource)
Ext.reg('mspre-combo-source', mspre.combo.Source)

// Filter Options
mspre.combo.FilterOptions = function (config) {
    config = config || {}

    if (config.custm) {
        config = getDefaultConfig(config)
    }
    config.paging = false
    config.pageLimit = 100
    config.pageSize = 0
    Ext.applyIf(config, {
        name: config.name || 'key',
        hiddenName: 'key',
        displayField: 'key',
        allowBlank: true,
        valueField: 'key',
        fields: ['key', 'caption'],
        emptyText: _('mspre_combo_options_key'),
        tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item">',
            '<small>({key})</small> <b>{caption}</b></span>',
            '</div></tpl>', {
                compiled: true
            }),
        url: mspre.config.connector_url,
        baseParams: {
            action: 'mgr/misc/optionfilters/key',
            combo: true,
        }
    })
    mspre.combo.FilterOptions.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.FilterOptions, MODx.combo.ComboBox)
Ext.reg('mspre-combo-filter-options', mspre.combo.FilterOptions)

// Filter Options Values
mspre.combo.FilterOptionsValues = function (config) {
    config = config || {}

    if (config.custm) {
        config = getDefaultConfig(config)
    }

    config.paging = false
    config.pageLimit = 100
    config.pageSize = 0
    Ext.applyIf(config, {
        name: config.name || 'key',
        hiddenName: 'key',
        displayField: 'value',
        allowBlank: true,
        valueField: 'key',
        fields: ['value', 'key'],
        emptyText: _('mspre_combo_options_value'),
        url: mspre.config.connector_url,
        baseParams: {
            action: 'mgr/misc/optionfilters/value',
            combo: true,
            option_key: '',
            option_exclude: '',
        }
    })
    mspre.combo.FilterOptionsValues.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.FilterOptionsValues, MODx.combo.ComboBox)
Ext.reg('mspre-combo-filter-options-value', mspre.combo.FilterOptionsValues)

// FilterOptionsValuesExclude
mspre.combo.FilterOptionsValuesExclude = function (config) {
    config = config || {}

    if (config.custm) {
        config = getDefaultConfig(config)
    }
    config.paging = false
    config.pageLimit = 100
    config.pageSize = 0
    Ext.applyIf(config, {
        name: config.name || 'filter_type',
        hiddenName: 'filter_type',
        allowBlank: true,
        fields: ['value', 'text'],
        emptyText: _('mspre_filter_type'),
        fieldLabel: _('mspre_filter_type'),
        store: new Ext.data.SimpleStore({
            data: [
                ['IN', _('mspre_filters_option_values_exclude_in')],
                ['NOT IN', _('mspre_filters_option_values_exclude_not_in')],
            ],
            id: 'id',
            fields: ['value', 'text']
        }),
        valueField: 'value',
        displayField: 'text',
        mode: 'local',
    })

    mspre.combo.FilterOptionsValuesExclude.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.FilterOptionsValuesExclude, MODx.combo.ComboBox)
Ext.reg('mspre-combo-filter-options-value-exclude', mspre.combo.FilterOptionsValuesExclude)