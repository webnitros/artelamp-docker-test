mspre.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'mspre-panel-home',
            renderTo: 'mspre-panel-home-div'
        }]
    });
    mspre.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(mspre.page.Home, MODx.Component);
Ext.reg('mspre-page-home', mspre.page.Home);