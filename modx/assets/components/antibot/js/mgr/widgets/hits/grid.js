antiBot.grid.Hits = function (config) {
    config = config || {}
    if (!config.id) {
        config.id = 'antibot-grid-hits'
    }

    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/hit/getlist',
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
                    ? 'antibot-grid-row-disabled'
                    : ''
            }
        },
        paging: true,
        remoteSort: true,
        autoHeight: true,
        multi_select: true,
        cellWrap: true
    })
    antiBot.grid.Hits.superclass.constructor.call(this, config)
}
Ext.extend(antiBot.grid.Hits, antiBot.grid.Default, {

    getFields: function () {
        return [
            'id', 'url', 'url_from', 'method', 'ip', 'user_agent', 'code_response', 'username', 'context', 'user_id', 'blocked', 'guest_id', 'createdon', 'updatedon', 'actions'
        ]
    },
    urlPage: function (value) {
        return String.format('<a class="antibot_link" target="_blank" href="//{0}">{0}</a>', value)
    },
    getColumns: function () {
        return [
            {header: _('antibot_hit_id'), dataIndex: 'id', width: 20, sortable: true},
            {header: _('antibot_hit_username'), dataIndex: 'username', sortable: true, width: 50},
            {header: _('antibot_hit_guest_id'), dataIndex: 'guest_id', sortable: true, width: 30},
            {
                header: _('antibot_hit_url'), dataIndex: 'url', width: 50, sortable: false,
                renderer: this.urlPage
            },
            {header: _('antibot_hit_url_from'), dataIndex: 'url_from', sortable: true, width: 50},
            {header: _('antibot_hit_context'), dataIndex: 'context', sortable: true, width: 50},
            {header: _('antibot_hit_code_response'), dataIndex: 'code_response', sortable: true, width: 40},
            {header: _('antibot_hit_method'), dataIndex: 'method', sortable: true, width: 30},
            {header: _('antibot_hit_ip'), dataIndex: 'ip', sortable: true, width: 40},
            {header: _('antibot_hit_user_agent'), dataIndex: 'user_agent', sortable: true, width: 50},
            {
                header: _('antibot_hit_blocked'),
                dataIndex: 'blocked',
                sortable: true,
                width: 50,
                renderer: antiBot.utils.renderBoolean
            },
            {header: _('antibot_hit_createdon'), dataIndex: 'createdon', sortable: true, width: 50},
            {header: _('antibot_hit_updatedon'), dataIndex: 'updatedon', sortable: true, width: 50},
            /*{
                header: _('antibot_grid_actions'),
                dataIndex: 'actions',
                id: 'actions',
                width: 50,
                renderer: antiBot.utils.renderActions
            }*/
        ]
    },

    getTopBar: function (config) {

        return [

            {
                text: '<i class="icon icon-cogs"></i> ',
                menu: [
                    {
                        text: '<i class="icon icon-trash"></i>&nbsp;' + _('antibot_hit_btn_remove_all'),
                        handler: this.removeAllHits,
                        scope: this
                    },

                ]
            },

            {
                xtype: 'antiBot-combo-guest',
                id: config.id + '-guest',
                width: 210,
                name: 'guest',
                custm: true,
                clear: true,
                addall: true,
                value: 0,
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
                xtype: 'antiBot-combo-methods',
                id: config.id + '-method',
                width: 210,
                name: 'method',
                custm: true,
                clear: true,
                addall: true,
                value: 0,
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
                xtype: 'antiBot-combo-code-response',
                id: config.id + '-code_response',
                width: 210,
                name: 'code_response',
                custm: true,
                clear: true,
                addall: true,
                value: 0,
                listeners: {
                    select: {
                        fn: this._filterByCombo,
                        scope: this
                    },
                    afterrender: {
                        fn: this._filterByCombo,
                        scope: this
                    }
                },
            },
            {
                xtype: 'datefield',
                width: 200,
                id: config.id + '-begin',
                emptyText: _('antibot_hit_form_begin'),
                name: 'date_start',
                format: MODx.config['manager_date_format'] || 'Y-m-d',
                listeners: {
                    select: {
                        fn: function (tf) {
                            this.dateStart(tf)
                        }, scope: this
                    },
                },
            }, {
                xtype: 'datefield',
                width: 200,
                id: config.id + '-end',
                emptyText: _('antibot_hit_form_end'),
                name: 'date_end',
                format: MODx.config['manager_date_format'] || 'Y-m-d',
                listeners: {
                    select: {
                        fn: function (tf) {
                            this.dateEnd(tf)
                        }, scope: this
                    },
                },
            },

            '->',
            this.getSearchField(config, 'ip',200),
            this.getSearchField(config, 'url'),
            this.getSearchField(config, 'url_from'),
            this.getSearchField(config, 'user_agent'),

            {
                text: '<i class="icon icon-times"></i> ' + _('antibot_btn_reset'),
                handler: this.resetForm,
                scope: this,
            }
        ]
    },

    getListeners: function () {
        return {}
    },

    removeHit: function () {
        var ids = this._getSelectedIds()
        if (!ids.length) {
            return false
        }
        Ext.MessageBox.confirm(
            ids.length > 1
                ? _('antibot_hit_remove')
                : _('antibot_hit_remove'),
            ids.length > 1
                ? _('antibot_hit_remove_confirm')
                : _('antibot_hit_remove_confirm'),
            function (val) {
                if (val == 'yes') {
                    var ids = this._getSelectedIds()
                    if (!ids.length) {
                        return false
                    }
                    MODx.Ajax.request({
                        url: antiBot.config.connector_url,
                        params: {
                            action: 'mgr/hit/remove',
                            ids: Ext.util.JSON.encode(ids),
                        },
                        listeners: {
                            success: {
                                fn: function () {
                                    this.refresh()
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

    blockedHitIp: function () {
        return this.blocked('hit', 'ip')
    },
    blockedHitUserAgent: function () {
        return this.blocked('hit', 'useragent')
    },

    removeAllHits: function () {

        Ext.MessageBox.confirm(
            _('antibot_hit_all_remove'),
            _('antibot_hit_all_remove_confirm'),
            function (val) {
                if (val == 'yes') {
                    MODx.Ajax.request({
                        url: antiBot.config.connector_url,
                        params: {
                            action: 'mgr/hit/remove',
                        },
                        listeners: {
                            success: {
                                fn: function () {
                                    this.refresh()
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

    },
    actionHit: function (method) {
        var ids = this._getSelectedIds()
        if (!ids.length) {
            return false
        }
        MODx.Ajax.request({
            url: antiBot.config.connector_url,
            params: {
                action: 'mgr/hit/multiple',
                method: method,
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh()
                    }, scope: this
                },
                failure: {
                    fn: function (response) {
                        MODx.msg.alert(_('error'), response.message)
                    }, scope: this
                },
            }
        })
    },

    resetForm: function () {
        this.getStore().baseParams.date_start = ''
        this.getStore().baseParams.date_end = ''
        this.getStore().baseParams.code_response = ''
        this.getStore().baseParams.method = ''
        this.getStore().baseParams.guest = ''
        this.resetQueryForm()
        this.getBottomToolbar().changePage(1)
    },

})
Ext.reg('antibot-grid-hits', antiBot.grid.Hits)
