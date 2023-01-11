antiBot.grid.Default = function (config) {
    config = config || {}

    if (typeof (config['multi_select']) != 'undefined' && config['multi_select'] == true) {
        config.sm = new Ext.grid.CheckboxSelectionModel()
    }

    Ext.applyIf(config, {
        url: antiBot.config['connector_url'],
        baseParams: {},
        cls: config['cls'] || 'main-wrapper antibot-grid',
        autoHeight: true,
        paging: true,
        remoteSort: true,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        listeners: this.getListeners(config),
        multi_select: true,
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: -10,
            getRowClass: function (rec) {
                var cls = []
                if (rec.data['published'] != undefined && rec.data['published'] == 0) {
                    cls.push('antibot-row-unpublished')
                }
                if (rec.data['active'] != undefined && rec.data['active'] == 0) {
                    cls.push('antibot-row-inactive')
                }
                if (rec.data['deleted'] != undefined && rec.data['deleted'] == 1) {
                    cls.push('antibot-row-deleted')
                }
                if (rec.data['required'] != undefined && rec.data['required'] == 1) {
                    cls.push('antibot-row-required')
                }
                return cls.join(' ')
            }
        },
    })
    antiBot.grid.Default.superclass.constructor.call(this, config)

    if (config.enableDragDrop && config.ddAction) {
        this.on('render', function (grid) {
            grid._initDD(config)
        })
    }
}
Ext.extend(antiBot.grid.Default, MODx.grid.Grid, {

    searchFields: [],

    getFields: function () {
        return [
            'id', 'actions'
        ]
    },

    getColumns: function () {
        return [{
            header: _('id'),
            dataIndex: 'id',
            width: 35,
            sortable: true,
        }, {
            header: _('antibot_actions'),
            dataIndex: 'actions',
            renderer: antiBot.utils.renderActions,
            sortable: false,
            width: 75,
            id: 'actions'
        }]
    },

    getTopBar: function (config) {
        return ['->', this.getSearchField(config, 'query')]
    },

    getListeners: function () {
        return {
            /*
             rowDblClick: function(grid, rowIndex, e) {
             var row = grid.store.getAt(rowIndex);
             this.someAction(grid, e, row);
             }
             */
        }
    },

    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds()
        var row = grid.getStore().getAt(rowIndex)

        var menu = antiBot.utils.getMenu(row.data['actions'], this, ids)

        this.addContextMenuItem(menu)
    },

    onClick: function (e) {
        var elem = e.getTarget()
        if (elem.nodeName == 'BUTTON') {
            var row = this.getSelectionModel().getSelected()
            if (typeof (row) != 'undefined') {
                var action = elem.getAttribute('action')
                if (action == 'showMenu') {
                    var ri = this.getStore().find('id', row.id)
                    return this._showMenu(this, ri, e)
                } else if (typeof this[action] === 'function') {
                    this.menu.record = row.data
                    return this[action](this, e)
                }
            }
        } else if (elem.nodeName == 'A' && elem.href.match(/(\?|\&)a=resource/)) {
            if (e.button == 1 || (e.button == 0 && e.ctrlKey == true)) {
                // Bypass
            } else if (elem.target && elem.target == '_blank') {
                // Bypass
            } else {
                e.preventDefault()
                MODx.loadPage('', elem.href)
            }
        }
        return this.processEvent('click', e)
    },

    refresh: function () {
        this.getStore().reload()
        if (this.config['enableDragDrop'] == true) {
            this.getSelectionModel().clearSelections(true)
        }
    },

    _doSearch: function (tf) {
        this.getStore().baseParams[tf.name] = tf.getValue()
        this.getBottomToolbar().changePage(1)
    },

    _clearSearch: function (tf) {
        this.getStore().baseParams[tf.name] = ''
        this.getBottomToolbar().changePage(1)
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
    },

    _initDD: function (config) {
        var grid = this
        var el = grid.getEl()

        new Ext.dd.DropTarget(el, {
            ddGroup: grid.ddGroup,
            notifyDrop: function (dd, e, data) {
                var store = grid.getStore()
                var target = store.getAt(dd.getDragData(e).rowIndex).id
                var sources = []
                if (data.selections.length < 1 || data.selections[0].id == target) {
                    return false
                }
                for (var i in data.selections) {
                    if (!data.selections.hasOwnProperty(i)) {
                        continue
                    }
                    var row = data.selections[i]
                    sources.push(row.id)
                }

                el.mask(_('loading'), 'x-mask-loading')
                MODx.Ajax.request({
                    url: config.url,
                    params: {
                        action: config.ddAction,
                        sources: Ext.util.JSON.encode(sources),
                        target: target,
                    },
                    listeners: {
                        success: {
                            fn: function () {
                                el.unmask()
                                grid.refresh()
                                if (typeof (grid.reloadTree) == 'function') {
                                    sources.push(target)
                                    grid.reloadTree(sources)
                                }
                            }, scope: grid
                        },
                        failure: {
                            fn: function () {
                                el.unmask()
                            }, scope: grid
                        },
                    }
                })
            },
        })
    },

    _loadStore: function () {
        this.store = new Ext.data.JsonStore({
            url: this.config.url,
            baseParams: this.config.baseParams || {action: this.config.action || 'getList'},
            fields: this.config.fields,
            root: 'results',
            totalProperty: 'total',
            remoteSort: this.config.remoteSort || false,
            storeId: this.config.storeId || Ext.id(),
            autoDestroy: true,
            listeners: {
                load: function (store, rows, data) {
                    store.sortInfo = {
                        field: data.params['sort'] || 'id',
                        direction: data.params['dir'] || 'ASC',
                    }
                    Ext.getCmp('modx-content').doLayout()
                }
            }
        })
    },

    blocked: function (action, name) {

        var ids = this._getSelectedIds()
        if (!ids.length) {
            return false
        }
        Ext.MessageBox.confirm(
            ids.length > 1
                ? _('antibot_blockeds_title_' + name)
                : _('antibot_blocked_title_' + name),
            ids.length > 1
                ? _('antibot_blockeds_confirm_' + name)
                : _('antibot_blocked_confirm_' + name),
            function (val) {
                if (val == 'yes') {
                    var ids = this._getSelectedIds()
                    if (!ids.length) {
                        return false
                    }
                    MODx.Ajax.request({
                        url: antiBot.config.connector_url,
                        params: {
                            action: 'mgr/' + action + '/blocked/' + name,
                            ids: Ext.util.JSON.encode(ids),
                        },
                        listeners: {
                            success: {
                                fn: function (response) {
                                    //this.refresh()
                                    MODx.msg.alert(_('antibot_blocked_success'), response.message)
                                }, scope: this
                            },
                            failure: {
                                fn: function (response) {
                                    MODx.msg.alert(_('error'), response.message)
                                }, scope: this
                            },
                        }
                    })
                }
            }, this
        )
        return true

    },

    dateStart: function (tf) {
        this.getStore().baseParams.date_start = tf.getValue()
        this.getBottomToolbar().changePage(1)
    },
    dateEnd: function (tf) {
        this.getStore().baseParams.date_end = tf.getValue()
        this.getBottomToolbar().changePage(1)
    },

    getSearchField: function (config, field, width) {
        var name = field || 'query'
        this.searchFields.push({
            field: name,
            grid: config.id,
        })
        return {
            id: field ? config.id + '-query-' + field : config.id + '-query',
            name: field ? 'query_' + field : 'query',
            xtype: 'antibot-field-search',
            emptyText: _('antibot_search_' + name),
            width: width || 250,
            listeners: {
                search: {
                    fn: function (field) {
                        this._doSearch(field)
                    }, scope: this
                },
                clear: {
                    fn: function (field) {
                        field.setValue('')
                        this._clearSearch(field)
                    }, scope: this
                },
            }
        }
    },

    /**
     * Сброс значений во всех полях поиска
     */
    resetQueryForm: function () {
        var grid_id = this.config.id
        var store = this.getStore()
        this.searchFields.map(function (row, index) {
            var grid = row['grid']
            if (grid === grid_id) {
                var field = row['field']
                store.baseParams['query_' + field] = ''
                Ext.getCmp(grid_id + '-query-' + field).reset()
            }
        })
    },
    resetForm: function () {
        this.getStore().baseParams.date_start = ''
        this.getStore().baseParams.date_end = ''
        Ext.getCmp(this.config.id + '-begin').reset()
        Ext.getCmp(this.config.id + '-end').reset()

        this.resetQueryForm()
        this.getBottomToolbar().changePage(1)
    },

    _filterByCombo: function (cb) {
        this.getStore().baseParams[cb.name] = cb.value
        this.getBottomToolbar().changePage(1)
    },

})
Ext.reg('antibot-grid-default', antiBot.grid.Default)
