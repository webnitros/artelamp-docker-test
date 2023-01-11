ReadLogJson.window.CreateRequest = function (config) {
    config = config || {}
    config.url = ReadLogJson.config.connector_url

    Ext.applyIf(config, {
        title: _('readlogjson_request_create'),
        width: 1200,
        cls: 'readlogjson_windows',
        baseParams: {
            action: 'mgr/request/create'
        }
    })
    ReadLogJson.window.CreateRequest.superclass.constructor.call(this, config)

    this.on('success', function (data) {
        if (data.a.result.object) {
            // Авто запуск при создании новой подписик
            if (data.a.result.object.mode) {
                if (data.a.result.object.mode === 'new') {
                    var grid = Ext.getCmp('readlogjson-grid-requests')
                    grid.updateRequest(grid, '', {data: data.a.result.object})
                }
            }
        }
    }, this)
}
Ext.extend(ReadLogJson.window.CreateRequest, ReadLogJson.window.Default, {

    getFields: function (config) {

        var heightJson = 250
        return [
            {xtype: 'hidden', name: 'id', id: config.id + '-id'},
            {
                xtype: 'textfield',
                fieldLabel: _('readlogjson_request_url'),
                name: 'url',
                id: config.id + '-url',
                anchor: '99%',
                allowBlank: false,
            },

            {
                layout: 'column',
                hideLabels: true,
                items: [
                    {
                        columnWidth: .33,
                        layout: 'form',
                        defaults: {msgTarget: 'under'},
                        items: [

                            {
                                xtype: 'readlogjson-combo-filter-method',
                                fieldLabel: _('readlogjson_request_method'),
                                name: 'method_name',
                                id: config.id + '-method_name',
                                anchor: '99%',
                                allowBlank: false,
                            },
                            {
                                xtype: 'xcheckbox',
                                boxLabel: _('readlogjson_request_read'),
                                name: 'read',
                                id: config.id + '-read',
                                checked: true,
                            },
                        ]
                    }, {
                        columnWidth: .33,
                        layout: 'form',
                        defaults: {msgTarget: 'under'},
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: _('readlogjson_request_event'),
                                name: 'event',
                                id: config.id + '-event',
                                anchor: '99%',
                                allowBlank: false,
                            },
                            {
                                xtype: 'xcheckbox',
                                boxLabel: _('readlogjson_request_error'),
                                name: 'error',
                                id: config.id + '-error',
                                checked: true,
                            },
                        ],
                    }, {
                        columnWidth: .33,
                        layout: 'form',
                        defaults: {msgTarget: 'under'},
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: _('readlogjson_request_timeout'),
                                name: 'timeout',
                                id: config.id + '-timeout',
                                anchor: '99%',
                                allowBlank: false,
                            }
                        ],
                    }]
            },

            {
                layout: 'column',
                hideLabels: true,
                items: [
                    {
                        columnWidth: .5,
                        layout: 'form',
                        defaults: {msgTarget: 'under'},
                        items: [
                            {
                                xtype: 'button',
                                cls: 'readlogjson-btn-request',
                                text: 'Послать запрос',
                                handler: this.sendRequest,
                                scope: this,
                            },
                            {
                                xtype: Ext.ComponentMgr.types['modx-texteditor'] ? 'modx-texteditor' : 'textarea'
                                // , mimeType: 'application/x-php'
                                , mimeType: 'application/json'
                                , height: heightJson
                                , fieldLabel: _('readlogjson_request_request')
                                , name: 'request'
                                , id: config.id + '-request'
                                , allowBlank: false
                                , anchor: '100%'
                            }
                        ]
                    }, {
                        columnWidth: .5,
                        layout: 'form',
                        defaults: {msgTarget: 'under'},
                        items: [

                            {
                                xtype: Ext.ComponentMgr.types['modx-texteditor'] ? 'modx-texteditor' : 'textarea'
                                // , mimeType: 'application/x-php'
                                , mimeType: 'application/json'
                                , width: 'auto'
                                , height: heightJson
                                , fieldLabel: _('readlogjson_request_response')
                                , name: 'response'
                                , id: config.id + '-response'
                                , allowBlank: false
                                , anchor: '100%'
                            }
                        ],
                    }]
            },

        ]

    },

    sendRequest: function () {

        var data = ReadLogJson.grid.Requests.window_log.fp.getForm().getValues()

        ReadLogJson.grid.Requests.window_log.fp.getForm().setValues({
            response:'{}'
        })


        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/request/send',
                id: this.config.record.object.id,
                request: data.request,
                method_name: data.method_name,
                url: data.url,
                timeout: data.timeout
            },
            listeners: {
                success: {
                    fn: function (r) {
                        console.log(r)

                        if (r.success === true) {
                            ReadLogJson.grid.Requests.window_log.fp.getForm().setValues({
                                response: r.object.response_raw
                            })
                        } else {
                            console.log(22)
                        }

                    }, scope: this
                },
                failure: {
                    fn: function (r) {
                        MODx.msg.alert(_('error'), r.message)
                        var grid = Ext.getCmp('readlogjson-grid-requests')
                        grid.refresh()
                    }, scope: this
                }
            }
        })
    }
})
Ext.reg('readlogjson-request-window-create', ReadLogJson.window.CreateRequest)

ReadLogJson.window.UpdateRequest = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        title: _('readlogjson_request_update'),
        baseParams: {
            action: 'mgr/request/update',
            resource_id: config.resource_id
        },
    })
    ReadLogJson.window.UpdateRequest.superclass.constructor.call(this, config)
}
Ext.extend(ReadLogJson.window.UpdateRequest, ReadLogJson.window.CreateRequest)
Ext.reg('readlogjson-request-window-update', ReadLogJson.window.UpdateRequest)
