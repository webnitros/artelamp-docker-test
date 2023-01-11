msPromoCode2.grid.Coupons = function (config) {
    config = config || {};
    if (!config['id']) {
        config['id'] = 'mspc2-grid-coupons';
    }
    config['actionPrefix'] = 'mgr/coupons/';
    Ext.applyIf(config, {
        baseParams: {
            action: config['actionPrefix'] + 'getlist',
            sort: 'createdon',
            dir: 'DESC',
        },
        multi_select: true,
        // pageSize: Math.round(MODx.config['default_per_page'] / 2),
        enableDragDrop: false,
        // ddGroup: config['id'],
        // ddAction: config['actionPrefix'] + 'sort',
    });
    msPromoCode2.grid.Coupons.superclass.constructor.call(this, config);
};
Ext.extend(msPromoCode2.grid.Coupons, msPromoCode2.grid.Default, {
    /**
     * @param config
     * @returns {*[]}
     */
    getFields: function (config) {
        return [
            'id',
            'code',
            'discount',
            'count',
            'orders',
            'startedon',
            'stoppedon',
            'createdon',
            'active',
            'actions',
        ];
    },

    /**
     * @param config
     * @returns {*[]}
     */
    getColumns: function (config) {
        return [{
            header: _('mspc2_grid_id'),
            dataIndex: 'id',
            width: 50,
            sortable: true,
            fixed: true,
            resizable: false,
            hidden: true,
        }, {
            header: _('mspc2_grid_clipboard'),
            dataIndex: 'code',
            width: 25,
            sortable: true,
            fixed: true,
            resizable: false,
            hidden: false,
            renderer: msPromoCode2.renderer['Clipboard'],
        }, {
            header: _('mspc2_grid_coupon'),
            dataIndex: 'code',
            width: 200,
            sortable: true,
            renderer: msPromoCode2.renderer['Code'],
        }, {
            header: _('mspc2_grid_discount'),
            dataIndex: 'discount',
            width: 77,
            sortable: true,
            fixed: true,
            resizable: false,
            renderer: msPromoCode2.renderer['Discount'],
        }, {
            header: _('mspc2_grid_count'),
            dataIndex: 'count',
            width: 77,
            sortable: true,
            fixed: true,
            resizable: false,
            renderer: msPromoCode2.renderer['Count'],
        }, {
            header: _('mspc2_grid_orders'),
            dataIndex: 'orders',
            width: 77,
            sortable: true,
            fixed: true,
            resizable: false,
            renderer: msPromoCode2.renderer['Orders'],
        }, {
            header: _('mspc2_grid_lifetime'),
            dataIndex: 'startedon',
            width: 240,
            sortable: true,
            fixed: true,
            resizable: false,
            hidden: false,
            renderer: msPromoCode2.renderer['Lifetime'],
        }, {
            header: _('mspc2_grid_createdon'),
            dataIndex: 'createdon',
            width: 130,
            sortable: true,
            fixed: true,
            resizable: false,
            hidden: true,
            renderer: msPromoCode2.renderer['DateTime'],
        }, {
            header: _('mspc2_grid_active'),
            dataIndex: 'active',
            width: 60,
            sortable: true,
            fixed: true,
            resizable: false,
            renderer: msPromoCode2.renderer['Boolean'],
        }, {
            header: _('mspc2_grid_actions'),
            dataIndex: 'actions',
            id: 'actions',
            width: 130,
            sortable: false,
            fixed: true,
            resizable: false,
            renderer: msPromoCode2.renderer['Actions'],
        }];
    },

    /**
     * @param config
     * @returns {*[]}
     */
    getTopBar: function (config) {
        return [{
            text: '<i class="icon icon-plus"></i>&nbsp;' + _('mspc2_button_create'),
            cls: 'primary-button',
            handler: this.createObject,
            scope: this,
        }, '->', {
            xtype: 'mspc2-combo-list',
            id: config['id'] + '-list',
            filterName: 'list',
            emptyText: _('mspc2_grid_list') + '...',
            width: 150,
            filter: true,
            listeners: {
                afterrender: {fn: this._doFilter, scope: this},
                select: {fn: this._doFilter, scope: this},
            },
            value: MODx.config['mspc2_backend_coupons_default_list'] || '',
        }, this.getSearchField(config)];
    },

    /**
     * @param config
     * @returns {{rowDblClick: rowDblClick}}
     */
    getListeners: function (config) {
        return {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.updateObject(grid, e, row);
            },
        };
    },

    /**
     * @param btn
     * @param e
     */
    createObject: function (btn, e) {
        var w = MODx.load({
            xtype: 'mspc2-window-coupon-create',
            // id: Ext.id(),
            listeners: {
                success: {fn: this._listenerRefresh, scope: this},
                // hide: {fn: this._listenerRefresh, scope: this},
                failure: {fn: this._listenerHandler, scope: this},
            },
        });
        w.reset();
        w.setValues({
            list: MODx.config['mspc2_backend_coupons_default_list'] || 'default',
            showinfo: true,
            active: true,
        });
        w.show(e['target']);
    },

    /**
     * @param btn
     * @param e
     * @param row
     * @param activeTab
     * @returns {boolean}
     */
    updateObject: function (btn, e, row, activeTab) {
        if (typeof(row) !== 'undefined') {
            this.menu.record = row.data;
        } else if (!this.menu.record) {
            return false;
        }
        var id = this.menu.record.id;

        if (typeof(activeTab) === 'undefined') {
            activeTab = 0;
        }

        MODx.Ajax.request({
            url: this.config['url'],
            params: {
                action: this['actionPrefix'] + 'get',
                id: id,
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var values = r['object'];
                        ['startedon', 'stoppedon', 'createdon', 'updatedon'].forEach(function (k) {
                            if (values[k]) {
                                values[k] = '' + values[k];
                            }
                        });

                        var w = MODx.load({
                            xtype: 'mspc2-window-coupon-update',
                            // id: Ext.id(),
                            record: r,
                            activeTab: activeTab,
                            listeners: {
                                success: {fn: this._listenerRefresh, scope: this},
                                // hide: {fn: this._listenerRefresh, scope: this},
                                failure: {fn: this._listenerHandler, scope: this},
                            },
                        });
                        w.reset();
                        w.setValues(values);
                        w.show(e['target']);
                    }, scope: this
                },
                failure: {fn: this._listenerHandler, scope: this},
            }
        });
    },

    /**
     * @param btn
     * @param e
     * @param row
     * @returns {boolean}
     */
    configObject: function (btn, e, row) {
        return this.updateObject(btn, e, row, 1);
    },

    /**
     * @param btn
     * @param e
     * @param row
     * @returns {boolean}
     */
    joinsObject: function (btn, e, row) {
        return this.updateObject(btn, e, row, 2);
    },

    /**
     * @returns {*|boolean}
     */
    enableObject: function () {
        this.loadMask.show();
        return this._doAction('enable');
    },

    /**
     * @returns {*|boolean}
     */
    disableObject: function () {
        this.loadMask.show();
        return this._doAction('disable');
    },

    /**
     * @returns {*|boolean}
     */
    removeObject: function () {
        return this._doAction('remove', null, true);
    },
});
Ext.reg('mspc2-grid-coupons', msPromoCode2.grid.Coupons);