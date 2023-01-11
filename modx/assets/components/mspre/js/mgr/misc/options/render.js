mspre.grid.product.windowsOptions = {
    modal: false,
    html: false,
    render: false,
    categoryBinding: false
}
mspre.grid.product.fieldOptions = {}

mspre.grid.product.loadComboOptionsCustom = function () {

    var field = mspre.grid.product.fieldOptions

    if (!field.dataIndex) {
        MODx.msg.alert('Ошибка', 'не удалось получить имя поля')
        return false
    }

    var grid = Ext.getCmp(mspre.config.grid_id)
    var product_id = grid.getSelectionModel().getSelected().data.id
    var optionsname = field.dataIndex

    MODx.Ajax.request({
        url: mspre.config.connector_url,
        params: {
            action: mspre.config.controllerPath + 'options/getoptions',
            key: optionsname,
            product_id: product_id,
            start: 0,
            limit: 10,
        },
        listeners: {
            success: {
                fn: function (r) {
                    if (r.success) {
                        if (grid.windows.combo) {
                            grid.windows.combo.destroy()
                        }
                        grid.windows.combo = MODx.load({
                            xtype: 'mspre-window-options-superboxcombo',
                            record: r.object,
                            listeners: {
                                success: {
                                    fn: function () {
                                        grid.disabledMask()
                                        grid.refresh()
                                    }, scope: this
                                },
                                hide: {
                                    fn: function () {
                                        grid.windows.combo.destroy()
                                    },
                                    scope: this
                                }
                            }
                        })
                        grid.windows.combo.show()
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
}

/**
 * Проверка доступа к шаблону по ресурсу
 * @param params массив файла tvname и resource или template
 * @param callback функция для обратного вызова
 * @returns {boolean}
 */
mspre.grid.product.accessCategory = function (params, callback) {
    if (params === undefined) {
        MODx.msg.alert(_('error'), _('mspre_options_category_error_params'))
    }
    var grid = Ext.getCmp(mspre.config.grid_id)
    grid.request('mgr/controller/product/options/checkcategoryaccess', params, function (response) {
        if (response.success) {
            if (!response.object.access) {
                mspre.grid.product.categoryBinding(response.object, callback)
            } else {
                if (typeof callback === 'function') {
                    callback(response)
                }
            }
        }
    })
    return true
}

mspre.grid.product.categoryBinding = function (object, callback) {
    var grid = mspre.grid.product
    if (grid.windowsOptions.categoryBinding) {
        grid.windowsOptions.categoryBinding.destroy()
    }
    grid.windowsOptions.categoryBinding = MODx.load({
        xtype: 'mspre-window-options-category-binding',
        object: object,
        listeners: {
            success: {
                fn: function (response) {
                    if (typeof callback === 'function') {
                        callback(response.a.result)
                    }
                }, scope: this
            },
            failure: {
                fn: function (r) {
                    var msg = null;
                    if (r.a.result) {
                        msg = r.a.result.message
                    } else if (r.a.response) {
                        msg = r.a.response.statusText
                    }
                    if (msg) {
                        MODx.msg.alert(_('error'), msg)
                    }
                },
                scope: this
            },
            hide: {
                fn: function () {
                    grid.windowsOptions.categoryBinding.destroy()
                },
                scope: this
            }
        }
    })
    grid.windowsOptions.categoryBinding.show()
    return true
}