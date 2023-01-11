// TvImageHtml
mspre.window.TvImageHtml = function (config) {
    config = config || {}

    var formPanel = {
        xtype: 'form',
        autoHeight: true,
        autoScroll: true,
        id: 'modx-panel-resource',
        defaultType: 'field',
        cls: 'mspre-panel-resource-tv',
        frame: true,
        html: config.outputHtml,
        items: [
            {
                xtype: 'displayfield',
                name: config.tvname,
                value: config.tvname,
                allowBlank: false,
                fieldLabel: _('mspre_field'),
                anchor: '90%',
                cls: 'mspre-panel-resource-tv-tvname',
            }
        ],
        listeners: {
            specialkey: {
                fn: function (field, e) {
                    if (e.getKey() === e.ENTER) {
                        return false
                    }
                }, scope: this
            },
            'render': function (cmp) {
                cmp.getEl().on('keypress', function (e) {
                    if (e.getKey() === e.ENTER) {
                        return false
                    }
                })
            }
        }
    }
    Ext.applyIf(config, {
        title: _('mspre_combo_tv_title_update'),
        id: 'mspre-window-tv-image-html',
        cls: 'mspre-panel-resource-tv',
        renderTo: Ext.getBody(),
        autoHeight: true,
        height: 250,
        width: 450,
        maxWidth: 450,
        maxheight: 450,
        url: mspre.config.connector_url,
        baseParams: {
            action: 'mgr/common/tv/update',
        },
        items: [formPanel],
        fields: [
            {
                xtype: 'hidden',
                name: 'id',
                allowBlank: false,
                value: config.resource,
            },
            {
                xtype: 'hidden',
                name: 'field_name',
                allowBlank: false,
                value: config.tvname,
            },
            {
                xtype: 'hidden',
                name: 'field_value',
                allowBlank: false
            }
        ],
        keys: [
            {
                key: Ext.EventObject.ENTER
                , shift: true
                , fn: function () {
                this.submit()
            }
                , scope: this
            }
        ]
    })

    config.listeners.beforeSubmit = {
        fn: function () {
            var form = Ext.getCmp('modx-panel-resource')

            if (!form.form.isValid()) {
                return false
            }

            var values = []
            this.setValues({
                'field_value': Ext.util.JSON.encode(form.form.getValues())
            })
            return true
        }, scope: this
    }

    mspre.window.TvImageHtml.superclass.constructor.call(this, config)
}
Ext.extend(mspre.window.TvImageHtml, MODx.Window, {
    enter: function (config) {

    },
})
Ext.reg('mspre-window-tv-image-html', mspre.window.TvImageHtml)


// TV Change field
mspre.window.TvChangeTemplate = function (config) {
    this.grid = Ext.getCmp('mspre-grid-product')
    config = config || {}


    Ext.applyIf(config, {
        title: _('mspre_combo_change_template_title'),
        url: mspre.config.connector_url,
        id: 'mspre-window-change-template-modal',
        width: 400,
        maxHeight: 250,
        autoHeight: true,
        buttons: [
            {
                text: config.cancelBtnText || _('cancel')
                , scope: this
                , handler: function () {
                config.closeAction !== 'close' ? this.hide() : this.close()
            }
            }, {
                text: config.saveBtnText || _('mspre_combo_tv_field_change')
                , cls: 'primary-button'
                , scope: this
                , handler: this.change
            }
        ],
        fields: [
            {
                xtype: 'hidden',
                name: 'ids'
            },
            {
                xtype: 'mspre-combo-binding-template',
                id: 'mspre-window-binding-tv-template',
                fieldLabel: _('mspre_combo_change_template_label'),
                name: 'field_name',
                allowBlank: false,
                hiddenName: 'field_name',
                ids: config.record.ids,
                anchor: '90%',
                listeners: {
                    select: {
                        fn: function (field, record) {
                            var list = this.fieldBox()
                            list.show(false).setWidth(340).clearValue()
                            list.store.reload({
                                params: {
                                    template: record.data.id
                                }
                            })
                            list.baseParams.template = record.data.id
                        },
                        scope: this
                    }
                }
            },
            {
                xtype: 'xcheckbox',
                fieldLabel: _('mspre_combo_tv_show_all'),
                description: _('mspre_combo_tv_show_all_desc'),
                id: 'mspre-window-options-show_all',
                name: 'show_all',
                allowBlank: false,
                hiddenName: 'show_all',
                anchor: '90%',
                checked: false,
                listeners: {
                    check: {
                        fn: function (field) {
                            var checked = field.getValue()
                            var list = this.fieldBox()
                            list.store.reload({
                                params: {
                                    show_all: checked
                                }
                            })
                            list.baseParams.show_all = checked
                        },
                        scope: this
                    }
                }
            },
            {
                xtype: 'mspre-combo-tvfield',
                id: 'mspre-window-binding-tv-type-field',
                fieldLabel: _('mspre_combo_tv_field'),
                emptyText: _('mspre_combo_tv_field_emptytext'),
                name: 'field_name',
                hidden: true,
                allowBlank: false,
                hiddenName: 'field_name',
                anchor: '90%'
            }
        ]
    })
    mspre.window.TvChangeTemplate.superclass.constructor.call(this, config)
}
Ext.extend(mspre.window.TvChangeTemplate, MODx.Window, {
    fieldBox: function (key, param) {
        var list = Ext.getCmp('mspre-window-binding-tv-type-field')
        list.clearValue()
        return list
    },
    change: function (config) {

        var tvname = Ext.getCmp('mspre-window-binding-tv-type-field').value
        var template = Ext.getCmp('mspre-window-binding-tv-template').value
        mspre.grid.product.accessTemplate({
            tvname: 'tv-' + tvname,
            template: template
        }, function (r) {
            if (r.success || r.a.result) {
                Ext.getCmp('mspre-window-change-template-modal').loadWindows()
            } else {
                MODx.msg.alert(_('error'), _('mspre_error_options_failed_to_tie'))
            }
        })
    },
    loadWindows: function (config) {

        var window = Ext.getCmp('mspre-window-change-template-modal')
        var tvname = Ext.getCmp('mspre-window-binding-tv-type-field').getValue()
        var template = Ext.getCmp('mspre-window-binding-tv-template').getValue()

        this.grid.request('mgr/common/tv/gettv', {
            controller: mspre.config.controller,
            mode: window.mode,
            tvname: tvname,
            template: template
        }, function (response) {
            if (response.success) {

                if (mspre.grid.product.windowsTv.massActions) {
                    mspre.grid.product.windowsTv.massActions.destroy()
                }

                var grid = Ext.getCmp('mspre-grid-product')
                mspre.grid.product.windowsTv.massActions = MODx.load({
                    xtype: 'mspre-window-tv-mass-actions',
                    params: response.object.params,
                    mode: window.mode,
                    tvname: tvname,
                    template: template,
                    ids: Ext.util.JSON.encode(grid._getSelectedIds()),
                    listeners: {
                        'success': {
                            fn: function (r) {
                                grid.refresh()
                            }, scope: this
                        },
                        afterrender: {
                            fn: function () {
                                grid.enabledMask()
                            },
                            scope: this
                        },
                        hide: {
                            fn: function () {
                                mspre.grid.product.windowsTv.massActions.destroy()
                                grid.disabledMask()
                            },
                            scope: this
                        }
                    }
                })
                mspre.grid.product.windowsTv.massActions.show(grid.target)
                Ext.getCmp('mspre-window-change-template-modal').destroy()
            } else {
                MODx.msg.alert(_('error'), response.message)
            }
        })

    },
})
Ext.reg('mspre-window-tv-change-template', mspre.window.TvChangeTemplate)

// TV Change Combo
mspre.window.TvChangeCombo = function (config) {
    this.grid = Ext.getCmp('mspre-grid-product')
    config = config || {}

    Ext.applyIf(config, {
        title: _('mspre_combo_change_tv_combo_title'),
        url: mspre.config.connector_url,
        id: 'mspre-window-change-combo-modal',
        width: 400,
        maxHeight: 250,
        fields: [
            {
                xtype: 'modx-button',
                cls: 'mspre-btn-action-tv',
                iconCls: 'icon-plus',
                text: _('mspre_combo_change_combo_add'),
                anchor: '99%',
                listeners: {
                    click: {
                        fn: function () {
                            this.destroy()
                            Ext.getCmp('mspre-grid-product').autoStartTvCombo('add', config.records)
                        },
                        scope: this
                    }
                }
            },
            {
                xtype: 'modx-button',
                cls: 'mspre-btn-action-tv',
                iconCls: 'icon-edit',
                text: _('mspre_combo_change_combo_replace'),
                anchor: '99%',
                listeners: {
                    click: {
                        fn: function () {
                            this.destroy()
                            Ext.getCmp('mspre-grid-product').autoStartTvCombo('replace', config.records)
                        },
                        scope: this
                    }
                }
            },
            {
                xtype: 'modx-button',
                cls: 'mspre-btn-action-tv',
                iconCls: 'icon-trash-o',
                text: _('mspre_combo_change_combo_remove'),
                anchor: '99%',
                listeners: {
                    click: {
                        fn: function () {
                            this.destroy()
                            Ext.getCmp('mspre-grid-product').autoStartTvCombo('remove', config.records)
                        },
                        scope: this
                    }
                }
            },
            {
                xtype: 'xcheckbox',
                fieldLabel: _('mspre_combo_tv_offset'),
                description: _('mspre_combo_tv_offset_desc'),
                id: 'mspre-window-add-tv-field-field_offset',
                name: 'offset_resource',
                allowBlank: false,
                hiddenName: 'offset_resource',
                anchor: '90%',
                checked: false,
            },
            {
                xtype: 'xcheckbox',
                fieldLabel: _('mspre_combo_tv_enforce'),
                description: _('mspre_combo_tv_enforce_desc'),
                id: 'mspre-window-add-tv-field-field_enforce',
                name: 'enforce',
                allowBlank: false,
                hiddenName: 'enforce',
                anchor: '90%',
                checked: false,
            }

        ],
        listeners: {
            'failure': {
                fn: function (r) {
                    MODx.msg.alert(_('mspre_error'), response.message)
                }, scope: this
            }
        }
    })

    mspre.window.TvChangeCombo.superclass.constructor.call(this, config)
}
Ext.extend(mspre.window.TvChangeCombo, MODx.Window)
Ext.reg('mspre-window-tv-change-combo', mspre.window.TvChangeCombo)

// Binding
mspre.window.TemplateBinding = function (config) {

    config.saveBtnText = _('mspre_templatebinding_btn_save')
    var record = config.object
    config = config || {}
    Ext.applyIf(config, {
        title: _('mspre_combo_tv_template_binding_title'),
        url: MODx.config.connector_url,
        baseParams: {
            action: 'element/tv/update',
        },
        id: 'mspre-window-tv-template-binding',
        width: 400,
        maxHeight: 250,
        autoHeight: true,
        fields: [
            {
                xtype: 'hidden',
                name: 'id',
                value: config.object.tv_id
            },
            {
                xtype: 'hidden',
                name: 'name',
                value: config.object.tv_name
            },
            {
                xtype: 'hidden',
                name: 'templates',
                value: config.object.templates
            },
            {
                xtype: 'modx-description',
                html: _('mspre_combo_tv_template_binding_desc'),
                style: 'margin: 10px 0;'
            },
            {
                xtype: 'displayfield',
                fieldLabel: _('mspre_template_name'),
                anchor: '90%',
                value: '<a href="index.php?a=element/template/update&id=' + record.template_id + '" target="_blank">' + record.template_name + '</a> (' + _('id') + ' ' + record.template_id + ')',
                //value: config.object.template_name + ' (' + _('id') + ' ' + config.object.template_id + ')',
            },
            {
                xtype: 'displayfield',
                fieldLabel: _('mspre_tv_name'),
                anchor: '90%',
                value: config.object.tv_name + ' (' + _('id') + ' ' + config.object.tv_id + ')',
            },
            {
                xtype: 'displayfield',
                fieldLabel: _('name'),
                anchor: '90%',
                value: config.object.tv_caption,
            }
        ]
    })
    mspre.window.TemplateBinding.superclass.constructor.call(this, config)
}
Ext.extend(mspre.window.TemplateBinding, MODx.Window)
Ext.reg('mspre-window-tv-template-binding', mspre.window.TemplateBinding)

mspre.window.TvMassActionsOperations = function (config) {
    var params = config.params
    var fields = params.fields



    fields.push({
        xtype: 'hidden',
        name: 'ids',
        value: config.ids
    })

    // fields
    config = config || {}
    Ext.applyIf(config, {
        title: params.title,
        id: 'mspre-window-tv-mass-actions-modal',
        url: mspre.config.connector_url,
        baseParams: {
            action: mspre.config.controllerPath + 'multiple',
            method: 'settv',
            mode: config.mode,
            field_name: config.tvname,
            template: config.template,
        },
        width: 400,
        autoHeight: true,
        fields: fields
    })

    mspre.window.TvMassActionsOperations.superclass.constructor.call(this, config)
}
Ext.extend(mspre.window.TvMassActionsOperations, mspre.window.DefaultComboExt)
Ext.reg('mspre-window-tv-mass-actions', mspre.window.TvMassActionsOperations)

// Autocomplete
mspre.combo.AutocompleteTv = function (config) {
    config = config || {}
    var modal = Ext.getCmp('mspre-window-tv-mass-actions-modal')
    config.name = 'name'
    Ext.applyIf(config, {
        name: config.name,
        fieldLabel: _('mspre_header_' + config.name),
        id: 'mspre-product-' + config.name,
        hiddenName: config.name,
        displayField: config.name,
        valueField: 'id',
        anchor: '99%',
        fields: ['id', config.name],
        //pageSize: 20,
        forceSelection: false,
        url: mspre.config['connector_url'],
        typeAhead: true,
        editable: false,
        allowBlank: true,
        baseParams: {
            action: 'mgr/misc/tv/autocomplete',
            template: modal.template,
            mode:modal.mode,
            ids: modal.ids,
            field: modal.tvname,
            whatValues: config.whatValues,
            combo: true,
            limit: 0
        },
        hideTrigger: false,
    })
    mspre.combo.AutocompleteTv.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.AutocompleteTv, MODx.combo.ComboBox)
Ext.reg('mspre-combo-autocomplete-tv', mspre.combo.AutocompleteTv)

// Template
mspre.combo.Template = function (config) {
    config = config || {}

    if (config.custm) {
        config = getDefaultConfig(config)
    }
    Ext.applyIf(config, {
        name: config.name || 'template',
        hiddenName: 'template',
        displayField: 'templatename',
        allowBlank: true,
        editable: true,
        valueField: 'id',
        fields: ['id', 'templatename','count'],
        emptyText: _('mspre_combo_template_select'),
        url: mspre.config.connector_url,
        tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item">',
            '<small>({id})</small> <b>{templatename}</b> <br><small>' + _('mspre_selected_count_resource') + ' {count}</small></span>',
            '</div></tpl>', {
                compiled: true
            }),
        baseParams: {
            action: 'mgr/misc/template/getlist',
            combo: true,
            ids: config.ids || ''
        }
    })
    mspre.combo.Template.superclass.constructor.call(this, config)
}
Ext.extend(mspre.combo.Template, MODx.combo.ComboBox)
Ext.reg('mspre-combo-binding-template', mspre.combo.Template)




mspre.combo.TvField = function (config) {
    config = config || {}
    if (config.custm) {
        config = getDefaultConfig(config)
    }
    Ext.applyIf(config, {
        id: 'mspre-combo-tvfield',
        name: config.name || 'name',
        hiddenName: 'name',
        displayField: 'name',
        allowBlank: false,


        mode: 'remote',
        forceSelection: true,
        editable: true,
        enableKeyEvents: true,


        valueField: 'name',
        fields: ['caption', 'name'],
        emptyText: _('mspre_combo_select_tv_field'),
        url: mspre.config.connector_url,
        tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item">',
            '<small>({name})</small> <b>{caption}</b></span>',
            '</div></tpl>', {
                compiled: true
            }),
        baseParams: {
            action: 'mgr/misc/tv/getlist',
            combo: true,
            template: 0,
            show_all: false
        }
    })
    mspre.combo.TvField.superclass.constructor.call(this, config)

}
Ext.extend(mspre.combo.TvField, MODx.combo.ComboBox)
Ext.reg('mspre-combo-tvfield', mspre.combo.TvField)