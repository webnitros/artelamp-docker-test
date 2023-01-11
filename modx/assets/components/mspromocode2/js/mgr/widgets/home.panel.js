msPromoCode2.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        bodyCssClass: 'mspc2-w',
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('mspromocode2') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            enableTabScroll: false,
            /*stateful: true,
            stateId: 'modextra-panel-home',
            stateEvents: ['tabchange'],
            getState: function () {
                return {activeTab: this.items.indexOf(this.getActiveTab())};
            },*/
            items: [{
                title: _('mspc2_tab_coupons'),
                layout: 'anchor',
                items: [{
                    xtype: 'mspc2-grid-coupons',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    });
    msPromoCode2.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(msPromoCode2.panel.Home, MODx.Panel);
Ext.reg('mspromocode2-panel-home', msPromoCode2.panel.Home);
