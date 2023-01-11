ReadLogJson.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'readlogjson-panel-home',
            renderTo: 'readlogjson-panel-home-div'
        }]
    });
    ReadLogJson.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(ReadLogJson.page.Home, MODx.Component);
Ext.reg('readlogjson-page-home', ReadLogJson.page.Home);