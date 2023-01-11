/**
 * Компонент для экспорта данных
 * @param classExport
 * @param profile_id
 */
msExportUsersExcel.export = {
    area: null,
    process: function (classExport, profile_id) {
        var exportCallback = function (btn, text) {
            if (btn === 'yes') {
                var topic = '/msexportusersexcel/'
                var register = 'mgr'
                var remove_read = true
                var con2 = MODx.load({
                    xtype: 'modx-console'
                    , register: register
                    , topic: topic
                    , remove_read: remove_read
                    , show_filename: 0
                    , listeners: {
                        'shutdown': {
                            fn: function () {

                                /* do code here when you close the console */
                            }, scope: this
                        }
                    }
                })
                con2.show(Ext.getBody())

                var baseParamsGrid = {}

                // Только для таблиц
                if (msExportUsersExcel.export.area) {
                    var element = Ext.getCmp(msExportUsersExcel.export.area)
                    if (element !== undefined) {
                        switch (msExportUsersExcel.export.area) {
                            case 'modx-form-users':
                                baseParamsGrid = element.store.baseParams
                                if (element.store.sortInfo !== undefined) {
                                    baseParamsGrid.dir = element.store.sortInfo.direction
                                    baseParamsGrid.sort = element.store.sortInfo.field
                                }
                                break
                            default:
                                baseParamsGrid = element.grid.baseParams
                                break
                        }
                    }
                }
                var params = {
                    action: 'mgr/profile/export',
                    id: profile_id,
                    classExport: classExport,
                    register: register,
                    topic: topic,
                    remove_read: remove_read,
                    baseParams: JSON.stringify(baseParamsGrid),
                }

                MODx.Ajax.request({
                    url: msExportUsersExcel.config['connector_url']
                    , params: params
                    , listeners: {
                        'success': {
                            fn: function (response) {
                                var res = response.object
                                if (res.total !== 0 && res.download) {
                                    document.location = res.download_link
                                }
                            }, scope: this
                        },
                        'failure': {
                            fn: function (response) {
                                  //console.fireEvent('complete');
                            }, scope: this
                        },
                    }
                })

            }
        }

        Ext.MessageBox.show({
            title: _('msexportusersexcel_profile_export'),
            msg: _('msexportusersexcel_profile_export_confirm'),
            width: 300,
            buttons: Ext.MessageBox.YESNO,
            buttonText: {ok: 'OK', cancel: 'Cancel', yes: _('msexportusersexcel_yes'), no: _('msexportusersexcel_no')},
            fn: exportCallback,
            icon: Ext.MessageBox.QUESTION
        })
        return true
    },
    buttons: function (list, profile_id) {
        var classExportList = []
        if (list.length) {
            for (var i = 0; i < list.length; i++) {
                var name = list[i]
                classExportList[i] = {
                    text: _('msexportusersexcel_profile_export_' + name),
                    profile_id: profile_id,
                    classExport: name,
                    handler: function (btn) {
                        msExportUsersExcel.export.process(btn.initialConfig.classExport, btn.initialConfig.profile_id)
                    },
                }
            }
        }
        return new Ext.Button({
            text: '<i class="icon icon-download"></i> '+_('msexportusersexcel_profile_export_btn'),
            cls: 'x-btn-text bmenu',
            style: 'marginRight: 5px',
            menuAlign: 'tr-br',
            menu: {
                id: 'msexportusersexcel-x-menu',
                items: classExportList
            },
            handler: function () {return false}
        })
    }
}

/** *********************************************** **/
Ext.onReady(function () {

    function regButtonsExport (area) {
        // Получения элемента для регистрации кнопок

        var element = Ext.getCmp(area)
        if (element !== undefined) {

            // определение места расположения кнопок
            switch (msExportUsersExcel.config.namespace) {
                case 'minishop2':

                    switch (msExportUsersExcel.config.controller) {
                        case 'controllers/mgr/orders':
                            element = element.getTopToolbar()
                            break
                        case 'mgr/orders':
                            break
                        default:
                            break
                    }

                    break
                case 'core':

                    switch (msExportUsersExcel.config.controller) {
                        case 'security/user':
                            element = element.getTopToolbar()
                            break
                        default:
                            break
                    }

                    break
                default:
                    break
            }

            msExportUsersExcel.export.area = area
            var buttons = msExportUsersExcel.export.buttons(msExportUsersExcel.config.list, msExportUsersExcel.config.profile)
            element.addButton(buttons)
            element.doLayout()
        }
    }

    regButtonsExport(msExportUsersExcel.config.area)
})
