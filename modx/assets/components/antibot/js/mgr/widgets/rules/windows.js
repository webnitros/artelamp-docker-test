antiBot.window.CreateRule = function (config) {
    config = config || {}
    config.url = antiBot.config.connector_url

    Ext.applyIf(config, {
        title: _('antibot_rule_create'),
        width: 800,
        cls: 'antibot_windows',
        baseParams: {
            action: 'mgr/rule/create'
        }
    })
    antiBot.window.CreateRule.superclass.constructor.call(this, config)
}
Ext.extend(antiBot.window.CreateRule, antiBot.window.Default, {

    getFields: function (config) {
        return [
            {xtype: 'hidden', name: 'id', id: config.id + '-id'},
            {
                xtype: 'textfield',
                fieldLabel: _('antibot_rule_name'),
                name: 'name',
                id: config.id + '-name',
                anchor: '99%',
                allowBlank: true,
            }, {
                xtype: 'textfield',
                fieldLabel: _('antibot_rule_method'),
                name: 'hit_method',
                id: config.id + '-hit_method',
                anchor: '99%'
            },
            {
                xtype: 'textfield',
                fieldLabel: _('antibot_rule_core_response'),
                name: 'core_response',
                id: config.id + '-core_response',
                anchor: '99%'
            },
            {
                layout: 'column',
                fieldLabel: _('antibot_rule_time'),
                description: _('antibot_rule_time_desc'),
                items: [{
                    columnWidth: .5,
                    layout: 'form',
                    defaults: {msgTarget: 'under'},
                    items: [{
                        xtype: 'numberfield',
                        fieldLabel: _('antibot_rule_hour'),
                        name: 'hour',
                        minValue: 0,
                        maxValue: 255,
                        anchor: '99%',
                        id: config.id + '-hour'
                    }]
                }, {
                    columnWidth: .5,
                    layout: 'form',
                    defaults: {msgTarget: 'under'},
                    items: [{
                        fieldLabel: _('antibot_rule_hits_per_minute'),
                        xtype: 'numberfield',
                        name: 'hits_per_minute',
                        anchor: '99%',
                        minValue: 0,
                        maxValue: 10000,
                        id: config.id + '-hits_per_minute'
                    }],
                }]

            },
          /*  {
                xtype: 'xcheckbox',
                boxLabel: _('antibot_rule_captcha'),
                name: 'captcha',
                id: config.id + '-captcha',
                checked: true,
            },*/
            {
                xtype: 'xcheckbox',
                boxLabel: _('antibot_rule_active'),
                name: 'active',
                id: config.id + '-active',
                checked: true,
            }
        ]


    }
})
Ext.reg('antibot-rule-window-create', antiBot.window.CreateRule)

antiBot.window.UpdateRule = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        title: _('antibot_rule_update'),
        baseParams: {
            action: 'mgr/rule/update',
            resource_id: config.resource_id
        },
    })
    antiBot.window.UpdateRule.superclass.constructor.call(this, config)

}
Ext.extend(antiBot.window.UpdateRule, antiBot.window.CreateRule)
Ext.reg('antibot-rule-window-update', antiBot.window.UpdateRule)
