var msPromoCode2 = function (config) {
    config = config || {};
    msPromoCode2.superclass.constructor.call(this, config);
};
Ext.extend(msPromoCode2, Ext.Component, {
    page: {},
    window: {},
    grid: {},
    tree: {},
    panel: {},
    formpanel: {},
    combo: {},
    config: {},
    view: {},
    ux: {},
    utils: {},
    renderer: {},
    fields: {},
});
Ext.reg('mspromocode2', msPromoCode2);

msPromoCode2 = new msPromoCode2();