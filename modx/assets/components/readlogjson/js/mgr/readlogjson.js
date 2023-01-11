var ReadLogJson = function (config) {
    config = config || {};
    ReadLogJson.superclass.constructor.call(this, config);
};
Ext.extend(ReadLogJson, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}, buttons: {}
});
Ext.reg('readlogjson', ReadLogJson);

ReadLogJson = new ReadLogJson();