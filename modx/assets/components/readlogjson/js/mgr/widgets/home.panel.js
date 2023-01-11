ReadLogJson.panel.Home = function (config) {
    config = config || {}
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',

        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('readlogjson') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            stateful: true,
            stateId: 'readlogjson-panel-home',
            stateEvents: ['tabchange'],
            getState: function () {return {activeTab: this.items.indexOf(this.getActiveTab())}},
            items: [{
                title: _('readlogjson_requests'),
                layout: 'anchor',
                items: [{
                    html: _('readlogjson_intro_msg'),
                    cls: 'panel-desc',
                }, {
                    xtype: 'readlogjson-grid-requests',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    })
    ReadLogJson.panel.Home.superclass.constructor.call(this, config)
}
Ext.extend(ReadLogJson.panel.Home, MODx.Panel)
Ext.reg('readlogjson-panel-home', ReadLogJson.panel.Home)

Ext.onReady(function () {
    if (ReadLogJson.config.help_buttons.length > 0) {
        ReadLogJson.buttons.help = function (config) {
            config = config || {}
            for (var i = 0; i < ReadLogJson.config.help_buttons.length; i++) {
                if (!ReadLogJson.config.help_buttons.hasOwnProperty(i)) {
                    continue
                }
                ReadLogJson.config.help_buttons[i]['handler'] = this.loadPaneURl
            }
            Ext.applyIf(config, {
                buttons: ReadLogJson.config.help_buttons
            })
            ReadLogJson.buttons.help.superclass.constructor.call(this, config)
        }
        Ext.extend(ReadLogJson.buttons.help, MODx.toolbar.ActionButtons, {
            loadPaneURl: function (b) {
                var url = b.url
                var text = b.text
                if (!url || !url.length) { return false }
                if (url.substring(0, 4) !== 'http') {
                    url = MODx.config.base_help_url + url
                }
                MODx.helpWindow = new Ext.Window({
                    title: text
                    , width: 850
                    , height: 350
                    , resizable: true
                    , maximizable: true
                    , modal: false
                    , layout: 'fit'
                    , bodyStyle: 'padding: 0;'
                    , items: [{
                        xtype: 'container',
                        layout: {
                            type: 'vbox',
                            align: 'stretch'
                        },
                        width: '100%',
                        height: '100%',
                        items: [{
                            autoEl: {
                                tag: 'iframe',
                                src: url,
                                width: '100%',
                                height: '100%',
                                frameBorder: 0
                            }
                        }]
                    }]
                    //,html: '<iframe src="' + url + '" width="100%" height="100%" frameborder="0"></iframe>'
                })
                MODx.helpWindow.show(b)
                return true
            }
        })

        Ext.reg('readlogjson-buttons-help', ReadLogJson.buttons.help)
        MODx.add('readlogjson-buttons-help')
    }
})
