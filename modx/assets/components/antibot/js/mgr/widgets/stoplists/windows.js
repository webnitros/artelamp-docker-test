antiBot.window.CreateStopList = function (config) {
    config = config || {}
    config.url = antiBot.config.connector_url

    Ext.applyIf(config, {
        title: _('antibot_stoplist_create'),
        width: 800,
        cls: 'antibot_windows',
        baseParams: {
            action: 'mgr/stoplist/create',
            resource_id: config.resource_id
        }
    })
    antiBot.window.CreateStopList.superclass.constructor.call(this, config)
}
Ext.extend(antiBot.window.CreateStopList, antiBot.window.Default, {

    getFields: function (config) {
        return [
            {xtype: 'hidden', name: 'id', id: config.id + '-id'},
            {
                xtype: 'textfield',
                fieldLabel: _('antibot_stoplist_context'),
                description: _('antibot_stoplist_context_desc'),
                name: 'context',
                id: config.id + '-context',
                anchor: '99%',
                allowBlank: true,
            }, {
                xtype: 'textarea',
                fieldLabel: _('antibot_stoplist_user_agent'),
                description: _('antibot_stoplist_user_agent_desc'),
                name: 'user_agent',
                id: config.id + '-user_agent',
                height: 100,
                anchor: '99%'
            },

            // IP
            {
                layout: 'column',
                fieldLabel: _('antibot_stoplist_ip_bloks'),
                description: _('antibot_stoplist_ip_bloks_desc'),
                items: [{
                    columnWidth: .1,
                    layout: 'form',
                    defaults: {msgTarget: 'under'},
                    items: [{
                        xtype: 'numberfield',
                        labelStyle: 'display: none;',
                        name: 'ip_1',
                        minValue: 0,
                        maxValue: 255,
                        anchor: '99%',
                        id: config.id + '-ip_1'
                    }]
                }, {
                    columnWidth: .1,
                    layout: 'form',
                    defaults: {msgTarget: 'under'},
                    items: [{
                        xtype: 'numberfield',
                        labelStyle: 'display: none;',
                        name: 'ip_2',
                        anchor: '99%',
                        minValue: 0,
                        maxValue: 255,
                        id: config.id + '-ip_2'
                    }],
                }, {
                    columnWidth: .1,
                    layout: 'form',
                    defaults: {msgTarget: 'under'},
                    items: [{
                        xtype: 'numberfield',
                        labelStyle: 'display: none;',
                        name: 'ip_3',
                        anchor: '99%',
                        minValue: 0,
                        maxValue: 255,
                        id: config.id + '-ip_3'
                    }],
                }, {
                    columnWidth: .1,
                    layout: 'form',
                    defaults: {msgTarget: 'under'},
                    items: [{
                        xtype: 'numberfield',
                        labelStyle: 'display: none;',
                        name: 'ip_4',
                        anchor: '99%',
                        minValue: 0,
                        maxValue: 255,
                        id: config.id + '-ip_4'
                    }],
                }]

            }, {
                xtype: 'textarea',
                fieldLabel: _('antibot_stoplist_message'),
                name: 'message',
                id: config.id + '-message',
                height: 100,
                anchor: '99%'
            }, {
                xtype: 'textfield',
                fieldLabel: _('antibot_stoplist_redirect_url'),
                description: _('antibot_stoplist_redirect_url_desc'),
                name: 'redirect_url',
                id: config.id + '-redirect_url',
                anchor: '99%',
                allowBlank: true,
            },
            {
                xtype: 'textarea',
                fieldLabel: _('antibot_stoplist_comment'),
                name: 'comment',
                id: config.id + '-comment',
                height: 100,
                anchor: '99%'
            },
            /*{
                xtype: 'xcheckbox',
                boxLabel: _('antibot_stoplist_recaptcha'),
                name: 'recaptcha',
                id: config.id + '-recaptcha',
                checked: true,
            },*/
            {
                xtype: 'xcheckbox',
                boxLabel: _('antibot_stoplist_active'),
                name: 'active',
                id: config.id + '-active',
                checked: true,
            }
        ]


    }
})
Ext.reg('antibot-stoplist-window-create', antiBot.window.CreateStopList)

antiBot.window.UpdateStopList = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        title: _('antibot_stoplist_update'),
        baseParams: {
            action: 'mgr/stoplist/update',
            resource_id: config.resource_id
        },
    })
    antiBot.window.UpdateStopList.superclass.constructor.call(this, config)

}
Ext.extend(antiBot.window.UpdateStopList, antiBot.window.CreateStopList)
Ext.reg('antibot-stoplist-window-update', antiBot.window.UpdateStopList)