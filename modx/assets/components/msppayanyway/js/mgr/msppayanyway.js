var msppayanyway = function (config) {
	config = config || {};
	msppayanyway.superclass.constructor.call(this, config);
};
Ext.extend(msppayanyway, Ext.Component, {
	page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, tools: {}
});
Ext.reg('msppayanyway', msppayanyway);

msppayanyway = new msppayanyway();