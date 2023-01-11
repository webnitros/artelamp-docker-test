mspre.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        /*
         stateful: true,
         stateId: 'mspre-panel-home',
         stateEvents: ['tabchange'],
         getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
         */
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('mspre') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [
                {
                    title: _('mspre_items'),
                    layout: 'anchor',
                    items: [
                        {
                            html: _('mspre_intro_msg'),
                            cls: 'panel-desc',
                        }, {
                            xtype: 'mspre-grid-items',
                            cls: 'main-wrapper',
                        }
                    ]
                }
            ]
        }]
    });
    mspre.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(mspre.panel.Home, MODx.Panel);
Ext.reg('mspre-panel-home', mspre.panel.Home);
