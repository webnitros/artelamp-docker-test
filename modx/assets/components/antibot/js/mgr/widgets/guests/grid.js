antiBot.grid.Guests = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'antibot-grid-guest';
    }

    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/guest/getlist',
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
    antiBot.grid.Guests.superclass.constructor.call(this, config);
};
Ext.extend(antiBot.grid.Guests, antiBot.grid.Default, {

    getFields: function () {
        return [
            'id', 'happy','hits', 'user_id', 'username', 'ip', 'user_agent','createdon','updatedon','blocked','fake', 'actions'
        ];
    },

    getColumns: function () {
        return [
            {header: _('antibot_guest_id'), dataIndex: 'id', width: 20, sortable: true},
            {header: _('antibot_guest_hits'), dataIndex: 'hits', sortable: true, width: 20},
            {header: _('antibot_guest_username'), dataIndex: 'username', sortable: true, width: 50},
            {header: _('antibot_guest_ip'), dataIndex: 'ip', sortable: true, width: 70},
            {header: _('antibot_guest_user_agent'), dataIndex: 'user_agent', sortable: true, width: 110},
            {header: _('antibot_guest_happy'), dataIndex: 'happy', sortable: true, width: 50, renderer: antiBot.utils.renderBoolean},
            {header: _('antibot_guest_fake'), dataIndex: 'fake', sortable: true, width: 50, renderer: antiBot.utils.renderBoolean},
            {header: _('antibot_guest_blocked'), dataIndex: 'blocked', sortable: true, width: 50, renderer: antiBot.utils.renderBoolean},
            {header: _('antibot_guest_createdon'), dataIndex: 'createdon', sortable: true, width: 110},
            {header: _('antibot_guest_updatedon'), dataIndex: 'updatedon', sortable: true, width: 110},
           /* {
                header: _('antibot_grid_actions'),
                dataIndex: 'actions',
                id: 'actions',
                width: 50,
                renderer: antiBot.utils.renderActions
            }*/
        ];
    },

    getTopBar: function (config) {
        return [
            {
                text: '<i class="icon icon-cogs"></i> ',
                menu: [
                    {
                        text: '<i class="icon icon-trash"></i>&nbsp;' + _('antibot_guest_btn_remove_all'),
                        handler: this.removeAllGuests,
                        scope: this
                    }
                ]
            },
            {
                xtype: 'datefield',
                width: 200,
                id: config.id + '-begin',
                emptyText: _('antibot_guest_form_begin'),
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
                emptyText: _('antibot_guest_form_end'),
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
            {
                text: '<i class="icon icon-times"></i> ' + _('antibot_btn_reset'),
                handler: this.resetForm,
                scope: this,
            },
            '->',
            this.getSearchField(config),
        ];
    },

    removeAllGuests: function () {

        Ext.MessageBox.confirm(
            _('antibot_guest_all_remove'),
            _('antibot_guest_all_remove_confirm'),
            function (val) {
                if (val == 'yes') {
                    MODx.Ajax.request({
                        url: antiBot.config.connector_url,
                        params: {
                            action: 'mgr/guest/remove',
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

    removeGuest: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        Ext.MessageBox.confirm(
            ids.length > 1
                ? _('antibot_guest_remove')
                : _('antibot_guest_remove'),
            ids.length > 1
                ? _('antibot_guest_remove_confirm')
                : _('antibot_guest_remove_confirm'),
            function (val) {
                if (val == 'yes') {

                    var ids = this._getSelectedIds()
                    if (!ids.length) {
                        return false
                    }
                    MODx.Ajax.request({
                        url: antiBot.config.connector_url,
                        params: {
                            action: 'mgr/guest/remove',
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
        return true;
    },

    fakeYandex: function () {
        this.fakeGuest('yandex')
    },

    fakeMail: function () {
        this.fakeGuest('mail')
    },

    fakeGoogle: function () {
        this.fakeGuest('google')
    },

    fakeGuest: function (bot) {
        var ids = this._getSelectedIds()
        if (!ids.length) {
            return false
        }
        MODx.Ajax.request({
            url: antiBot.config.connector_url,
            params: {
                action: 'mgr/guest/fake',
                bot: bot,
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
                        this.refresh()
                    }, scope: this
                },
            }
        })
    },


    actionGuest: function (method) {
        var ids = this._getSelectedIds()
        if (!ids.length) {
            return false
        }
        MODx.Ajax.request({
            url: antiBot.config.connector_url,
            params: {
                action: 'mgr/guest/'+method,
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

    checkBot: function (btn, e) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }
        else if (!this.menu.record) {
            return false;
        }
        var id = this.menu.record.id;



        var w = MODx.load({
            xtype: 'antibot-check-bot-window',
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
            ip: this.menu.record.ip,
        });
        w.show(e.target);
    },

    blockedGuestIp: function () {
        return this.blocked('guest','ip')
    },
    blockedGuestUserAgent: function () {
        return this.blocked('guest','useragent')
    },



    happyEnableGuest: function () {
        this.actionGuest('happy/enable')
    },

    happyDisableGuest: function () {
        this.actionGuest('happy/disable')
    },

});
Ext.reg('antibot-grid-guests', antiBot.grid.Guests);
