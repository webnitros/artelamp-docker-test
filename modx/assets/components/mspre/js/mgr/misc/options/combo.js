// Options Change SuperBoxCombo
mspre.window.OptionsChangeSuperBoxCombo = function (config) {
    this.grid = Ext.getCmp('mspre-grid-product')
    config = config || {}

    var record = config.record
    var ids = record.ids
    var field = record.field
    var ext_field = Ext.util.JSON.decode(record.ext_field)
    delete record.ext_field


    Ext.applyIf(config, {
        title: _('mspre_combo_superboxcombo_title'),
        url: mspre.config.connector_url,
        id: 'mspre-window-options-superboxcombo',
        width: 500,
        autoHeight: true,
        baseParams: {
            action: mspre.config.controllerPath + 'options/json/insert',
        },
        fields: [
            {
                xtype: 'hidden',
                name: 'field',
                allowBlank: false,
                value: field,
            },
            {
                xtype: 'hidden',
                name: 'ids',
                allowBlank: false,
                value: ids,
            },
            {
                xtype: 'hidden',
                name: 'complete_replacement',
                allowBlank: false,
                value: true,
            },
            {
                xtype: 'displayfield',
                fieldLabel: _('mspre_field'),
                hiddenName: 'showname',
                name: field,
                value: field,
                anchor: '90%',
            }, Ext.applyIf(record, ext_field)
        ],
        listeners: {
            'failure': {
                fn: function (r) {
                    MODx.msg.alert(_('mspre_error'), response.message)
                }, scope: this
            }
        }
    })

    mspre.window.OptionsChangeSuperBoxCombo.superclass.constructor.call(this, config)
}
Ext.extend(mspre.window.OptionsChangeSuperBoxCombo, MODx.Window)
Ext.reg('mspre-window-options-superboxcombo', mspre.window.OptionsChangeSuperBoxCombo)

// replace Options field
mspre.window.CategoryBinding = function (config) {

    config.saveBtnText = _('mspre_categorybinding_btn_save')

    var record = config.object
    var params = Ext.util.JSON.decode(record.options)

    config = config || {}
    Ext.applyIf(config, {
        title: _('mspre_combo_options_category_title'),
        url: mspre.config['connectorUrlMinishop'],
        baseParams: params,
        id: 'mspre-window-options-category-binding',
        width: 400,
        maxHeight: 250,
        autoHeight: true,
        fields: [
            {
                xtype: 'modx-description',
                html: _('mspre_combo_options_category_binding_desc'),
                style: 'margin: 10px 0;'
            },
            {
                xtype: 'displayfield',
                fieldLabel: _('mspre_category_name'),
                anchor: '90%',
                value: '<a href="index.php?a=resource/update&id=' + record.category_id + '" target="_blank">' + record.category_name + '</a> (' + _('id') + ' ' + record.category_id + ')',
            },
            {
                xtype: 'displayfield',
                fieldLabel: _('mspre_option_key'),
                anchor: '90%',
                value: record.option_key + ' (' + _('id') + ' ' + record.category_id + ')',
            },
            {
                xtype: 'displayfield',
                fieldLabel: _('mspre_option_name'),
                anchor: '90%',
                value: record.option_caption
            }
        ]
    })
    mspre.window.CategoryBinding.superclass.constructor.call(this, config)
}
Ext.extend(mspre.window.CategoryBinding, MODx.Window)
Ext.reg('mspre-window-options-category-binding', mspre.window.CategoryBinding)

// Options Change field
mspre.window.OptionsChangeCategory = function (config) {
    this.grid = Ext.getCmp('mspre-grid-product')
    config = config || {}

    Ext.applyIf(config, {
        title: _('mspre_combo_change_category_title'),
        url: mspre.config.connector_url,
        id: 'mspre-window-change-category-modal',
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
                xtype: 'mspre-combo-category',
                ids: config.record.ids,
                id: 'mspre-window-options-category',
                fieldLabel: _('mspre_combo_change_category_label'),
                name: 'field_name',
                allowBlank: false,
                hiddenName: 'field_name',
                anchor: '90%',
                listeners: {
                    select: {
                        fn: function (field, record) {
                            var list = this.fieldBox()
                            list.show(false).setWidth(340).clearValue()
                            list.store.reload({
                                params: {
                                    category: record.data.id
                                }
                            })
                            list.baseParams.category = record.data.id
                        },
                        scope: this
                    }
                }
            },
            {
                xtype: 'xcheckbox',
                fieldLabel: _('mspre_combo_options_show_all'),
                description: _('mspre_combo_options_show_all_desc'),
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
                xtype: 'mspre-combo-option',
                id: 'mspre-window-options-type-field',
                fieldLabel: _('mspre_combo_options_field'),
                emptyText: _('mspre_combo_options_field_emptytext'),
                name: 'key',
                hidden: true,
                allowBlank: false,
                hiddenName: 'key',
                anchor: '90%',
            },
        ]
    })

    mspre.window.OptionsChangeCategory.superclass.constructor.call(this, config)
}
Ext.extend(mspre.window.OptionsChangeCategory, MODx.Window, {

    fieldBox: function (key, param) {
        var list = Ext.getCmp('mspre-window-options-type-field')
        list.clearValue()
        return list
    },
    change: function (config) {
        var option = Ext.getCmp('mspre-window-options-type-field').getValue()
        var category = Ext.getCmp('mspre-window-options-category').getValue()
        mspre.grid.product.accessCategory({
            option: 'options-' + option,
            category: category
        }, function (r) {
            if (r.success) {
                Ext.getCmp('mspre-window-change-category-modal').loadWindows()
            } else {
                MODx.msg.alert(_('error'), _('mspre_error_options_failed_to_tie'))
            }
        })
    },
    loadWindows: function (config) {

        var window = Ext.getCmp('mspre-window-change-category-modal')
        var option = Ext.getCmp('mspre-window-options-type-field').getValue()


        this.grid.request('mgr/controller/product/options/render', {mode: window.mode,option: option}, function (response) {
            if (response.success) {

                Ext.getCmp('mspre-grid-product').defaultCombo(response.object.params, this)
                Ext.getCmp('mspre-window-change-category-modal').destroy()
            } else {
                MODx.msg.alert(_('error'), response.message)
            }
        })

    },

})
Ext.reg('mspre-window-options-change-category', mspre.window.OptionsChangeCategory)