// numberfield
mspre.window.GridNumberfield = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        id: 'mspre-grid-update-number-field',
        listeners: {
            blur: {
                fn: function (e) {
                    Ext.getCmp(mspre.config.grid_id).config.saveParams = {
                        updategrig: e.autoEl.name
                    }
                },
                scope: this
            }
        }
    })
    mspre.window.GridNumberfield.superclass.constructor.call(this, config)
}
Ext.extend(mspre.window.GridNumberfield, Ext.form.NumberField)
Ext.reg('mspre-grid-numberfield', mspre.window.GridNumberfield)

// textfield
mspre.window.GridTextfield = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        id: 'mspre-grid-update-text-field',
        listeners: {
            blur: {
                fn: function (e) {
                    Ext.getCmp(mspre.config.grid_id).config.saveParams = {
                        updategrig: e.autoEl.name
                    }
                },
                scope: this
            }
        }
    })
    mspre.window.GridTextfield.superclass.constructor.call(this, config)
}
Ext.extend(mspre.window.GridTextfield, Ext.form.TextField)
Ext.reg('mspre-grid-textfield', mspre.window.GridTextfield)

// textarea
mspre.window.GridTextarea = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        id: 'mspre-grid-update-text-field',
        listeners: {
            blur: {
                fn: function (e) {
                    Ext.getCmp(mspre.config.grid_id).config.saveParams = {
                        updategrig: e.autoEl.name
                    }
                },
                scope: this
            }
        }
    })
    mspre.window.GridTextarea.superclass.constructor.call(this, config)
}
Ext.extend(mspre.window.GridTextarea, Ext.form.TextArea)
Ext.reg('mspre-grid-textarea', mspre.window.GridTextarea)

// Boolean
mspre.combo.GridBoolean = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        store: new Ext.data.SimpleStore({
            fields: ['d', 'v'],
            data: [[_('yes'), _('yes')], [_('no'), _('no')]]
        }),
        listeners: {
            blur: {
                fn: function (e) {
                    Ext.getCmp(mspre.config.grid_id).config.saveParams = {
                        updategrig: e.autoEl.name
                    }
                },
                scope: this
            }
        }
    })
    mspre.combo.GridBoolean.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.GridBoolean, MODx.combo.Boolean)
Ext.reg('mspre-grid-boolean', mspre.combo.GridBoolean)

// Boolean True False
mspre.combo.GridBooleanTrue = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        listeners: {
            blur: {
                fn: function (e) {
                    Ext.getCmp(mspre.config.grid_id).config.saveParams = {
                        updategrig: e.autoEl.name
                    }
                },
                scope: this
            }
        }
    })
    mspre.combo.GridBooleanTrue.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.GridBooleanTrue, MODx.combo.Boolean)
Ext.reg('mspre-grid-boolean-true', mspre.combo.GridBooleanTrue)

mspre.combo.GridResource = function (config) {
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
        },
        listeners: {
            blur: {
                fn: function (e) {
                    Ext.getCmp(mspre.config.grid_id).config.saveParams = {
                        updategrig: e.autoEl.name
                    }
                },
                scope: this
            }
        }
    })
    mspre.combo.GridResource.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.GridResource, MODx.combo.ComboBox)
Ext.reg('mspre-grid-resource', mspre.combo.GridResource)
