mspre.combo.Options = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        xtype: 'superboxselect',
        allowBlank: true,
        msgTarget: 'under',
        allowAddNewData: true,
        addNewDataOnBlur: true,
        pinList: false,
        resizable: true,
        lazyInit: false,
        name: config.name || 'tags',
        anchor: '100%',
        minChars: 1,
        pageSize: 10,
        store: new Ext.data.JsonStore({
            id: (config.name || 'tags') + '-store',
            root: 'results',
            autoLoad: false,
            autoSave: false,
            totalProperty: 'total',
            fields: ['value'],
            url: mspre.config['connectorUrlMinishop'],
            baseParams: {
                action: '/mgr/product/getoptions',
                //action: mspre.config.controllerPath + '/mgr/product/getoptions',
                key: config.name,
                start: 0,
                limit: 10
            },
        }),
        mode: 'local',
        displayField: 'value',
        valueField: 'value',
        triggerAction: 'all',
        extraItemCls: 'x-tag',
        expandBtnCls: 'x-form-trigger',
        clearBtnCls: 'x-form-trigger',
        displayFieldTpl: config.displayFieldTpl || '{value}',
        // fix for setValue
        addValue: function (value) {
            if (Ext.isEmpty(value)) {
                return
            }
            var values = value
            if (!Ext.isArray(value)) {
                value = '' + value
                values = value.split(this.valueDelimiter)
            }
            Ext.each(values, function (val) {
                var record = this.findRecord(this.valueField, val)
                if (record) {
                    this.addRecord(record)
                }
                this.remoteLookup.push(val)
            }, this)
            if (this.mode === 'remote') {
                var q = this.remoteLookup.join(this.queryValuesDelimiter)
                this.doQuery(q, false, true)
            }
        },
        // fix similar queries
        shouldQuery: function (q) {
            if (this.lastQuery) {
                return (q !== this.lastQuery)
            }
            return true
        },
    })
    config.name += '[]'

    Ext.apply(config, {
        listeners: {
            newitem: function (bs, v) {
                bs.addNewItem({value: v})
            },
            beforequery: {
                fn: function (o) {
                    // reset sort
                    o.combo.store.sortInfo = ''
                    if (o.forceAll !== false) {
                        exclude = o.combo.getValue().split(o.combo.valueDelimiter)
                    } else {
                        exclude = []
                    }
                    o.combo.store.baseParams.exclude = Ext.util.JSON.encode(exclude)
                },
                scope: this
            }
        },
    })
    if (config.ext_field) {
        var ext = Ext.util.JSON.decode(config.ext_field);
        delete ext['xtype']
        Ext.apply(config, ext)
    }
    mspre.combo.Options.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.Options, Ext.ux.form.SuperBoxSelect)
Ext.reg('mspre-combo-options', mspre.combo.Options)

mspre.combo.SuperBoxOptions = function (config) {
    config = config || {}

    config.method = 'optionsselect'

    var mode = 'remote'
    var allowAddNewData = true
    var pinList = false
    if (config.method === 'optionsselect') {
        mode = 'local'
        allowAddNewData = false
        pinList = true
    }

    var store = {}

    if (config.method === 'optionsselect') {
        store = new Ext.data.JsonStore({
            fields: ['value'],
            data: config.possible
        })
    } else {
        store = new Ext.data.JsonStore({
            id: (config.name || 'tags') + '-store',
            root: 'results',
            autoLoad: false,
            autoSave: false,
            totalProperty: 'total',
            fields: ['value'],
            url: mspre.config['connectorUrlMinishop'],
            baseParams: {
                action: mspre.config.controllerPath + 'getoptions',
                key: config.name,
                start: 0,
                limit: 10
            },
        })
    }

    Ext.applyIf(config, {
        xtype: 'superboxselect',
        allowBlank: true,
        msgTarget: 'under',
        allowAddNewData: allowAddNewData,
        addNewDataOnBlur: true,
        pinList: pinList,
        resizable: true,
        lazyInit: false,
        name: config.name || 'tags',
        anchor: '100%',
        minChars: 1,
        pageSize: 10,
        store: store,
        mode: mode,
        displayField: 'value',
        valueField: 'value',
        triggerAction: 'all',
        extraItemCls: 'x-tag',
        expandBtnCls: 'x-form-trigger',
        clearBtnCls: 'x-form-trigger',
        displayFieldTpl: config.displayFieldTpl || '{value}',
        // fix for setValue
        addValue: function (value) {
            if (Ext.isEmpty(value)) {
                return
            }
            var values = value
            if (!Ext.isArray(value)) {
                value = '' + value
                values = value.split(this.valueDelimiter)
            }
            Ext.each(values, function (val) {
                var record = this.findRecord(this.valueField, val)
                if (record) {
                    this.addRecord(record)
                }
                this.remoteLookup.push(val)
            }, this)
            if (this.mode === 'remote') {
                var q = this.remoteLookup.join(this.queryValuesDelimiter)
                this.doQuery(q, false, true)
            }
        },
        // fix similar queries
        shouldQuery: function (q) {
            if (this.lastQuery) {
                return (q !== this.lastQuery)
            }
            return true
        },
    })
    config.name += '[]'

    Ext.apply(config, {
        listeners: {
            newitem: function (bs, v) {
                bs.addNewItem({value: v})
            },
            beforequery: {
                fn: function (o) {
                    // reset sort
                    o.combo.store.sortInfo = ''
                    if (o.forceAll !== false) {
                        exclude = o.combo.getValue().split(o.combo.valueDelimiter)
                    } else {
                        exclude = []
                    }
                    o.combo.store.baseParams.exclude = Ext.util.JSON.encode(exclude)
                },
                scope: this
            }
        },
    })

    mspre.combo.SuperBoxOptions.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.SuperBoxOptions, Ext.ux.form.SuperBoxSelect)
Ext.reg('mspre-combo-superboxselect', mspre.combo.SuperBoxOptions)

mspre.combo.OptionsSelectCombo = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        xtype: 'superboxselect',
        allowBlank: true,
        msgTarget: 'under',
        allowAddNewData: false,
        addNewDataOnBlur: true,
        pinList: false,
        resizable: true,
        lazyInit: false,
        name: config.name || 'tags',
        anchor: '100%',
        minChars: 1,
        pageSize: 10,
        store: new Ext.data.JsonStore({
            id: (config.name || 'tags') + '-store',
            root: 'results',
            autoLoad: false,
            autoSave: false,
            totalProperty: 'total',
            fields: ['value'],
            url: mspre.config['connectorUrlMinishop'],
            baseParams: {
                action: 'mgr/product/getoptions',
                //action: mspre.config.controllerPath + 'getoptions',
                key: config.name,
                start: 0,
                limit: 10
            },
        }),
        mode: 'local',
        displayField: 'value',
        valueField: 'value',
        triggerAction: 'all',
        extraItemCls: 'x-tag',
        expandBtnCls: 'x-form-trigger',
        clearBtnCls: 'x-form-trigger',
        displayFieldTpl: config.displayFieldTpl || '{value}',
        // fix for setValue
        addValue: function (value) {
            if (Ext.isEmpty(value)) {
                return
            }
            var values = value
            if (!Ext.isArray(value)) {
                value = '' + value
                values = value.split(this.valueDelimiter)
            }
            Ext.each(values, function (val) {
                var record = this.findRecord(this.valueField, val)
                if (record) {
                    this.addRecord(record)
                }
                this.remoteLookup.push(val)
            }, this)
            if (this.mode === 'remote') {
                var q = this.remoteLookup.join(this.queryValuesDelimiter)
                this.doQuery(q, false, true)
            }
        },
        // fix similar queries
        shouldQuery: function (q) {
            if (this.lastQuery) {
                return (q !== this.lastQuery)
            }
            return true
        },
    })
    config.name += '[]'

    Ext.apply(config, {
        listeners: {
            newitem: function (bs, v) {
                bs.addNewItem({value: v})
            },
            beforequery: {
                fn: function (o) {
                    // reset sort
                    o.combo.store.sortInfo = ''
                    if (o.forceAll !== false) {
                        exclude = o.combo.getValue().split(o.combo.valueDelimiter)
                    } else {
                        exclude = []
                    }
                    o.combo.store.baseParams.exclude = Ext.util.JSON.encode(exclude)
                },
                scope: this
            }
        },
    })

    mspre.combo.OptionsSelectCombo.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.OptionsSelectCombo, Ext.ux.form.SuperBoxSelect)
Ext.reg('mspre-combo-optionsselect', mspre.combo.OptionsSelectCombo)

mspre.combo.Combo = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        xtype: 'superboxselect',
        allowBlank: true,
        msgTarget: 'under',
        allowAddNewData: true,
        addNewDataOnBlur: true,
        pinList: false,
        resizable: true,
        lazyInit: false,
        name: config.name || 'tags',
        anchor: '100%',
        minChars: 1,
        pageSize: 10,
        store: new Ext.data.JsonStore({
            id: (config.name || 'tags') + '-store',
            root: 'results',
            autoLoad: false,
            autoSave: false,
            totalProperty: 'total',
            fields: ['value'],
            url: mspre.config['connectorUrlMinishop'],
            baseParams: {
                action: '/mgr/product/getoptions',
                //action: mspre.config.controllerPath + '/mgr/product/getoptions',
                key: config.name,
                start: 0,
                limit: 10
            },
        }),
        mode: 'local',
        displayField: 'value',
        valueField: 'value',
        triggerAction: 'all',
        extraItemCls: 'x-tag',
        expandBtnCls: 'x-form-trigger',
        clearBtnCls: 'x-form-trigger',
        displayFieldTpl: config.displayFieldTpl || '{value}',
        // fix for setValue
        addValue: function (value) {
            if (Ext.isEmpty(value)) {
                return
            }
            var values = value
            if (!Ext.isArray(value)) {
                value = '' + value
                values = value.split(this.valueDelimiter)
            }
            Ext.each(values, function (val) {
                var record = this.findRecord(this.valueField, val)
                if (record) {
                    this.addRecord(record)
                }
                this.remoteLookup.push(val)
            }, this)
            if (this.mode === 'remote') {
                var q = this.remoteLookup.join(this.queryValuesDelimiter)
                this.doQuery(q, false, true)
            }
        },
        // fix similar queries
        shouldQuery: function (q) {
            if (this.lastQuery) {
                return (q !== this.lastQuery)
            }
            return true
        },

    })
    config.name += '[]'

    Ext.apply(config, {
        listeners: {
            newitem: function (bs, v) {
                bs.addNewItem({value: v})
            },
            beforequery: {
                fn: function (o) {
                    // reset sort
                    o.combo.store.sortInfo = ''
                    if (o.forceAll !== false) {
                        exclude = o.combo.getValue().split(o.combo.valueDelimiter)
                    } else {
                        exclude = []
                    }
                    o.combo.store.baseParams.exclude = Ext.util.JSON.encode(exclude)
                },
                scope: this
            }
        },
    })
    if (config.ext_field) {
        var ext = Ext.util.JSON.decode(config.ext_field);
        delete ext['xtype']
        Ext.apply(config, ext)
    }
    mspre.combo.Combo.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.Combo, MODx.combo.ComboBox)
Ext.reg('mspre-combo', mspre.combo.Combo)