mspre.window.Default = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        title: '',
        url: mspre.config['connector_url'],
        cls: 'modx-window mspre-window ' || config['cls'],
        width: 600,
        autoHeight: true,
        allowDrop: false,
        record: {},
        baseParams: {},
        fields: this.getFields(config),
        keys: this.getKeys(config),
        buttons: this.getButtons(config),
        listeners: this.getListeners(config),
    })
    mspre.window.Default.superclass.constructor.call(this, config)

    this.on('hide', function () {
        var w = this
        window.setTimeout(function () {
            w.close()
        }, 200)
    })
}
Ext.extend(mspre.window.Default, MODx.Window, {

    getFields: function () {
        return []
    },

    getButtons: function (config) {
        return [{
            text: config.cancelBtnText || _('cancel'),
            scope: this,
            handler: function () {
                config.closeAction !== 'close'
                    ? this.hide()
                    : this.close()
            }
        }, {
            text: config.saveBtnText || _('save'),
            cls: 'primary-button',
            scope: this,
            handler: this.submit,
        }]
    },

    getKeys: function () {
        return [{
            key: Ext.EventObject.ENTER,
            shift: true,
            fn: function () {
                this.submit()
            }, scope: this
        }]
    },

    getListeners: function () {
        return {}
    },

})
Ext.reg('mspre-window-default', mspre.window.Default)

mspre.window.tableSetup = function (config) {
    config = config || {}
    if (!config.id) {
        config.id = 'mspre-window-table-setup'
    }
    Ext.applyIf(config, {
        title: _('mspre_menu_tooltip_columns'),
        url: mspre.config['connector_url'],
        cls: 'modx-window mspre-window ' || config['cls'],
        width: 1000,
        height: 600,
        autoHeight: true,
        allowDrop: false,
        record: {},
        baseParams: {
            action: 'mgr/common/fields'
        },
        fields: this.getFields(config),
        keys: this.getKeys(config),
        buttons: this.getButtons(config),
        listeners: this.getListeners(config),
    })
    mspre.window.tableSetup.superclass.constructor.call(this, config)
}
Ext.extend(mspre.window.tableSetup, MODx.Window, {

    getFields: function (config) {
        var fields = [
            {
                xtype: 'hidden',
                readOnly: true,
                name: 'controller',
                id: config.id + '-controller'
            },
            {
                xtype: 'hidden',
                readOnly: true,
                name: 'mode',
                id: config.id + '-mode'
            },
            {
                layout: 'column',
                cls: 'mspre-column',
                items: [{
                    columnWidth: .5,
                    layout: 'form',
                    defaults: {msgTarget: 'under'},
                    items: [
                        {
                            xtype: 'mspre-grid-table-setup',
                            fieldLabel: _('mspre_tablesetup_available_fields'),
                            name: 'available_fields',
                            id: config.id + '-available_fields',
                            values: config.available_fields,
                            enableSize: config.enableSize,
                            cls: 'mspre-grid-table-setup-label',
                        }
                    ]
                }, {
                    columnWidth: .5,
                    layout: 'form',
                    defaults: {msgTarget: 'under'},
                    items: [{
                        xtype: 'mspre-grid-table-setup',
                        fieldLabel: _('mspre_tablesetup_selected_fields'),
                        name: 'selected_fields',
                        id: config.id + '-selected_fields',
                        values: config.selected_fields,
                        enableSize: config.enableSize,
                        cls: 'mspre-grid-table-setup-label',
                    }]
                },]
            },
            {
                tooltip: _('mspre_menu_tooltip_create'),
                text: '<i class="icon icon-plus"></i> Выбрать тв параметр по шаблону ',
                xtype: 'button',
                style: 'margin-right: 10px',
                handler: this.loadModalTemplate
            }
        ]

        if (mspre.config.controller === 'product') {
            fields.push({
                tooltip: _('mspre_menu_tooltip_create'),
                text: '<i class="icon icon-plus"></i> Выбрать опции по категории',
                xtype: 'button',
                handler: this.loadModalCategory
            })
        }
        /*
        *
        * ,

            */
        return fields
    },

    loadModal: function (actionfields, loadxtype, prefix, key_field) {

        var grid = Ext.getCmp(mspre.config.grid_id)
        if (grid.windows.combo) {
            grid.windows.combo.destroy()
        }

        grid.windows.combo = MODx.load({
            xtype: 'mspre-window-table-setup-category',
            loadxtype: loadxtype,
            prefix: prefix,
            key_field: key_field,
            actionfields: actionfields,
            listeners: {
                success: {
                    fn: function () {
                        grid.disabledMask()
                        grid.refresh()
                    }, scope: this
                },
                hide: {
                    fn: function () {
                        grid.windows.combo.destroy()
                    },
                    scope: this
                }
            }
        })
        grid.windows.combo.show()

    },
    loadModalCategory: function (config) {
        Ext.getCmp('mspre-window-table-setup').loadModal('mgr/misc/option/getlist', 'mspre-combo-category', 'options', 'key')
    },
    loadModalTemplate: function (config) {
        Ext.getCmp('mspre-window-table-setup').loadModal('mgr/misc/tv/getlist', 'mspre-combo-template', 'tv', 'name')
    },
    getButtons: function (config) {
        return [{
            text: config.cancelBtnText || _('cancel'),
            scope: this,
            handler: function () {
                config.closeAction !== 'close'
                    ? this.hide()
                    : this.close()
            }
        }, {
            text: config.saveBtnText || _('save'),
            cls: 'primary-button',
            scope: this,
            handler: this.submit,
        }]
    }
    
    
    ,submit: function(close) {
        // Сбрасываем поиск и собираем поля пользователя которые мы сохранили
        var setup = Ext.getCmp(this.config.id + '-selected_fields')
        var store = setup.getStore()
        store.filter('field', '', true, true)
        setup.prepareProperties();
        mspre.window.tableSetup.superclass.submit.call(this, close)
    }
    ,

    getKeys: function () {
        return [{
            key: Ext.EventObject.ENTER,
            shift: true,
            fn: function () {
                this.submit()
            }, scope: this
        }]
    },

    getListeners: function () {
        return {}
    },

})
Ext.reg('mspre-window-table-setup', mspre.window.tableSetup)

// Options Change field
mspre.window.TableSetupCategory = function (config) {

    var tpl = new Ext.XTemplate(
        '<tpl for="."><div class="x-combo-list-item">',
        '<small>({id})</small> <b>{templatename}</b></span>',
        '</div></tpl>', {
            compiled: true
        })
    if (config.loadxtype === 'mspre-combo-category') {
        tpl = new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item">',
            '<small>({id})</small> <b>{pagetitle}</b></span>',
            '</div></tpl>', {
                compiled: true
            })
    }
    config = config || {}
    Ext.applyIf(config, {
        title: _('mspre_combo_change_category_title'),
        url: mspre.config.connector_url,
        id: 'mspre-window-table-setup-category-modal',
        width: 400,
        maxHeight: 250,
        autoHeight: true,
        buttons: [
            {
                text: config.cancelBtnText || _('cancel')
                , scope: this
                , handler: function () {
                config.closeAction !== 'close' ? this.hide() : this.close()
            }
            }, {
                text: config.saveBtnText || _('mspre_combo_tv_field_change')
                , cls: 'primary-button'
                , scope: this
                , handler: this.addFiels
            }
        ],
        fields: [
            {
                xtype: config.loadxtype,
                tpl: tpl,
                minChars: 2,
                id: 'mspre-window-table-setup-category-field',
                fieldLabel: _('mspre_combo_change_category_label'),
                allowBlank: false,
                anchor: '90%',
                listeners: {
                    select: {
                        fn: function (field, record) {
                            this.change(field, record)
                        },
                        scope: this
                    }
                }
            },
            {
                xtype: 'displayfield',
                id: 'mspre-window-table-setup-category-total',
                fieldLabel: 'Выбранно полей',
                name: 'total',
                hiddenName: 'total',
                value: 0,
                anchor: '90%',
            },
            {
                xtype: 'displayfield',
                id: 'mspre-window-table-setup-category-fields',
                fieldLabel: 'Поля',
                name: 'fields[]',
                hiddenName: 'fields',
                value: '',
                anchor: '90%',
            },
        ]
    })
    mspre.window.TableSetupCategory.superclass.constructor.call(this, config)
}
Ext.extend(mspre.window.TableSetupCategory, MODx.Window, {
    total: 0,
    key_field: 'key',
    fields: {},
    addFiels: function () {
        if (this.total === 0) {
            MODx.msg.alert(_('error'), 'Выбранно 0 полей.')
            return false
        }

        var sourceEl = Ext.getCmp('mspre-window-table-setup-available_fields')
        var sourceStore = sourceEl.getStore()

        var targetEl = Ext.getCmp('mspre-window-table-setup-selected_fields')
        var targetStore = targetEl.getStore()

        var index = -1
        var key = ''
        var record = {}
        for (var i = 0; i < this.fields.length; i++) {
            if (!this.fields.hasOwnProperty(i)) {
                continue
            }
            key = this.prefix + '-' + this.fields[i][this.key_field]
            index = sourceStore.find('field', key)
            if (index !== -1) {
                record = sourceStore.data.items[index]
                if (record) {
                    targetStore.add(new Ext.data.Record(record.data))
                    targetEl.prepareProperties()
                    sourceStore.remove(record)
                }
            }
        }

        var grid = Ext.getCmp(mspre.config.grid_id)
        grid.windows.combo.destroy()
    },
    change: function (field, record) {
        var modal = this
        var grid = Ext.getCmp(mspre.config.grid_id)
        grid.request(this.actionfields, {category: record.id}, function (response) {
            if (response.success) {

                var total = Ext.getCmp('mspre-window-table-setup-category-total')
                total.setValue(response.total)

                var f = {}
                var field = []
                for (var i = 0; i < response.results.length; i++) {
                    if (!response.results.hasOwnProperty(i)) {
                        continue
                    }
                    f = response.results[i]

                    field[i] = '<b>' + f[modal.key_field] + '</b> - ' + f.caption
                }

                modal.total = response.total
                modal.key_field = modal.key_field
                modal.fields = response.results

                var fields = Ext.getCmp('mspre-window-table-setup-category-fields')
                fields.setValue(field.join('<br>'))

            } else {
                MODx.msg.alert(_('error'), 'Не удалось получить поля')
            }
        })
    },

})
Ext.reg('mspre-window-table-setup-category', mspre.window.TableSetupCategory)