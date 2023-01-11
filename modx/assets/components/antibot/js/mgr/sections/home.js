antiBot.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'antibot-panel-home',
            renderTo: 'antibot-panel-home-div'
        }]
    });
    antiBot.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(antiBot.page.Home, MODx.Component);
Ext.reg('antibot-page-home', antiBot.page.Home);