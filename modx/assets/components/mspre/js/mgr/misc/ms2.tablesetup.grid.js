mspre.grid.TableSetup = function (config) {
    config = config || {}
    if (!config.id) {
        config.id = 'mspre-grid-table-setup'
    }

    Ext.applyIf(config, {
        autoHeight: false,
        height: 400,
        style: 'padding-top: 5px;',
        hideHeaders: true,
        anchor: '100%',
        layout: 'anchor',
        tbar: this.getTopBar(config),
        viewConfig: {
            forceFit: true
        },
        fields: ['dd', 'field', 'remove'],
        columns: this.getColumns(config),
        plugins: this.getPlugins(config),
        listeners: this.getListeners(config),
        bbar: this.getBottomBar(config),
        bodyCssClass: 'x-menu',
        cls: 'mspre-grid',
    })
    mspre.grid.TableSetup.superclass.constructor.call(this, config)
}

Ext.extend(mspre.grid.TableSetup, MODx.grid.LocalGrid, {

    getTopBar: function (config) {
        return ['->', this.getSearchField(config)];
    },

    getSearchField: function (config) {
        return {
            xtype: 'mspre-field-search',
            id: config.id + '-search',
            width: config.width || 250,
            enableKeyEvents: true,
            fieldLabel: _('mspre_tablesetup_available_fields'),
            listeners: {
                search: {
                    fn: function (field) {
                        this.store.filter('field', field.getValue(), true, true)
                    }, scope: this
                },
                keyup: {
                    fn: function (field) {
                        this.store.filter('field', field.getValue(), true, true)
                    }, scope: this
                },
                clear: {
                    fn: function (field) {
                        this.store.filter('field', '', true, true)
                    }, scope: this
                },
            }
        };
    },


    getColumns: function (config) {
        var exclude = ''
        switch (config.name) {
            case 'available_fields':
                exclude = 'actions-left'
                break
            case 'selected_fields':
                exclude = 'actions-right'
                break
            default:
                break
        }

        var actions = [
            {
                header: _('mspre_dragging_left'),
                dataIndex: 'left',
                width: 50,
                id: 'actions-left',
                align: 'center',
                renderer: function () {
                    return String.format('\
                    <ul class="mspre-row-actions">\
                       <li>\
                            <button class="btn btn-default icon icon icon-arrow-left action-red" title="{0}" action="dragging"></button>\
                        </li>\
                    </ul>',
                        _('mspre_dragging_dragging')
                    )
                }
            }, {
                header: _('mspre_dragging_field'),
                dataIndex: 'field',
                id: 'mspre-tablesetup-field',
                width: 110, /*,
                editor: {
                    xtype: 'textfield',
                    listeners: {
                        change: {fn: this.prepareProperties, scope: this}
                    }
                }*/
            }, {
                header: _('mspre_dragging_size'),
                dataIndex: 'size',
                id: config.name + '-size',
                align: 'center',
                width: 110,
                editor: {
                    xtype: 'numberfield',
                    align: 'center',
                    listeners: {
                        change: {fn: this.prepareProperties, scope: this}
                    }
                }
            }, {
                header: _('mspre_dragging_right'),
                dataIndex: 'right',
                width: 20,
                id: 'actions-right',
                align: 'center',
                renderer: function () {
                    return String.format('\
                    <ul class="mspre-row-actions">\
                       <li>\
                            <button class="btn btn-default icon icon icon-arrow-right action-red" title="{0}" action="dragging"></button>\
                        </li>\
                    </ul>',
                        _('mspre_dragging')
                    )
                }
            }, {
                header: _('sort'),
                dataIndex: 'dd',
                width: 30,
                id: config.name + '-sort',
                align: 'center',
                renderer: function () {
                    return String.format(
                        '<div class="sort icon icon-sort" style="cursor:move;" title="{0}"></div>',
                        _('move')
                    )
                }
            }]

        var add = []
        for (var i = 0; i < actions.length; i++) {
            if (!actions.hasOwnProperty(i)) {
                continue
            }
            var action = actions[i]
            var id = action['id']

            if (exclude === id) {
                continue
            }

            if (!config.enableSize) {
                if ('available_fields-size' === id || 'selected_fields-size' === id) {
                    continue
                }
            }

            if ('available_fields-sort' === id) {
                continue
            }

            add.push(action)
        }
        return add
    },

    getBottomBar: function (config) {
        return [{
            xtype: 'hidden',
            id: config.id + '-fields',
            name: 'fields'
        }]
    },

    getPlugins: function (config) {
        if (config.name === 'available_fields') {
            return []
        }
        return [new Ext.ux.dd.GridDragDropRowOrder({
            copy: false,
            scrollable: true,
            targetCfg: {},
            listeners: {
                afterrowmove: {fn: this.prepareProperties, scope: this}
            }
        })]
    },

    onTextfieldChange: function (field, newValue, oldValue, options) {
        var grid = field.up('gridpanel')
        grid.store.clearFilter()
        grid.store.load().filter([
            {id: 'name', property: 'name', value: newValue, anyMatch: true}
        ])
    },
    getListeners: function () {
        return {
            viewready: {fn: this.prepareValues, scope: this},
            afteredit: {
                fn: function () {
                    this.prepareProperties()
                    this.addOption()
                }, scope: this
            }
        }
    },

    prepareValues: function () {
        var values = this.values
        if (values) {
            Ext.each(values, function (item) {
                this.store.add(new Ext.data.Record(item))
            }, this)
        }
        this.prepareProperties()
    },

    search: function (query, filed) {
        var n = filed.indexOf(query)

        if (n !== -1) {
            return true
        }
        return false
    },
    prepareProperties: function () {
        var properties = {}
        var items = this.store.data.items
        var tmp = {}
        var selected = this.store.data.items

        for (var i in selected) {
            if (!selected.hasOwnProperty(i)) {
                continue
            }
            var item = selected[i]
           
            tmp[i] = item.data
        }

        properties[this.config.name] = Ext.util.JSON.encode(tmp)
        Ext.getCmp(this.config.id + '-fields').setValue(properties)
        properties = Ext.util.JSON.encode(properties)
        Ext.getCmp(this.config.id + '-fields').setValue(properties)
    },

    addOption: function () {
        if (this.store.collect('field').length !== this.store.data.length) {
            Ext.Msg.alert(_('error'), _('ms2_err_value_duplicate'), function () {
                this.focusValueCell(this.store.data.length - 1)
            }, this)
        }
        this.prepareProperties()
    },

    dragging: function (config) {
        var record = this.getSelectionModel().getSelected()
        if (!record) {
            return false
        }

        if (this.store.data.length === 0) {
            Ext.Msg.alert(_('error'), _('mspe_dragging_error_min_1'), function () {}, this)
            return false
        }

        if ((this.store.collect('field').length !== this.store.data.length) && record.data['field'] === '') {
            this.focusValueCell(this.store.data.length - 1)
        } else {

            var target = false
            var source = this.config.name
            var sourceStore = this.store
            if (source === 'available_fields') {
                target = 'selected_fields'
            }
            if (source === 'selected_fields') {
                target = 'available_fields'
            }

            var targetEl = Ext.getCmp('mspre-window-table-setup-' + target)
            var targetStore = targetEl.store
            targetStore.add(new Ext.data.Record(record.data))
            targetEl.prepareProperties()
            sourceStore.remove(record)
        }
        this.prepareProperties()
    },

    focusValueCell: function (row) {
        this.startEditing(row, 1)
    },

    onClick: function (e) {
        var elem = e.getTarget()
        if (elem.nodeName == 'BUTTON') {
            var row = this.getSelectionModel().getSelected()
            if (typeof(row) != 'undefined') {
                var action = elem.getAttribute('action')
                if (typeof this[action] === 'function') {
                    this.menu.record = row.data
                    return this[action](this)
                }
            }
        }
        return this.processEvent('click', e)
    },

})
Ext.reg('mspre-grid-table-setup', mspre.grid.TableSetup)
