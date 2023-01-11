msPromoCode2.grid.Joins = function (config) {
    config = config || {};
    if (!config['type']) {
        return;
    }

    if (!config['id']) {
        config['id'] = 'mspc2-grid-joins';
    }
    config['actionPrefix'] = 'mgr/joins/';
    Ext.applyIf(config, {
        baseParams: {
            action: config['actionPrefix'] + 'getlist',
            type: config['type'] || '',
            coupon: config['coupon'] || '',
            sort: 'id',
            dir: 'DESC',
        },
        multi_select: true,
        pageSize: Math.round(MODx.config['default_per_page'] / 2),
        enableDragDrop: false,
        cls: 'mspc2-grid_joins',

        save_action: config['actionPrefix'] + 'update',
        save_callback: this.updateRow,
        autosave: true,
    });
    msPromoCode2.grid.Joins.superclass.constructor.call(this, config);
};
Ext.extend(msPromoCode2.grid.Joins, msPromoCode2.grid.Default, {
    /**
     * @param config
     * @returns {*[]}
     */
    getFields: function (config) {
        return [
            'id',
            'resource',
            'pagetitle',
            'discount',
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
            header: _('mspc2_grid_id'),
            dataIndex: 'resource',
            width: 50,
            sortable: true,
            fixed: true,
            resizable: false,
            hidden: false,
            renderer: msPromoCode2.renderer['Value'],
        }, {
            header: _('mspc2_grid_resource'),
            dataIndex: 'pagetitle',
            width: 200,
            sortable: true,
            renderer: msPromoCode2.renderer['Pagetitle'],
        }, {
            header: _('mspc2_grid_discount'),
            dataIndex: 'discount',
            width: 100,
            sortable: true,
            fixed: true,
            resizable: false,
            // hidden: typeof(config.discount) === 'boolean' ? config.discount : true,
            renderer: msPromoCode2.renderer['Value'],
            editor: {xtype: 'textfield'},
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
            xtype: 'mspc2-combo-resource',
            id: config['id'] + '-resource',
            // filterName: 'resource',
            type: config['type'],
            coupon: config['coupon'],
            emptyText: _('mspc2_combo_joins_select'),
            width: 450,
            listeners: {
                select: {
                    fn: function (combo, rec) {
                        if (!rec.data['id']) {
                            return false;
                        }

                        this.createObject(rec.data['id']);
                    },
                    scope: this,
                },
            },
        }, '->', this.getSearchField(config)];
    },

    /**
     * @param config
     * @returns {{}}
     */
    getListeners: function (config) {
        return {};
    },

    /**
     *
     * @param resource
     */
    createObject: function (resource) {
        // this.loadMask.show();

        console.log('createObject this', this);

        MODx.Ajax.request({
            url: this.config['url'],
            params: {
                action: this['actionPrefix'] + 'create',
                type: this.config['type'],
                coupon: this.config['coupon'],
                resource: resource,
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var grid = this;
                        this._listenerHandler(r, function () {
                            grid.refresh();
                        });
                    }, scope: this
                },
                failure: {
                    fn: function (r) {
                        var grid = this;
                        this._listenerHandler(r, function () {
                            grid.refresh();
                        });
                    }, scope: this
                },
            },
        });
    },

    /**
     *
     * @param response
     */
    updateRow: function (response) {
        this.refresh();

        console.log('updateRow this', this);
        console.log('updateRow response', response);
    },

    /**
     * @returns {*|boolean}
     */
    removeObject: function () {
        return this._doAction('remove', null, true);
    },
});
Ext.reg('mspc2-grid-joins', msPromoCode2.grid.Joins);