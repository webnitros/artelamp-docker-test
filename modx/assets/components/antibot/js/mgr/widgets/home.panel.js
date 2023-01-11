antiBot.panel.Home = function (config) {
    config = config || {}


    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('antibot') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            stateful: true,
            stateId: 'antibot-panel-home',
            stateEvents: ['tabchange'],
            getState: function () {
                return {activeTab: this.items.indexOf(this.getActiveTab())}
            },
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('antibot_guests'),
                layout: 'anchor',
                items: [{
                    html: _('antibot_intro_msg'),
                    cls: 'panel-desc',
                }, {
                    xtype: 'antibot-grid-guests',
                    cls: 'main-wrapper',
                }]
            }, {
                title: _('antibot_hits'),
                layout: 'anchor',
                items: [{
                    html: _('antibot_intro_msg'),
                    cls: 'panel-desc',
                }, {
                    xtype: 'antibot-grid-hits',
                    cls: 'main-wrapper',
                }]
            }, {
                title: _('antibot_stoplists'),
                layout: 'anchor',
                items: [{
                    html: _('antibot_intro_msg'),
                    cls: 'panel-desc',
                }, {
                    xtype: 'antibot-grid-stoplists',
                    cls: 'main-wrapper',
                }]
            }, {
                title: _('antibot_rules'),
                layout: 'anchor',
                items: [{
                    html: _('antibot_intro_msg'),
                    cls: 'panel-desc',
                }, {
                    xtype: 'antibot-grid-rules',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    })
    antiBot.panel.Home.superclass.constructor.call(this, config)
}
Ext.extend(antiBot.panel.Home, MODx.Panel)
Ext.reg('antibot-panel-home', antiBot.panel.Home)
