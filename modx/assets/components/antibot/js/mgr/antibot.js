var antiBot = function (config) {
    config = config || {};
    antiBot.superclass.constructor.call(this, config);
};
Ext.extend(antiBot, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('antibot', antiBot);

antiBot = new antiBot();