Ext.onReady(function () {
    var recordCount = 0 // Счетчик записей
    var listRecords = {}  // Массив с записями

    function cyclicQuery (config) {

        var total = listRecords.length

        var ix, j, temparray, chunk = mspre.config.max_records_processed
        for (ix = 0, j = listRecords.length; ix < j; ix += chunk) {
            if (!listRecords.hasOwnProperty(ix)) {
                continue
            }

            if (ix === recordCount) {
                temparray = listRecords.slice(ix, ix + chunk)
                mspre.progress.updateText(_('mspre_treated_resources') + ' ' + recordCount + ' ' + _('mspre_from') + ' ' + total + '')
                recordCount = recordCount + temparray.length
                config.params.ids = [Ext.util.JSON.encode(temparray)]
                MODx.Ajax.request(config, cyclicQuery)
                return true
            }
        }

        /*
            Одиночная отправка
        for (var i = 0; i < listRecords.length; i++) {
            if (!listRecords.hasOwnProperty(i)) {
                continue
            }
            if (i === recordCount) {
                var id = listRecords[i]
                mspre.progress.updateText('ID ' + id)
                recordCount++
                config.params.ids = [Ext.util.JSON.encode([id])]
                MODx.Ajax.request(config, cyclicQuery)
                return true
            }
        }
        */
        if (mspre.progress) {
            mspre.progress.hide()
        }
        mspre.disableRefresh = false
    }

    function runProcessingMspre (ids,config) {
        mspre.progress = Ext.MessageBox.wait('', _('please_wait'))
        recordCount = 0
        listRecords = Ext.util.JSON.decode(ids)
        mspre.disableRefresh = true
        config.offsetCyclic = true
        cyclicQuery(config)
    }

    MODx.Ajax.request = function (config, callback) {

        if (!config.offsetCyclic) {

            if (config.params.progress) {

                // Режим эксперт
                if (mspre.config.mode_expert) {
                    var total_all = Ext.get('mspre-panel-info-total_info').dom.innerText;
                    var total_selected = Ext.util.JSON.decode(config.params.ids).length;
                    Ext.MessageBox.show({
                        title: _('warning'),
                        msg: _('mspre_expert_mode_confirm') + mspre.config.max_records_processed_all,
                        width: 500,
                        buttons: {
                            yes: _('mspre_process_btn_yes') + ' (' + _('mspre_process_total') + ' ' + total_selected + ')',
                            no: _('mspre_process_btn_no') + ' (' + _('mspre_process_all_total') + ' ' + total_all + ')',
                            cancel: _('mspre_process_btn_cancel'),
                        },
                        fn: function (e) {

                            // Обработает только выбранные записи со страницы
                            if (e == 'yes') {
                                runProcessingMspre(config.params.ids, config)
                                return false;
                            }

                            // Будут обрабатываться все найденные ресрурсы с учетом фильтров
                            if (e == 'no') {

                                var grid = Ext.getCmp(mspre.config.grid_id)
                                var baseParamsCyclic = {cyclic: true, limit: mspre.config.max_records_processed_all}
                                for (var keyParam in grid.baseParams) {
                                    if (grid.baseParams.hasOwnProperty(keyParam)) {
                                        baseParamsCyclic[keyParam] = grid.baseParams[keyParam];
                                    }
                                }

                                MODx.Ajax.request({
                                    url: mspre.config.connector_url,
                                    params: baseParamsCyclic,
                                    listeners: {
                                        success: {
                                            fn: function (r) {
                                                if (r.success && r.total !== 0) {
                                                    runProcessingMspre(Ext.util.JSON.encode(r.results), config)
                                                    return false;
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
                        },
                        icon: Ext.MessageBox.QUESTION
                    });
                } else {
                    runProcessingMspre(config.params.ids, config)
                }
                return false
            }
        }
        Ext.apply(config, {
            success: function (r, o) {
                r = Ext.decode(r.responseText)
                if (!r) {
                    return false
                }
                r.options = o
                if (r.success) {

                    if (typeof callback === 'function') {
                        callback(config)
                    }

                    if (config.listeners.success && config.listeners.success.fn) {
                        this._runCallback(config.listeners.success, [r])
                    }
                } else if (config.listeners.failure && config.listeners.failure.fn) {
                    this._runCallback(config.listeners.failure, [r])
                    MODx.form.Handler.errorJSON(r)
                }
                return true
            }
            , failure: function (r, o) {
                r = Ext.decode(r.responseText)
                if (!r) {
                    return false
                }
                r.options = o
                if (config.listeners.failure && config.listeners.failure.fn) {
                    this._runCallback(config.listeners.failure, [r])
                    MODx.form.Handler.errorJSON(r)
                }
                return true
            }
            , scope: this
            , headers: {
                'Powered-By': 'MODx'
                , 'modAuth': config.auth
            }
        })
        Ext.Ajax.request(config)
    }
})