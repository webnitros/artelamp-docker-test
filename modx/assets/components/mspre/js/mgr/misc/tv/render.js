mspre.grid.product.windowsTv = {
    modal: false,
    html: false,
    render: false,
    massActions: false,
    templateBinding: false
}
mspre.grid.product.fieldTv = {}

MODx.fireResourceFormChange = function (f, nv, ov) {
    Ext.getCmp('modx-panel-resource').fireEvent('fieldChange')
}
mspre.grid.product.fireResourceFormChange = function (f, nv, ov) {
    Ext.getCmp('modx-panel-resource').fireEvent('fieldChange')
}

mspre.grid.product.loadComboTvCustom = function (field) {

    var tvname = mspre.grid.product.fieldTv.dataIndex
    var resource = Ext.getCmp(mspre.config.grid_id).selected()

    var grid = Ext.getCmp(mspre.config.grid_id)

    MODx.Ajax.request({
        url: mspre.config.connector_url,
        params: {
            action: 'mgr/common/tv/render',
            tvname: tvname,
            resource: resource,
        },
        listeners: {
            success: {
                fn: function (r) {
                    if (r.success) {

                        if (mspre.grid.product.windowsTv.html) {
                            mspre.grid.product.windowsTv.html.destroy()
                        }

                        mspre.grid.product.windowsTv.html = MODx.load({
                            xtype: 'mspre-window-tv-image-html',
                            id: 'mspre-window-tv-image-html',
                            tvname: tvname,
                            resource: resource,
                            tv_id: r.object.tv_id,
                            outputHtml: r.object.html,
                            listeners: {
                                success: {
                                    fn: function () {
                                        Ext.getCmp('mspre-grid-product').refresh()
                                    }, scope: this
                                },
                                hide: {
                                    fn: function () {
                                        mspre.grid.product.windowsTv.html.destroy()
                                    },
                                    scope: this
                                },
                            }
                        }).show()

                        eval(r.object.js)
                    } else {
                        MODx.msg.alert(_('mspre_error'), r.message)
                    }
                },
                scope: this
            },
            failure: {
                fn: function (response) {
                    MODx.msg.alert(_('mspre_error'), response.message)
                },
                scope: this
            }
        }
    })
    return false
}

/**
 * Проверка доступа к шаблону по ресурсу
 * @param params массив файла tvname и resource или template
 * @param callback функция для обратного вызова
 * @returns {boolean}
 */
mspre.grid.product.accessTemplate = function (params, callback) {
    var grid = Ext.getCmp(mspre.config.grid_id)
    grid.request('mgr/common/tv/checktemplateaccess', params, function (response) {
        if (response.success) {
            if (!response.object.access) {
                mspre.grid.product.templateBinding(response.object, callback)
            } else {
                if (typeof callback === 'function') {
                    callback(response)
                }
                //mspre.grid.product.loadComboTvCustom()
            }
        }
    })
    return true
}

mspre.grid.product.templateBinding = function (object, callback) {
    var grid = mspre.grid.product
    if (grid.windowsTv.templateBinding) {
        grid.windowsTv.templateBinding.destroy()
    }
    grid.windowsTv.templateBinding = MODx.load({
        xtype: 'mspre-window-tv-template-binding',
        object: object,
        listeners: {
            success: {
                fn: function (response) {
                    if (typeof callback === 'function') {
                        callback(response)
                    }
                }, scope: this
            },
            hide: {
                fn: function () {
                    grid.windowsTv.templateBinding.destroy()
                },
                scope: this
            }
        }
    })
    grid.windowsTv.templateBinding.show()
    return true
}
