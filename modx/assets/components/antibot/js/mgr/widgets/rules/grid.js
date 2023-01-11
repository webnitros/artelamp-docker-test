antiBot.grid.Rules = function (config) {
    config = config || {}
    if (!config.id) {
        config.id = 'antibot-grid-rules'
    }

    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/rule/getlist',
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
    })
    antiBot.grid.Rules.superclass.constructor.call(this, config)
}
Ext.extend(antiBot.grid.Rules, antiBot.grid.Default, {

    getFields: function () {
        return [
            'id', 'name', 'hit_method', 'core_response', 'total_ip', 'hour', 'hits_per_minute', 'captcha', 'createdon', 'updatedon', 'active', 'actions'
        ]
    },

    getColumns: function () {
        return [
            {header: _('antibot_rule_id'), dataIndex: 'id', width: 20, sortable: true},
            {header: _('antibot_rule_name'), dataIndex: 'name', sortable: true, width: 90},
            {header: _('antibot_rule_hit_method'), dataIndex: 'hit_method', sortable: false, width: 30},
            {header: _('antibot_rule_core_response'), dataIndex: 'core_response', sortable: false, width: 50},
            {header: _('antibot_rule_hour'), dataIndex: 'hour', sortable: false, width: 80},
            {header: _('antibot_rule_hits_per_minute'), dataIndex: 'hits_per_minute', sortable: false, width: 80},
            {header: _('antibot_rule_captcha'), dataIndex: 'captcha', sortable: false, width: 80, hidden: true},
            {header: _('antibot_rule_total_ip'), dataIndex: 'total_ip', sortable: false, width: 80, hidden: true},
            {header: _('antibot_rule_createdon'), dataIndex: 'createdon', sortable: false, width: 80, hidden: true},
            {header: _('antibot_rule_updatedon'), dataIndex: 'updatedon', sortable: false, width: 80, hidden: true},
            {header: _('antibot_rule_active'), dataIndex: 'active', sortable: false, width: 50, renderer: antiBot.utils.renderBoolean},
            {
                header: _('antibot_grid_actions'),
                dataIndex: 'actions',
                id: 'actions',
                width: 50,
                renderer: antiBot.utils.renderActions
            }
        ]
    },

    getTopBar: function (config) {
        return [{
            text: '<i class="icon icon-plus"></i>&nbsp;' + _('antibot_rule_create'),
            handler: this.createRule,
            scope: this
        }, '->', this.getSearchField(config)]
    },

    getListeners: function () {
        return {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex)
                this.updateRule(grid, e, row)
            },
        }
    },

    createRule: function (btn, e) {
        var w = MODx.load({
            xtype: 'antibot-rule-window-create',
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
            name: 'Авто блокировка',
            hit_method: 'GET',
            core_response: '404',
            hour: 1,
            hits_per_minute: 50,
            active: true,
        })
        w.show(e.target)
    },

    updateRule: function (btn, e, row) {
        if (typeof (row) != 'undefined') {
            this.menu.record = row.data
        } else if (!this.menu.record) {
            return false
        }
        var id = this.menu.record.id

        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/rule/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = MODx.load({
                            xtype: 'antibot-rule-window-update',
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

    removeRule: function () {
        var ids = this._getSelectedIds()
        if (!ids.length) {
            return false
        }
        Ext.MessageBox.confirm(
            ids.length > 1
                ? _('antibot_stoplist_remove')
                : _('antibot_stoplist_remove'),
            ids.length > 1
                ? _('antibot_stoplist_remove_confirm')
                : _('antibot_stoplist_remove_confirm'),
            function (val) {
                if (val == 'yes') {
                    this.actionRule('remove')
                }
            }, this
        )
        return true
    },

    disableRule: function () {
        this.actionRule('disable')
    },

    enableRule: function () {
        this.actionRule('enable')
    },

    colletionRule: function () {
        if (typeof (row) != 'undefined') {
            this.menu.record = row.data
        } else if (!this.menu.record) {
            return false
        }
        var id = this.menu.record.id


        var w = MODx.load({
            xtype: 'antibot-rule-window-ips',
            id: Ext.id(),
            record: this.menu.record,
            listeners: {
                success: {
                    fn: function () {
                        this.refresh()
                    }, scope: this
                }
            }
        })
        w.show()

        return true;
        MODx.Ajax.request({
            url: antiBot.config.connector_url,
            params: {
                action: 'mgr/rule/colletion',
                id: id,
            },
            listeners: {
                success: {
                    fn: function (res) {
                        //this.refresh()


                        //antibot-rule-window-ips



                        //w.reset()
                       // w.setValues(res.object)
                       // w.show(e.target)


                     /*   Ext.MessageBox.show({
                            title: 'Список IP попавших под правило',
                            msg: res.object.outer,
                            width: 900,
                            minWidth: 900,
                        })*/


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

    actionRule: function (method) {
        var ids = this._getSelectedIds()
        if (!ids.length) {
            return false
        }
        MODx.Ajax.request({
            url: antiBot.config.connector_url,
            params: {
                action: 'mgr/rule/' + method,
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

})
Ext.reg('antibot-grid-rules', antiBot.grid.Rules)
