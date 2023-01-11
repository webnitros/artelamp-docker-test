mspre.grid.transactions = function (config) {
    config = config || {}
    if (!config.id) {
        config.id = 'mspre-grid-transactions'
    }

    this.sm = new Ext.grid.CheckboxSelectionModel()
    Ext.applyIf(config, {
        id: config.id,
        cls: config['cls'] || 'main-wrapper mspre-grid-transactions',
        autoHeight: true,
        paging: true,
        remoteSort: true,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: -10,
            getRowClass: function (rec) {
                var cls = []
                if (rec.data['published'] != undefined && rec.data['published'] == 0) {
                    cls.push('modextra-row-unpublished')
                }
                if (rec.data['active'] != undefined && rec.data['active'] == 0) {
                    cls.push('modextra-row-inactive')
                }
                if (rec.data['deleted'] != undefined && rec.data['deleted'] == 1) {
                    cls.push('modextra-row-deleted')
                }
                if (rec.data['required'] != undefined && rec.data['required'] == 1) {
                    cls.push('modextra-row-required')
                }
                return cls.join(' ')
            }
        },
        baseParams: {
            action: 'mgr/transactions/getlist',
        },

        url: mspre.config.connector_url,
        listeners: this.getListeners(config),
        sm: this.sm,
        multi_select: true,
    })

    mspre.grid.transactions.superclass.constructor.call(this, config)
}
Ext.extend(mspre.grid.transactions, MODx.grid.Grid, {

    getFields: function () {
        return [
            'id', 'product_id', 'field', 'increase', 'round', 'updatedon', 'oldValue', 'newValue', 'actions',
        ]
    },
    getColumns: function () {
        return [this.sm,
            {header: _('mspre_transactions_id'), dataIndex: 'id', width: 20, sortable: true},
            {header: _('mspre_transactions_product_id'), dataIndex: 'product_id', sortable: true, width: 30},
            {header: _('mspre_transactions_field'), dataIndex: 'field', sortable: true, width: 50},
            {header: _('mspre_transactions_increase'), dataIndex: 'increase', sortable: true, width: 50},
            {header: _('mspre_transactions_round'), dataIndex: 'round', sortable: true, width: 70},
            {header: _('mspre_transactions_updatedon'), dataIndex: 'updatedon', sortable: true, width: 60},
            {header: _('mspre_transactions_oldValue'), dataIndex: 'oldValue', sortable: true, width: 40},
            {header: _('mspre_transactions_newValue'), dataIndex: 'newValue', sortable: true, width: 40},
        ]
    },
    getTopBar: function () {
        return [
          //cls, icon, title, action, field_name, field_value, combo_id
            new Ext.Button({
               text: '<i class="icon icon-download"></i>&nbsp;'+_('mspre_transactions_actions'),
               cls: 'x-btn-text mspre-actions-menu',
               menu: {
                    id: 'exportusers-x-menu',
                    items: [
                        {
                            text: String.format(
                              '<span class="{0}"><i class="x-menu-item-icon {1}"></i>{2}</span>',
                              '', 'icon icon-remove', _('mspre_transactions_canceled')
                            ),
                            handler: this.canceledOperations,
                            scope: this,
                        },
                        {
                            text: String.format(
                              '<span class="{0}"><i class="x-menu-item-icon {1}"></i>{2}</span>',
                              '', 'icon icon-trash-o red', _('mspre_transactions_remove')
                            ),
                            handler: this.removeOperations,
                            scope: this,
                        }
                    ]
                },
            }),

            {
                xtype: 'datefield',
                id: 'mspre_transactions-start',
                emptyText: _('mspre_transactions_form_start'),
                name: 'date_start',
                width: 160,
                format: 'Y-m-d',
                listeners: {
                    select: {
                        fn: function (field) {
                            this.fireEvent('change', field)
                        }, scope: this
                    },
                },
            }, {
                xtype: 'datefield',
                id: 'mspre_transactions-end',
                emptyText: _('mspre_transactions_form_end'),
                name: 'date_end',
                width: 160,
                format: 'Y-m-d',
                listeners: {
                    select: {
                        fn: function (field) {
                            this.fireEvent('change', field)
                        }, scope: this
                    },
                },
            }, {
                xtype: 'mspre-combo-price-increase',
                width: 250,
                id: 'mspre_transactions-increase',
                emptyText: _('mspre_transactions_form_increase'),
                name: 'increase',
                listeners: {
                    select: {
                        fn: function (field) {
                            this.fireEvent('change', field)
                        }, scope: this
                    },
                },
            }, {
                xtype: 'mspre-combo-price-round',
                width: 210,
                id: 'mspre_transactions-round',
                emptyText: _('mspre_transactions_form_round'),
                name: 'round',
                listeners: {
                    select: {
                        fn: function (field) {
                            this.fireEvent('change', field)
                        }, scope: this
                    },
                },
            }, '->', {
                text: '<i class="icon icon-refresh"></i>',
                handler: this.refreshOperations,
                scope: this
            }, this.getSearchField()

        ]
    },

    getSearchField: function (width) {
        return {
            xtype: 'mspre-field-search',
            width: width || 250,
            emptyText: _('mspre_transaction_emptyquery'),
            listeners: {
                search: {
                    fn: function (field) {
                        this._doSearch(field)
                    }, scope: this
                },
                clear: {
                    fn: function (field) {
                        field.setValue('')
                        this._clearSearch()
                    }, scope: this
                },
            }
        }
    },

    _doSearch: function (tf) {
        this.getStore().baseParams.query = tf.getValue()
        this.getBottomToolbar().changePage(1)
    },

    _clearSearch: function () {
        this.getStore().baseParams.date_start = ''
        this.getStore().baseParams.increase = ''
        this.getStore().baseParams.round = ''
        this.getStore().baseParams.date_end = ''
        this.getStore().baseParams.query = ''

        var start = Ext.getCmp('mspre_transactions-start')
        start.setValue('')

        var end = Ext.getCmp('mspre_transactions-end')
        end.setValue('')

        var increase = Ext.getCmp('mspre_transactions-increase')
        increase.setValue('')

        var round = Ext.getCmp('mspre_transactions-round')
        round.setValue('')

        this.getBottomToolbar().changePage(1)
    },

    getListeners: function () {
        return {
            change: function (field) {

                this.getStore().baseParams[field.name] = field.value
                this.getBottomToolbar().changePage(1)
            }
        }
    },

    refreshOperations: function (btn, e) {
        this.refresh()
    },
    canceledOperations: function (btn, e) {

        var ids = this._getSelectedIds()

        MODx.msg.confirm({
            title: _('mspre_transactions_canceled_title'),
            text: _('mspre_transactions_canceled_confirm'),
            url: mspre.config.connector_url,
            params: {
                action: 'mgr/transactions/canceled',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh()
                    }, scope: this
                }
            }
        })
        return true
    },
    removeOperations: function (btn, e) {
        var ids = this._getSelectedIds()
        MODx.msg.confirm({
            title: _('mspre_transactions_remove_title'),
            text: _('mspre_transactions_remove_confirm'),
            url: mspre.config.connector_url,
            params: {
                action: 'mgr/transactions/remove',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh()
                    }, scope: this
                }
            }
        })
        return true
    },
    _getSelectedIds: function () {
        var ids = []
        var selected = this.getSelectionModel().getSelections()
        for (var i in selected) {
            if (!selected.hasOwnProperty(i)) {
                continue
            }
            ids.push(selected[i]['id'])
        }
        return ids
    }

})
Ext.reg('mspre-grid-transactions', mspre.grid.transactions)