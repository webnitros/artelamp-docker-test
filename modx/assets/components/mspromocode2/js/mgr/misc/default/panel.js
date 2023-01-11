msPromoCode2.panel.Default = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        // id: Ext.id(),
        items: this.getItems(config),
        listeners: this.getListeners(config),

        baseCls: 'modx-formpanel',
        layout: 'anchor',
        hideMode: 'offsets',
        autoHeight: true,
    });
    msPromoCode2.panel.Default.superclass.constructor.call(this, config);
};
Ext.extend(msPromoCode2.panel.Default, MODx.Panel, {
    getItems: function (config) {
        return [];
    },

    getListeners: function (config) {
        return {};
    },
});
Ext.reg('mspc2-panel-default', msPromoCode2.panel.Default);
