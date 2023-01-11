mspre.window.DefaultComboExt = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        title: '',
    })
    mspre.window.DefaultComboExt.superclass.constructor.call(this, config)
    this.on('hide', function () {
        var w = this
        window.setTimeout(function () {
            w.close()
        }, 200)
    })
}
Ext.extend(mspre.window.DefaultComboExt, MODx.Window, {
    progress: true,
    cyclicQuery: function ($this) {

        var total = mspre.listRecords.length

        var ix, j, temparray, chunk = mspre.config.max_records_processed
        for (ix = 0, j = mspre.listRecords.length; ix < j; ix += chunk) {
            if (!mspre.listRecords.hasOwnProperty(ix)) {
                continue
            }

            if (ix === mspre.recordCount) {
                temparray = mspre.listRecords.slice(ix, ix + chunk)
                mspre.message_wait = _('mspre_treated_resources') + ' ' + mspre.recordCount + ' ' + _('mspre_from') + ' ' + total + ''
                mspre.recordCount = mspre.recordCount + temparray.length

                mspre.formExt.setValues({
                    ids: [Ext.util.JSON.encode(temparray)]
                })

                if (mspre.listRecords.length !== mspre.recordCount) {
                    $this.submit($this.cyclicQuery)
                } else {
                    $this.submit()
                }
                return true
            }
        }

        /*
            Одиночная обработка
            for (var i = 0; i < mspre.listRecords.length; i++) {
                if (!mspre.listRecords.hasOwnProperty(i)) {
                    continue
                }

                if (i === mspre.recordCount) {
                    var id = mspre.listRecords[i]

                    //mspre.progress.updateText('ID ' + id)
                    mspre.recordCount++
                    mspre.message_wait = 'ID' + id

                    mspre.formExt.setValues({
                        ids: [Ext.util.JSON.encode([id])]
                    })

                    if (mspre.listRecords.length !== mspre.recordCount) {
                        $this.submit($this.cyclicQuery)
                    } else {
                        $this.submit()
                    }
                    return true
                }
            }
        */
    },
    isProgress: function ($this) {
        var $thisProgress = this;

        if (!mspre.offsetCyclic) {
            if (this.progress) {

                mspre.formExt = this.fp.getForm()
                var values = mspre.formExt.getValues()
                mspre.recordCount = 0
                mspre.listRecords = Ext.util.JSON.decode(values.ids)
                mspre.disableRefresh = true
                mspre.offsetCyclic = true

                // Режим эксперт
                if (mspre.config.mode_expert) {
                    var total_all = Ext.get('mspre-panel-info-total_info').dom.innerText
                    var total_selected = mspre.listRecords.length
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
                                $this.cyclicQuery($this)
                                return false
                            }

                

                            // Будут обрабатываться все найденные ресрурсы с учетом фильтров
                            if (e == 'no') {

                                var grid = Ext.getCmp(mspre.config.grid_id)
                                var baseParamsCyclic = {cyclic: true, limit: mspre.config.max_records_processed_all}
                                for (var keyParam in grid.baseParams) {
                                    if (grid.baseParams.hasOwnProperty(keyParam)) {
                                        baseParamsCyclic[keyParam] = grid.baseParams[keyParam]
                                    }
                                }


                                MODx.Ajax.request({
                                    url: mspre.config.connector_url,
                                    params: baseParamsCyclic,
                                    listeners: {
                                        success: {
                                            fn: function (r) {
                                                if (r.success && r.total !== 0) {
                                                    mspre.listRecords = r.results
                                                    //mspre.listRecords = Ext.util.JSON.encode(r.results)
                                                    $thisProgress.cyclicQuery($this)
                                                    return false
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
                    })
                } else {
                    this.cyclicQuery($this)
                }

                return true
            }
        }

        return false
    },
    submit: function (callback) {

        var $this = this

        close = close === false ? false : true
        var f = this.fp.getForm()
        if (f.isValid() && this.fireEvent('beforeSubmit', f.getValues())) {

            var elem = Ext.getCmp(this.id)
            if (!elem.isProgress($this)) {

                f.submit({
                    //waitMsg: _('saving')
                    waitMsg: mspre.message_wait
                    , submitEmptyText: this.config.submitEmptyText !== false
                    , scope: this
                    , failure: function (frm, a) {
                        if (this.fireEvent('failure', {f: frm, a: a})) {
                            MODx.form.Handler.errorExt(a.result, frm)
                        }
                        this.doLayout()
                    }
                    , success: function (frm, a) {

                        if (typeof callback === 'function') {
                            callback($this)
                        } else {
                            if (this.config.success) {
                                Ext.callback(this.config.success, this.config.scope || this, [frm, a])
                            }
                            this.fireEvent('success', {f: frm, a: a})
                            if (close) { this.config.closeAction !== 'close' ? this.hide() : this.close() }
                            this.doLayout()

                            // Сбрасываем
                            mspre.offsetCyclic = false
                            mspre.disableRefresh = false
                        }
                    }
                })

            }
        }
    }
})
Ext.reg('mspre-window-default-combo-ext', mspre.window.DefaultComboExt)