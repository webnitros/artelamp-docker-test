antiBot.grid.StopLists = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'antibot-grid-stoplists';
    }

    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/stoplist/getlist',
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
                  : '';
            }
        },
        paging: true,
        remoteSort: true,
        autoHeight: true,
        multi_select: true,
    });
    antiBot.grid.StopLists.superclass.constructor.call(this, config);
};
Ext.extend(antiBot.grid.StopLists, antiBot.grid.Default, {

    getFields: function () {
        return [
            'id', 'user_agent', 'context', 'mask_1', 'mask_2', 'mask_3','mask_4', 'ip_1', 'ip_2', 'ip_3','ip_4','ip','recaptcha','comment','message','redirect_url', 'active', 'actions'
        ];
    },

    getColumns: function () {
        return [
            {header: _('antibot_stoplist_id'), dataIndex: 'id', width: 20, sortable: true},
            {header: _('antibot_stoplist_user_agent'), dataIndex: 'user_agent', sortable: true, width: 90},
            {header: _('antibot_stoplist_context'), dataIndex: 'context', sortable: false, width: 30},
            {header: _('antibot_stoplist_ip_bloks'), dataIndex: 'ip', sortable: false, width: 50},
            {header: _('antibot_stoplist_comment'), dataIndex: 'comment', sortable: false, width: 80},
            {header: _('antibot_stoplist_message'), dataIndex: 'message', sortable: false, width: 80},
            {header: _('antibot_stoplist_redirect_url'), dataIndex: 'redirect_url', sortable: false, width: 80,hidden: true},
            {header: _('antibot_stoplist_active'), dataIndex: 'active', sortable: false, width: 50, renderer: antiBot.utils.renderBoolean},
            {
                header: _('antibot_grid_actions'),
                dataIndex: 'actions',
                id: 'actions',
                width: 50,
                renderer: antiBot.utils.renderActions
            }
        ];
    },

    getTopBar: function (config) {
        return [{
            text: '<i class="icon icon-plus"></i>&nbsp;' + _('antibot_stoplist_create'),
            handler: this.createStopList,
            scope: this
        }, '->',{
            text: '<i class="icon icon-download"></i>&nbsp;' + _('antibot_stoplist_download'),
            handler: this.downloadStopList,
            scope: this
        }, this.getSearchField(config)];
    },


    getListeners: function () {
        return {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.updateStopList(grid, e, row);
            },
        };
    },

    createStopList: function (btn, e) {
        var w = MODx.load({
            xtype: 'antibot-stoplist-window-create',
            id: Ext.id(),
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        w.reset();
        w.setValues({
            active: true,
            context: 'web',
            message: _('antibot_stoplist_message_value'),
            comment: _('antibot_stoplist_comment_value'),
            ip_1: '',
            ip_2: '',
            ip_3: '',
            ip_4: '',
        });
        w.show(e.target);
    },

    updateStopList: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }
        else if (!this.menu.record) {
            return false;
        }
        var id = this.menu.record.id;

        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/stoplist/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = MODx.load({
                            xtype: 'antibot-stoplist-window-update',
                            id: Ext.id(),
                            record: r,
                            listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh();
                                    }, scope: this
                                }
                            }
                        });
                        w.reset();
                        w.setValues(r.object);
                        w.show(e.target);
                    }, scope: this
                }
            }
        });
    },

    removeStopList: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
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
                    this.actionStopList('remove')
                }
            }, this
        )
        return true;
    },


    downloadStopList: function () {

        Ext.MessageBox.confirm(_('antibot_stoplist_download'),_('antibot_stoplist_download_confirm'),
            function (val) {
                if (val == 'yes') {
                    MODx.Ajax.request({
                        url: antiBot.config.connector_url,
                        params: {
                            action: 'mgr/stoplist/download',
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
        return true;
    },

    disableStopList: function () {
        this.actionStopList('disable')
    },

    enableStopList: function () {
        this.actionStopList('enable')
    },

    actionStopList: function (method) {
        var ids = this._getSelectedIds()
        if (!ids.length) {
            return false
        }
        MODx.Ajax.request({
            url: antiBot.config.connector_url,
            params: {
                action: 'mgr/stoplist/'+method,
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

});
Ext.reg('antibot-grid-stoplists', antiBot.grid.StopLists);
