ReadLogJson.grid.Requests = function (config) {
    config = config || {}
    if (!config.id) {
        config.id = 'readlogjson-grid-requests'
    }

    if (!config.multiple) {
        config.multiple = 'request'
    }

    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/request/getlist',
            sort: 'id',
            dir: 'DESC'
        },
        stateful: true,
        stateId: config.id,
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: 0,
            getRowClass: function (rec) {
                return !rec.data.active
                    ? 'readlogjson-grid-row-disabled'
                    : ''
            }
        },
        paging: true,
        remoteSort: true,
        autoHeight: true,
    })
    ReadLogJson.grid.Requests.superclass.constructor.call(this, config)
}
Ext.extend(ReadLogJson.grid.Requests, ReadLogJson.grid.Default, {

    getFields: function () {
        return [
            'id', 'url', 'method', 'event', 'timeout', 'msg', 'error', 'read', 'read_in', 'processed', 'processed_in', 'createdon', 'updatedon', 'active', 'actions'
        ]
    },

    getColumns: function () {
        return [
            {header: _('readlogjson_request_id'), dataIndex: 'id', width: 20, sortable: true, hidden: true},
            {header: _('readlogjson_request_url'), dataIndex: 'url', width: 150, sortable: true},
            {header: _('readlogjson_request_method'), dataIndex: 'method', width: 100, sortable: true},
            {header: _('readlogjson_request_event'), dataIndex: 'event', width: 100, sortable: true},

            {header: _('readlogjson_request_msg'), dataIndex: 'msg', width: 100, sortable: true, hidden: true},
            {header: _('readlogjson_request_error'), dataIndex: 'error', width: 70, sortable: true, hidden: true, renderer: ReadLogJson.utils.renderBoolean},
            {header: _('readlogjson_request_read'), dataIndex: 'read', width: 70, sortable: true, hidden: true, renderer: ReadLogJson.utils.renderBoolean},
            {header: _('readlogjson_request_processed'), dataIndex: 'processed', width: 70, sortable: true, renderer: ReadLogJson.utils.renderBoolean},
            {header: _('readlogjson_request_read_in'), dataIndex: 'read_in', width: 75, hidden: true, renderer: ReadLogJson.utils.formatDate},
            {header: _('readlogjson_request_processed_in'), dataIndex: 'processed_in', width: 75, renderer: ReadLogJson.utils.formatDate},
            {header: _('readlogjson_request_createdon'), dataIndex: 'createdon', width: 75, renderer: ReadLogJson.utils.formatDate},
            {header: _('readlogjson_request_updatedon'), dataIndex: 'updatedon', width: 75, renderer: ReadLogJson.utils.formatDate},
            {
                header: _('readlogjson_grid_actions'),
                dataIndex: 'actions',
                id: 'actions',
                width: 50,
                renderer: ReadLogJson.utils.renderActions
            }
        ]
    },

    getTopBar: function () {
        return [{
            text: '<i class="icon icon-plus"></i>&nbsp;' + _('readlogjson_request_create'),
            handler: this.createRequest,
            scope: this
        }, {
            xtype: 'readlogjson-combo-filter-processed',
            name: 'processed',
            width: 210,
            custm: true,
            clear: true,
            addall: true,
            value: '',
            listeners: {
                select: {
                    fn: this._filterByCombo,
                    scope: this
                },
                afterrender: {
                    fn: this._filterByCombo,
                    scope: this
                }
            }
        }, {
            xtype: 'readlogjson-combo-filter-method',
            name: 'method',
            width: 210,
            custm: true,
            clear: true,
            addall: true,
            value: '',
            listeners: {
                select: {
                    fn: this._filterByCombo,
                    scope: this
                },
                afterrender: {
                    fn: this._filterByCombo,
                    scope: this
                }
            }
        },
            '->', this.getSearchField()]
    },

    getListeners: function () {
        return {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex)
                this.updateRequest(grid, e, row)
            },
        }
    },

    createRequest: function (btn, e) {
        var w = MODx.load({
            xtype: 'readlogjson-request-window-create',
            id: Ext.id(),
            listeners: {
                success: {
                    fn: function () {
                        this.refresh()
                    }, scope: this
                }
            }
        })
        w.reset()
        w.setValues({
            url: 'https://fandeco.ru/rest/articles',
            event: 'test',
            request: '{}',
            response: '{}',
            //params: '{}',
            //errors: '{}',
        })
        w.show(e.target)
    },

    window_log: null,

    updateRequest: function (btn, e, row) {
        if (typeof (row) != 'undefined') {
            this.menu.record = row.data
        } else if (!this.menu.record) {
            return false
        }
        var id = this.menu.record.id

        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/request/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {

                        if (ReadLogJson.grid.Requests.window_log) {
                            ReadLogJson.grid.Requests.window_log.close()
                        }

                        ReadLogJson.grid.Requests.window_log = MODx.load({
                            xtype: 'readlogjson-request-window-update',
                            id: Ext.id(),
                            record: r,
                            listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh()
                                    }, scope: this
                                }
                            }
                        })
                        ReadLogJson.grid.Requests.window_log.reset()
                        ReadLogJson.grid.Requests.window_log.setValues(r.object)
                        ReadLogJson.grid.Requests.window_log.show(e.target)

                    }, scope: this
                }
            }
        })
    },

    removeRequest: function () {
        this.action('remove')
    },

    // copy
    copyRequest: function (btn, e) {
        if (typeof (row) != 'undefined') {
            this.menu.record = row.data
        } else if (!this.menu.record) {
            return false
        }
        var id = this.menu.record.id

        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/request/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {

                        var w = MODx.load({
                            xtype: 'readlogjson-request-window-create',
                            id: Ext.id(),
                            record: r,
                            listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh()
                                    }, scope: this
                                }
                            }
                        })
                        w.reset()
                        w.setValues(r.object)
                        w.show(e.target)

                    }, scope: this
                }
            }
        })
    },

})
Ext.reg('readlogjson-grid-requests', ReadLogJson.grid.Requests)
