userlocation.grid.Location = function (config) {
    config = config || {};

    this.exp = new Ext.grid.RowExpander({
        expandOnDblClick: false,
        tpl: new Ext.Template('<p class="desc">{description}</p>'),
        renderer: function (v, p, record) {
            return record.data.description != '' && record.data.description != null ? '<div class="x-grid3-row-expander">&#160;</div>' : '&#160;';
        }
    });

    this.sm = new Ext.grid.CheckboxSelectionModel();

    Ext.applyIf(config, {
        url: userlocation.config.connector_url,
        baseParams: {
            action: 'mgr/location/getlist',
            //class: config.class || ''
        },
        save_action: 'mgr/location/updatefromgrid',
        autosave: true,
        save_callback: this._updateRow,
        fields: this.getAllFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        listeners: this.getListeners(config),

        sm: this.sm,
        plugins: this.exp,
        // ddGroup: 'dd',
        // enableDragDrop: true,

        autoHeight: true,
        paging: true,
        pageSize: 5,
        remoteSort: true,
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: 0
        },
        cls: 'userlocation-grid',
        bodyCssClass: 'grid-with-buttons',
        stateful: false,
        //stateId: 'userlocation-grid-location-state'

    });

    userlocation.grid.Location.superclass.constructor.call(this, config);

    /* color row */
    /* this.store.on('load', function (s, r, p) {
         this.getView().getRowClass = function (rec) {
             var cls = [];
             if (rec.json['private'] == '1') {
                 cls.push('grid-row-private');
             }
             // fix expander
             if (!!rec.json['description']) {
                 cls.push('x-grid3-row-collapsed');
             }

             return cls.join(' ');
         };
     }, this, {scope: this});

     this.on('render', function (s, r, p) {
         var grid = this;
         window.setTimeout(function () {
             grid.refresh();
         }, 1000);
     });
 */

    this.getStore().sortInfo = {
        field: 'name',
        direction: 'ASC'
    };
};
Ext.extend(userlocation.grid.Location, MODx.grid.Grid, {
    windows: {},

    getAllFields: function (config) {
        var fields = userlocation.config.grid_location_fields || [];

        return fields;
    },

    getTopBarComponent: function (config) {
        var component = ['menu', 'import', 'export', 'left', 'type', 'parent', 'search'];
        if (!!config.compact) {
            component = ['menu', 'import', 'export', 'left', 'type', 'parent', 'search'];
        }

        return component;
    },

    getTopBar: function (config) {
        var tbar = [];
        var add = {
            menu: {
                text: '<i class="icon icon-cogs"></i> ',
                menu: [{
                    text: '<i class="icon icon-plus"></i> ' + _('userlocation_action_create'),
                    cls: 'userlocation-cogs',
                    handler: this.create,
                    scope: this
                }, {
                    text: '<i class="icon icon-trash-o red"></i> ' + _('userlocation_action_remove'),
                    cls: 'userlocation-cogs',
                    handler: this.remove,
                    scope: this
                }, '-', {
                    text: '<i class="icon icon-toggle-on green"></i> ' + _('userlocation_action_active'),
                    cls: 'userlocation-cogs',
                    handler: this.active,
                    scope: this
                }, {
                    text: '<i class="icon icon-toggle-off red"></i> ' + _('userlocation_action_inactive'),
                    cls: 'userlocation-cogs',
                    handler: this.inactive,
                    scope: this
                }, '-', {
                    text: '<i class="icon icon-upload"></i> ' + _('userlocation_action_import'),
                    cls: 'userlocation-cogs',
                    handler: this.import,
                    scope: this
                }, {
                    text: '<i class="icon icon-download"></i> ' + _('userlocation_action_export'),
                    cls: 'userlocation-cogs',
                    handler: this.export,
                    scope: this
                }, '-', {
                    text: '<i class="icon icon-trash-o red"></i> ' + _('userlocation_action_truncate'),
                    cls: 'userlocation-cogs',
                    handler: this.truncate,
                    scope: this
                }]
            },
            /*import: {
                text: '<i class="icon icon-cloud-upload"></i>',
                tooltip: _('userlocation_action_import'),
                handler: this.import,
                scope: this
            },
            export: {
                text: '<i class="icon icon-download"></i>',
                tooltip: _('userlocation_action_export'),
                handler: this.export,
                scope: this
            },*/
            left: '->',
            search: {
                xtype: 'userlocation-field-search',
                width: 210,
                listeners: {
                    search: {
                        fn: function (field) {
                            this._doSearch(field);
                        },
                        scope: this
                    },
                    clear: {
                        fn: function (field) {
                            field.setValue('');
                            this._clearSearch();
                        },
                        scope: this
                    }
                }
            },
            parent: {
                xtype: 'userlocation-combo-location',
                name: 'parent',
                width: 240,
                custm: true,
                clear: true,
                addall: true,
                value: '',
                listeners: {
                    select: {
                        fn: this._filterByCombo,
                        scope: this
                    },
                    afterrender: {
                        fn: this._filterByCombo,
                        scope: this
                    }
                }
            },
            type: {
                xtype: 'userlocation-combo-type',
                name: 'type',
                width: 210,
                custm: true,
                clear: true,
                addall: true,
                value: '',
                listeners: {
                    select: {
                        fn: this._filterByCombo,
                        scope: this
                    },
                    afterrender: {
                        fn: this._filterByCombo,
                        scope: this
                    }
                }
            },
            spacer: {
                xtype: 'spacer',
                style: 'width:1px;'
            }
        };

        var cmp = this.getTopBarComponent(config);
        for (var i = 0; i < cmp.length; i++) {
            var item = cmp[i];
            if (add[item]) {
                tbar.push(add[item]);
            }
        }

        return tbar;
    },

    getColumns: function (config) {
        var columns = [/*this.exp, */this.sm];
        var add = {
            id: {
                width: 10,
                sortable: true,
               /* editor: {
                    xtype: 'textfield',
                },*/
                // hidden: true
            },
            type: {
                width: 8,
                sortable: true,
                editor: {
                    xtype: 'textfield'
                }
            },
            name: {
                width: 20,
                sortable: true,
                editor: {
                    xtype: 'textfield',
                },
                renderer: function (value, metaData, record) {
                    var title = value;
                    var parent = record['json']['parent_name'] || '';
                    if (parent) {
                        title = String.format('{0} </sub>({1})</sub>', title, parent);
                    }
                    return title;
                }
            },
            postal: {
                width: 8,
                sortable: true,
                editor: {
                    xtype: 'textfield'
                }
            },
            gninmb: {
                width: 10,
                sortable: true,
                editor: {
                    xtype: 'textfield'
                }
            },
            okato: {
                width: 10,
                sortable: true,
                editor: {
                    xtype: 'textfield'
                }
            },
            oktmo: {
                width: 10,
                sortable: true,
                editor: {
                    xtype: 'textfield'
                }
            },
            fias: {
                width: 20,
                sortable: true,
                editor: {
                    xtype: 'textfield'
                }
            },
            active: {
                width: 8,
                sortable: true,
                xtype: 'checkcolumn',
                processEvent: function (name, e, grid, rowIndex, colIndex) {
                    if (name === 'mousedown') {
                        var record = grid.store.getAt(rowIndex);
                        record.set(this.dataIndex, !record.data[this.dataIndex]);

                        MODx.Ajax.request({
                            url: userlocation.config.connector_url,
                            params: {
                                action: 'mgr/location/setproperty',
                                id: record['data']['id'],
                                active: record.data[this.dataIndex]
                            },
                            listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh();
                                    },
                                    scope: grid
                                },
                            }
                        });
                        return false; // Cancel row selection.
                    } else {
                        return Ext.grid.ActionColumn.superclass.processEvent.apply(this, arguments);
                    }
                },
            },
            resource: {
                width: 20,
                sortable: true,
                editor: {
                    xtype: 'userlocation-combo-resource',
                    novalue: 1
                },
                renderer: function (value, metaData, record) {
                    var title = value;
                    var pagetitle = record['json']['resource_pagetitle'] || '';

                    if (pagetitle && value) {
                        title = String.format('{0} </sub>({1})</sub>', title, pagetitle);
                    } else {
                        title = String.format('{0} </sub>({1})</sub>', title, _('userlocation_no'));
                    }
                    return title;
                }
            },
            description: {
                width: 25,
                sortable: true,
                editor: {
                    xtype: 'textfield',
                },
                renderer: function (val, cell, row) {
                    return '<span style="white-space: pre-line;">' + val + '</span>';
                }
            },
            actions: {
                width: 5,
                sortable: false,
                renderer: userlocation.tools.renderActions,
                id: 'actions'
            }
        };


        var fields = this.getAllFields(config);

        for (var i = 0; i < fields.length; i++) {
            var field = fields[i];
            if (add[field]) {
                Ext.applyIf(add[field], {
                    header: _('userlocation_header_' + field) || _('userlocation_' + field) || field,
                    tooltip: _('userlocation_tooltip_' + field) || _('userlocation_' + field),
                    dataIndex: field
                });
                columns.push(add[field]);
            }
        }

        return columns;
    },

    getListeners: function (config) {
        return {
            afterrender: function () {

                var cnt = Ext.getCmp('modx-content'),
                    parent = Ext.get('userlocation-panel-widget-div'),
                    dashboard = Ext.select('.dashboard-block-userlocation').item(0);

                if (cnt && parent) {
                    cnt.on('afterlayout', function (elem, layout) {
                        var width = parent.getWidth();
                        // Only resize when more than 500px (else let's use/enable the horizontal scrolling)
                        if (width > 500) {
                            this.setWidth(width);
                        }
                        //Ext.get('userlocation-panel-widget-div').setHeight(800);
                        /* var height = parent.getHeight();
                         console.log(height);
                         if (height < 500) {
                             this.setHeight(800);
                         }*/


                    }, this);
                }

                if (cnt && dashboard) {
                   cnt.on('afterlayout', function (elem, layout) {
                        var height = dashboard.getHeight();
                        if (height > this.getHeight()) {
                            if((tb = this.getBottomToolbar()) && !this.autoPageSize) {
                                pageSize = Math.ceil((height - 150) / 45);
                                if (pageSize != tb.pageSize) {
                                    tb.pageSize = pageSize;

                                    if ((d = Ext.select('.userlocation-grid .x-tbar-page-size').item(0)) && (did = d.id) && (page = Ext.getCmp(did))) {
                                        page.setValue(pageSize);
                                    }

                                    params = this.getStore().baseParams;
                                    params['limit'] = pageSize;
                                    this.getStore().load({params:{
                                            limit: pageSize
                                        }});

                                    this.autoPageSize = 1;
                                }

                            }

                        }
                    }, this);
                }

            },
            /* render: {
                 fn: this.dd,
                 scope: this
             },*/
            beforerender: function (grid, rowIndex, e) {
                this.getView().getRowClass = function (rec) {
                    var cls = [];
                    // fix expander
                    if (!!rec.json['description']) {
                        cls.push('x-grid3-row-collapsed');
                    }
                    return cls.join(' ');
                };
            },
        };
    },

    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds();
        var row = grid.getStore().getAt(rowIndex);
        var menu = userlocation.tools.getMenu(row.data['actions'], this, ids);
        this.addContextMenuItem(menu);
    },

    onClick: function (e) {
        var elem = e.getTarget();
        if (elem.nodeName == 'BUTTON') {
            var row = this.getSelectionModel().getSelected();
            if (typeof (row) != 'undefined') {
                var action = elem.getAttribute('action');
                if (action == 'showMenu') {
                    var ri = this.getStore().find('id', row.id);
                    return this._showMenu(this, ri, e);
                } else if (typeof this[action] === 'function') {
                    this.menu.record = row.data;
                    return this[action](this, e);
                }
            }
        }
        return this.processEvent('click', e);
    },

    setAction: function (method, field, value) {
        var ids = this._getSelectedIds();
        if (!ids.length && (field !== 'false')) {
            return false;
        }
        MODx.Ajax.request({
            url: userlocation.config.connector_url,
            params: {
                action: 'mgr/location/multiple',
                method: method,
                field_name: field,
                field_value: value,
                ids: Ext.util.JSON.encode(ids)
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    },
                    scope: this
                },
                failure: {
                    fn: function (response) {
                        MODx.msg.alert(_('error'), response.message);
                    },
                    scope: this
                }
            }
        })
    },

    active: function (btn, e) {
        this.setAction('setproperty', 'active', 1);
    },

    inactive: function (btn, e) {
        this.setAction('setproperty', 'active', 0);
    },

    remove: function () {
        Ext.MessageBox.confirm(
            _('userlocation_action_remove'),
            _('userlocation_confirm_remove'),
            function (val) {
                if (val == 'yes') {
                    this.setAction('remove');
                }
            },
            this
        );
    },

    truncate: function () {
        Ext.MessageBox.confirm(
            _('userlocation_action_truncate'),
            _('userlocation_confirm_truncate'),
            function (val) {
                if (val == 'yes') {
                    this.setAction('truncate', 'false');
                }
            },
            this
        );
    },

    update: function (btn, e, row) {
        var record = typeof (row) != 'undefined' ? row.data : this.menu.record;
        MODx.Ajax.request({
            url: userlocation.config.connector_url,
            params: {
                action: 'mgr/location/get',
                id: record.id,
                option: true,
                key: 'tag'
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var record = r.object;
                        var w = MODx.load({
                            xtype: 'userlocation-window-update-location',
                            title: _('userlocation_action_update'),
                            action: 'mgr/location/update',
                            record: record,
                            update: true,
                            listeners: {
                                success: {
                                    fn: this.refresh,
                                    scope: this
                                }
                            }
                        });
                        w.reset();
                        w.setValues(record);
                        w.show(e.target);
                    },
                    scope: this
                }
            }
        });
    },

    create: function (btn, e) {
        var record = {
            active: 1,
            cost: 0,
            userlocation: this.getStore().baseParams['userlocation'],
        };

        w = MODx.load({
            xtype: 'userlocation-window-update-location',
            record: record,
            listeners: {
                success: {
                    fn: this.refresh,
                    scope: this
                }
            }
        });
        w.reset();
        w.setValues(record);
        w.show(e.target);
    },

    import: function (btn, e) {
        var record = {
            csv_terminated: ',',
            csv_enclosed: '"',
            csv_escaped: "'",
            csv_ignore_lines: "1",
            load_method: "ignore",
            load_truncate: "1"
        };
        var w = MODx.load({
            xtype: 'userlocation-window-import-location',
            record: record,
            listeners: {
                success: {
                    fn: this.refresh,
                    scope: this
                }
            }
        });
        w.reset();
        w.setValues(record);
        w.show(e.target);
    },

    export: function (btn, e) {
        var record = {
            csv_terminated: ',',
            csv_enclosed: '"',
            csv_escaped: "'",
            csv_ignore_lines: "1",
            load_method: "ignore"
        };
        var w = MODx.load({
            xtype: 'userlocation-window-export-location',
            record: record,
            listeners: {
                success: {
                    fn: function (r) {
                        console.log(111);
                    },
                    scope: this
                },
                failure: {
                    fn: function (response) {
                        if (r.message) {
                            MODx.msg.alert(_('error'), r.message);
                        }
                    },
                    scope: this
                }
            }
        });
        w.reset();
        w.setValues(record);
        w.show(e.target);
    },

    _filterByCombo: function (cb) {
        this.getStore().baseParams[cb.name] = cb.value;
        this.getBottomToolbar().changePage(1);
    },

    _doSearch: function (tf) {
        this.getStore().baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },

    _clearSearch: function () {
        this.getStore().baseParams.query = '';
        this.getBottomToolbar().changePage(1);
    },

    _updateRow: function (response) {
        this.refresh();
    },

    _getSelectedIds: function () {
        var ids = [];
        var selected = this.getSelectionModel().getSelections();

        for (var i in selected) {
            if (!selected.hasOwnProperty(i)) {
                continue;
            }
            ids.push(selected[i]['id']);
        }

        return ids;
    }

});
Ext.reg('userlocation-grid-location', userlocation.grid.Location);





