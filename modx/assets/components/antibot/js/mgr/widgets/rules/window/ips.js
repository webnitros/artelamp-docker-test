antiBot.window.GridIps = function (config) {
    config = config || {}
    config.url = antiBot.config.connector_url

    Ext.applyIf(config, {
        title: _('antibot_rule_grid_ips'),
        width: 1200,
        cls: 'antibot_windows',
        baseParams: {
            action: 'mgr/rule/create'
        }
    })
    antiBot.window.GridIps.superclass.constructor.call(this, config)
}
Ext.extend(antiBot.window.GridIps, antiBot.window.Default, {

    getFields: function (config) {
        return [
            {
                xtype: 'antibot-rule-window-ips-grid',
                id: 'antibot-rule-window-ips-grid',
                record: config.record,
                baseParams: {
                    rule_id: config.record.id,
                    action: 'mgr/rule/ips/getlist'
                }
            }
        ]
    }
})
Ext.reg('antibot-rule-window-ips', antiBot.window.GridIps)

antiBot.grid.GridIps = function (config) {
    config = config || {}

    config = config || {}
    if (!config.id) {
        config.id = 'antibot-rule-window-ips-grid'
    }
    Ext.applyIf(config, {
        tbar: this.getTopBar(config),
        columns: [
            {
                header: _('antibot_ips_methods'),
                dataIndex: 'methods',
                sortable: true,
                width: 50
            },
            {
                header: _('antibot_ips_codes_response'),
                dataIndex: 'codes_response',
                sortable: true,
                width: 50
            },
            {
                header: _('antibot_ips_user_id'),
                dataIndex: 'user_id',
                sortable: true,
                width: 50
            }, {
                header: _('antibot_ips_guest_id'),
                dataIndex: 'guest',
                sortable: true,
                width: 50
            }, {
                header: _('antibot_ips_ip'),
                dataIndex: 'ip',
                sortable: true,
                width: 100
            }, {
                header: _('antibot_ips_total'),
                dataIndex: 'total',
                sortable: true,
                width: 100
            }, {
                header: _('antibot_ips_user_agent'),
                dataIndex: 'user_agent',
                sortable: true,
                width: 100
            }],
        fields: ['codes_response','methods','user_id', 'guest', 'ip', 'total', 'user_agent'],
        url: antiBot.config.connector_url,
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: 0
        },
        paging: true,
        pageSize: 10,
        remoteSort: true,
        autoHeight: true
    })
    antiBot.grid.GridIps.superclass.constructor.call(this, config)
}
Ext.extend(antiBot.grid.GridIps, antiBot.grid.Default, {

    getTopBar: function (config) {

        var method = config.record.hit_method
        var core_response = config.record.core_response
        var hour = config.record.hour
        var hits_per_minute = config.record.hits_per_minute

        var text = 'Показать все хиты по методам ' + method + ' c кодом ответа ' + core_response + ' в течении ' + hour + ' ч. где превышен лимит в ' + hits_per_minute + ' заходов'
        return [
            {
                xtype: 'displayfield',
                fieldLabel: _('antibot_check_bot_ip'),
                description: _('antibot_check_bot_ip'),
                value: text,
                id: config.id + '-ip',
            },
            '->', this.getSearchField(config)
        ]
    },

})
Ext.reg('antibot-rule-window-ips-grid', antiBot.grid.GridIps)
