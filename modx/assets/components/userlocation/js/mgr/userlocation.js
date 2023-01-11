var userlocation = function (config) {
    config = config || {};
    userlocation.superclass.constructor.call(this, config);
};
Ext.extend(userlocation, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, tools: {}
});
Ext.reg('userlocation', userlocation);

userlocation = new userlocation();