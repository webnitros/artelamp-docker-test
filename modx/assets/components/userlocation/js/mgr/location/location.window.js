userlocation.window.ImportLocation = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        title: _('userlocation_action_import'),
        autoHeight: true,
        url: userlocation.config.connector_url,
        baseParams: {
            action: 'mgr/location/import',
        },
        fileUpload: true,
        fields: this.getFields(config),
        keys: this.getKeys(config),
        buttons: this.getButtons(config)
    });
    userlocation.window.ImportLocation.superclass.constructor.call(this, config);
};
Ext.extend(userlocation.window.ImportLocation, MODx.Window, {
    getKeys: function (config) {
        return [{
            key: Ext.EventObject.ENTER,
            shift: true,
            fn: this.submit,
            scope: this
        }];
    },

    getButtons: function (config) {
        return [{
            text: _('userlocation_action_import'),
            scope: this,
            handler: function () {
                this.submit();
            }
        }, /*{
            text: config.cancelBtnText || _('cancel'),
            scope: this,
            handler: function () {
                config.closeAction !== 'close'
                    ? this.hide()
                    : this.close();
            }
        }*/];
    },
    getFields: function (config) {
        return [{
            layout: 'column',
            border: false,
            defaults: {
                layout: 'form',
                labelAlign: 'top',
                border: false,
                cls: '',
                labelSeparator: ''
            },
            items: [{
                columnWidth: 1,
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('userlocation_csv_terminated'),
                    name: 'csv_terminated',
                    anchor: '100%',
                    allowBlank: false
                }, {
                    xtype: 'textfield',
                    fieldLabel: _('userlocation_csv_enclosed'),
                    name: 'csv_enclosed',
                    anchor: '100%',
                    allowBlank: false
                }, {
                    xtype: 'textfield',
                    fieldLabel: _('userlocation_csv_escaped'),
                    name: 'csv_escaped',
                    anchor: '100%',
                    allowBlank: false
                }, {
                    xtype: 'textfield',
                    fieldLabel: _('userlocation_csv_ignore_lines'),
                    name: 'csv_ignore_lines',
                    anchor: '100%',
                    allowBlank: false
                }, {
                    xtype: 'modx-combo',
                    store: new Ext.data.SimpleStore({
                        fields: ['d', 'v'],
                        data: [['replace', 'replace'], ['ignore', 'ignore']]
                    }),
                    displayField: 'd',
                    valueField: 'v',
                    mode: 'local',
                    triggerAction: 'all',
                    editable: false,
                    selectOnFocus: false,
                    preventRender: true,
                    forceSelection: true,
                    enableKeyEvents: true,

                    fieldLabel: _('userlocation_load_method'),
                    name: 'load_method',
                    hiddenNamed: 'load_method',
                    anchor: '100%',
                    allowBlank: false
                    //method
                }, {
                    xtype: 'userlocation-combo-boolean',
                    fieldLabel: _('userlocation_load_truncate'),
                    name: 'load_truncate',
                    anchor: '100%',
                    allowBlank: false
                }, {
                    xtype: 'fileuploadfield',
                    inputType: 'text',
                    fieldLabel: _('userlocation_file'),
                    buttonText: '<i class="icon icon-upload"></i>',
                    name: 'file',
                    anchor: '100%',
                    allowBlank: false,
                }]
            }]
        }]
    },

});
Ext.reg('userlocation-window-import-location', userlocation.window.ImportLocation);


userlocation.window.ExportLocation = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        title: _('userlocation_action_export'),
        autoHeight: true,
        url: userlocation.config.connector_url,
        baseParams: {
            action: 'mgr/location/export',
        },
        fileUpload: true,
        fields: this.getFields(config),
        keys: this.getKeys(config),
        buttons: this.getButtons(config)
    });
    userlocation.window.ExportLocation.superclass.constructor.call(this, config);
};
Ext.extend(userlocation.window.ExportLocation, MODx.Window, {
    getKeys: function (config) {
        return [{
            key: Ext.EventObject.ENTER,
            shift: true,
            fn: this.submit,
            scope: this
        }];
    },

    getButtons: function (config) {
        return [{
            text: _('userlocation_action_export'),
            scope: this,
            handler: function () {
                var form = this.fp.getForm();
                var fields = form ? form.getValues() : {};
                var url = userlocation.config.connector_url+'?action=mgr/location/export&HTTP_MODAUTH='+MODx.siteId;
                for (var i in fields) {
                    url+='&'+i+'='+fields[i];
                }
                window.open(url);
                return false;

                this.submit();
            }
        }, /*{
            text: config.cancelBtnText || _('cancel'),
            scope: this,
            handler: function () {
                config.closeAction !== 'close'
                    ? this.hide()
                    : this.close();
            }
        }*/];
    },
    getFields: function (config) {
        return [{
            layout: 'column',
            border: false,
            defaults: {
                layout: 'form',
                labelAlign: 'top',
                border: false,
                cls: '',
                labelSeparator: ''
            },
            items: [{
                columnWidth: 1,
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('userlocation_csv_terminated'),
                    name: 'csv_terminated',
                    anchor: '100%',
                    allowBlank: false
                }, {
                    xtype: 'textfield',
                    fieldLabel: _('userlocation_csv_enclosed'),
                    name: 'csv_enclosed',
                    anchor: '100%',
                    allowBlank: false
                }, {
                    xtype: 'textfield',
                    fieldLabel: _('userlocation_csv_escaped'),
                    name: 'csv_escaped',
                    anchor: '100%',
                    allowBlank: false
                }, /*{
                    xtype: 'textfield',
                    fieldLabel: _('userlocation_csv_ignore_lines'),
                    name: 'csv_ignore_lines',
                    anchor: '100%',
                    allowBlank: false
                },*/]
            }]
        }]
    },

});
Ext.reg('userlocation-window-export-location', userlocation.window.ExportLocation);



userlocation.window.UpdateLocation = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        title: _('create'),
        width: 600,
        autoHeight: true,
        fields: this.getFields(config),
        action: 'mgr/location/create',
        url: userlocation.config['connector_url'],
        bodyCssClass: 'userlocation-window',
    });
    userlocation.window.UpdateLocation.superclass.constructor.call(this, config);

    this.on('afterrender', function () {
        if (userlocation.config.window_location_fields) {
            Ext.each(this.fp.getForm().items.items, function (t) {
                if (!t.name || t.name === "pk" || t.name === "description") {
                    return true;
                }

                if (
                    userlocation.config.window_location_fields.indexOf(t.name) >= 0 ||
                    userlocation.config.window_location_fields.indexOf(t.name.replace(/(_)/, "")) >= 0
                ) {
                    return true;
                }
                else {
                    t.disable().hide();
                }
            });
        }
    });

};
Ext.extend(userlocation.window.UpdateLocation, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'pk',
            value: config.record.id || '',
            setValue: function (value) {
                value = value ? value : this.initialConfig.value || '';
                return Ext.form.TextField.superclass.setValue.call(this, value);
            },
        },{
            xtype: 'userlocation-field-textfield-default',
            name: 'name',
            fieldLabel: _('userlocation_name'),
            anchor: '100%',
            allowBlank: false,
        }, {
            layout: 'column',
            border: false,
            items: [{
                columnWidth: 0.5,
                layout: 'form',
                defaults: {border: false, anchor: '100%'},
                items: [{
                    xtype: 'userlocation-field-textfield-default',
                    name: 'id',
                    fieldLabel: _('userlocation_id'),
                    anchor: '100%',
                    allowBlank: false
                }, {
                    xtype: 'userlocation-combo-location',
                    fieldLabel: _('userlocation_parent'),
                    name: 'parent',
                    novalue: true,
                    anchor: '100%',
                    allowBlank: true
                }]
            }, {
                columnWidth: 0.5,
                layout: 'form',
                defaults: {border: false, anchor: '100%'},
                items: [ {
                    xtype: 'userlocation-field-textfield-default',
                    name: 'type',
                    fieldLabel: _('userlocation_type'),
                    anchor: '100%',
                    allowBlank: false
                }, {
                    xtype: 'userlocation-combo-resource',
                    fieldLabel: _('userlocation_resource'),
                    name: 'resource',
                    novalue: true,
                    anchor: '100%',
                    allowBlank: true
                }]
            }]
        }, {

            layout: 'column',
            border: false,
            items: [{
                columnWidth: 0.5,
                layout: 'form',
                defaults: {border: false, anchor: '100%'},
                items: [{
                    xtype: 'userlocation-field-textfield-default',
                    name: 'okato',
                    fieldLabel: _('userlocation_okato'),
                    anchor: '100%',
                    allowBlank: true
                },{
                    xtype: 'userlocation-field-textfield-default',
                    name: 'gninmb',
                    fieldLabel: _('userlocation_gninmb'),
                    anchor: '100%',
                    allowBlank: true
                },{
                    xtype: 'userlocation-field-textfield-default',
                    name: 'postal',
                    fieldLabel: _('userlocation_postal'),
                    anchor: '100%',
                    allowBlank: true
                }]
            }, {
                columnWidth: 0.5,
                layout: 'form',
                defaults: {border: false, anchor: '100%'},
                items: [{
                    xtype: 'userlocation-field-textfield-default',
                    name: 'oktmo',
                    fieldLabel: _('userlocation_oktmo'),
                    anchor: '100%',
                    allowBlank: true
                },{
                    xtype: 'userlocation-field-textfield-default',
                    name: 'fias',
                    fieldLabel: _('userlocation_fias'),
                    anchor: '100%',
                    allowBlank: true
                }]
            }]
        }, {
            xtype: 'textarea',
            fieldLabel: _('userlocation_properties'),
            msgTarget: 'under',
            name: 'properties',
            anchor: '100%',
            height: 50,
            allowBlank: true,
            setValue: function (value) {

                if (value && value instanceof Object) {
                    value = Ext.util.JSON.encode(value);
                }
                return Ext.form.TextField.superclass.setValue.call(this, value);
            },
        }, {
            xtype: 'textarea',
            fieldLabel: _('userlocation_description'),
            msgTarget: 'under',
            name: 'description',
            anchor: '100%',
            height: 50,
            allowBlank: true
        }, {
            xtype: 'checkboxgroup',
            hideLabel: true,
            columns: 3,
            items: [{
                xtype: 'xcheckbox',
                boxLabel: _('userlocation_active'),
                name: 'active',
                checked: parseInt(config.record.active)
            }]
        }];
    }

});
Ext.reg('userlocation-window-update-location', userlocation.window.UpdateLocation);
