var mspre = function (config) {
    config = config || {}
    mspre.superclass.constructor.call(this, config)
}
Ext.extend(mspre, Ext.Component, {
    disableRefresh: false,
    progress: null,

    message_wait: null,
    formExt: null,
    offsetCyclic: false,
    recordCount: 0, // Счетчик записей
    listRecords: {},  // Массив с записями

    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}, store: {}
})
Ext.reg('mspre', mspre)

mspre = new mspre()