antiBot.window.CreateCheckBot = function (config) {
    config = config || {}
    config.url = antiBot.config.connector_url

    Ext.applyIf(config, {
        title: _('antibot_stoplist_create'),
        width: 400,
        cls: 'antibot_windows',
        baseParams: {
            action: 'mgr/stoplist/create',
            resource_id: config.resource_id
        },
        buttons: this.getButtons(config),
    })
    antiBot.window.CreateCheckBot.superclass.constructor.call(this, config)
}
Ext.extend(antiBot.window.CreateCheckBot, antiBot.window.Default, {

    getButtons: function () {
        return [
            {
                text: '<i class="icon icon-check"></i> ' + _('antibot_action_check_ip'),
                handler: this.checkIp,
                scope: this,
                cls: 'primary-button',
            }
        ]
    },

    checkIp: function (config) {

        var f = this.fp.getForm()
        var ip = f.findField('ip').value
        var bot = f.findField('bot').value
        MODx.Ajax.request({
            url: antiBot.config.connector_url,
            params: {
                action: 'mgr/guest/fake',
                ip: ip,
                bot: bot,
            },
            listeners: {
                success: {
                    fn: function (res) {
                        if (!res.object.status) {
                            this.blockedIP(ip, res.message)
                        } else {
                            MODx.msg.alert(_('antibot_blocked_title'), _('antibot_btn_blocked_is_check'))
                        }
                    }, scope: this
                }
            }
        })

    },
    blockedIP: function (ip, message) {

        Ext.MessageBox.show({
            title: _('antibot_blocked_title'),
            msg: message,
            width: 500,
            buttons: {
                yes: _('antibot_btn_blocked_yes'),
                no: _('antibot_btn_blocked_no'),
                cancel: _('antibot_btn_blocked_cancel'),
            },
            fn: function (e) {
                // Обработает только выбранные записи со страницы
                if (e == 'yes') {

                    MODx.Ajax.request({
                        url: antiBot.config.connector_url,
                        params: {
                            action: 'mgr//blocked/',
                            ip: ip,
                        },
                        listeners: {
                            success: {
                                fn: function (response) {
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
                    return false
                }

                // Будут обрабатываться все найденные ресрурсы с учетом фильтров
                if (e == 'no') {


                }
            },
            icon: Ext.MessageBox.QUESTION
        })

    },
    getFields: function (config) {
        return [
            {xtype: 'hidden', name: 'id', id: config.id + '-id'},
            {
                xtype: 'displayfield',
                fieldLabel: _('antibot_check_bot_ip'),
                description: _('antibot_check_bot_ip'),
                name: 'ip',
                id: config.id + '-ip',
                anchor: '99%',
                allowBlank: true,
            },
            {
                xtype: 'antiBot-combo-bots',
                fieldLabel: _('antibot_bots_change'),
                description: _('antibot_bots_change_description'),
                name: 'bot',
                id: config.id + '-bot',
                anchor: '99%',
                allowBlank: true,
            }
        ]
    }
})
Ext.reg('antibot-check-bot-window', antiBot.window.CreateCheckBot)

