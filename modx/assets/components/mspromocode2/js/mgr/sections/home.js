msPromoCode2.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'mspromocode2-panel-home',
            renderTo: 'mspromocode2-panel-home-div'
        }]
    });
    msPromoCode2.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(msPromoCode2.page.Home, MODx.Component);
Ext.reg('mspromocode2-page-home', msPromoCode2.page.Home);