/**
 * Вкладки/поля для окон добавления/редактирования
 *
 * @param config
 * @returns {{object}}
 * @constructor
 */
msPromoCode2.fields.Coupon = function (config) {
    var data = config['record'] ? config.record['object'] : null;
    var coupon_id = data && data['id'] ? data['id'] : 0;
    var fields = {
        xtype: 'modx-tabs',
        border: true,
        autoHeight: true,
        // style: {marginTop: '10px'},
        anchor: '100% 100%',
        items: [{
            title: _('mspc2_tab_main'),
            layout: 'form',
            cls: 'modx-panel mspc2-panel mspc2-tab-panel',
            autoHeight: true,
            items: [],
        }, {
            title: _('mspc2_tab_config'),
            layout: 'form',
            cls: 'modx-panel mspc2-panel mspc2-tab-panel',
            autoHeight: true,
            items: [],
        }, {
            title: _('mspc2_tab_joins'),
            layout: 'form',
            cls: 'modx-panel modx-panel-subtabs mspc2-panel mspc2-tab-panel',
            autoHeight: true,
            items: [],
        }],
        listeners: {
            afterrender: function (tabs) {
                // Рендерим вторую вкладку, иначе данные с неё не передаются в процессор
                tabs.setActiveTab(1);
                tabs.setActiveTab(0);

                if (config['activeTab']) {
                    tabs.setActiveTab(config['activeTab']);
                }
            },
        },
    };
    var tabMain = fields.items[0];
    var tabConfig = fields.items[1];
    var tabJoins = fields.items[2];

    /**
     * Tab / Main
     */
    tabMain.items.push({
        layout: 'column',
        border: false,
        style: {marginTop: '0px'},
        anchor: '100%',
        items: [{
            columnWidth: .65,
            layout: 'form',
            style: {marginRight: '5px'},
            items: [{
                xtype: 'mspc2-field-coupon', // 'textfield',
                id: config['id'] + '-code',
                name: 'code',
                fieldLabel: _('mspc2_field_code'),
                anchor: '100%',
                regexValue: '[a-zA-Z0-9]{10}',
            }, {
                layout: 'column',
                border: false,
                style: {marginTop: '0px'},
                anchor: '100%',
                items: [{
                    columnWidth: .5,
                    layout: 'form',
                    style: {marginRight: '5px'},
                    items: [{
                        xtype: 'mspc2-combo-list',
                        id: config['id'] + '-list',
                        name: 'list',
                        fieldLabel: _('mspc2_field_list'),
                        anchor: '100%',
                    }],
                }, {
                    columnWidth: .5,
                    layout: 'form',
                    style: {marginLeft: '5px'},
                    items: [{
                        layout: 'column',
                        border: false,
                        style: {marginTop: '0px'},
                        anchor: '100%',
                        items: [{
                            columnWidth: .5,
                            layout: 'form',
                            style: {marginRight: '5px'},
                            items: [{
                                xtype: 'textfield',
                                id: config['id'] + '-discount',
                                name: 'discount',
                                fieldLabel: _('mspc2_field_discount'),
                                anchor: '100%',
                            }],
                        }, {
                            columnWidth: .5,
                            layout: 'form',
                            style: {marginLeft: '5px'},
                            items: [{
                                xtype: 'numberfield',
                                id: config['id'] + '-count',
                                name: 'count',
                                fieldLabel: _('mspc2_field_count'),
                                emptyText: _('mspc2_field_count_desc'),
                                anchor: '100%',
                                allowDecimals: false,
                            }],
                        }],
                    }],
                }],
            }, {
                layout: 'column',
                border: false,
                style: {marginTop: '0px'},
                anchor: '100%',
                items: [{
                    columnWidth: .5,
                    layout: 'form',
                    style: {marginRight: '5px'},
                    items: [{
                        xtype: 'mspc2-datetime',
                        id: config['id'] + '-startedon',
                        name: 'startedon',
                        fieldLabel: _('mspc2_field_startedon'),
                        anchor: '100%',
                        // hideTime: true,
                        timeWidth: 95,
                    }],
                }, {
                    columnWidth: .5,
                    layout: 'form',
                    style: {marginLeft: '5px'},
                    items: [{
                        xtype: 'mspc2-datetime',
                        id: config['id'] + '-stoppedon',
                        name: 'stoppedon',
                        fieldLabel: _('mspc2_field_stoppedon'),
                        anchor: '100%',
                        // hideTime: true,
                        timeWidth: 95,
                    }],
                }],
            }],
        }, {
            columnWidth: .35,
            layout: 'form',
            style: {marginLeft: '5px'},
            items: [{
                xtype: 'textarea',
                id: config['id'] + '-description',
                name: 'description',
                fieldLabel: _('mspc2_field_description'),
                height: 170,
                anchor: '100%',
            }],
        }],
    }, {
        layout: 'column',
        border: false,
        style: {marginTop: '0px'},
        anchor: '100%',
        items: [{
            columnWidth: 1,
            layout: 'form',
            items: [{
                xtype: 'xcheckbox',
                id: config['id'] + '-active',
                name: 'active',
                boxLabel: _('mspc2_field_active'),
            }],
        }],
    });

    /**
     * Tab / Config
     */
    tabConfig.items.push({
        layout: 'column',
        border: false,
        style: {marginTop: '0px'},
        anchor: '100%',
        items: [{
            columnWidth: 1,
            layout: 'form',
            items: [{
                xtype: 'xcheckbox',
                id: config['id'] + '-showinfo',
                name: 'showinfo',
                fieldLabel: _('mspc2_field_showinfo'),
                boxLabel: _('mspc2_field_showinfo_desc'),
            }],
        }]
    }, {
        layout: 'column',
        border: false,
        style: {marginTop: '10px'},
        anchor: '100%',
        items: [{
            columnWidth: 1,
            layout: 'form',
            items: [{
                xtype: 'xcheckbox',
                id: config['id'] + '-allcart',
                name: 'allcart',
                fieldLabel: _('mspc2_field_allcart'),
                boxLabel: _('mspc2_field_allcart_desc'),
                handler: function ($cb, checked) {
                    [
                        'oneunit',
                        'onlycart',
                        'oldprice',
                    ].forEach(function (k) {
                        let $cmp = Ext.getCmp(config['id'] + '-' + k);
                        if (!!$cmp) {
                            $cmp.setReadOnly(checked);
                            if (checked) {
                                $cmp.hide();
                            } else {
                                $cmp.show();
                            }
                        }
                    });

                    let $joins = Ext.getCmp(config['id'] + '-joins-tabs');
                    if (!!$joins) {
                        $joins.hideDiscountColumn(checked);
                    }
                },
            }],
        }]
    }, {
        layout: 'column',
        border: false,
        style: {marginTop: '10px'},
        anchor: '100%',
        items: [{
            columnWidth: 1,
            layout: 'form',
            items: [{
                xtype: 'xcheckbox',
                id: config['id'] + '-oneunit',
                name: 'oneunit',
                fieldLabel: _('mspc2_field_oneunit'),
                boxLabel: _('mspc2_field_oneunit_desc'),
            }],
        }]
    }, {
        layout: 'column',
        border: false,
        style: {marginTop: '10px'},
        anchor: '100%',
        items: [{
            columnWidth: 1,
            layout: 'form',
            items: [{
                xtype: 'xcheckbox',
                id: config['id'] + '-onlycart',
                name: 'onlycart',
                fieldLabel: _('mspc2_field_onlycart'),
                boxLabel: _('mspc2_field_onlycart_desc'),
            }],
        }]
    }, {
        layout: 'column',
        border: false,
        style: {marginTop: '10px'},
        anchor: '100%',
        items: [{
            columnWidth: 1,
            layout: 'form',
            items: [{
                xtype: 'xcheckbox',
                id: config['id'] + '-unsetifnull',
                name: 'unsetifnull',
                fieldLabel: _('mspc2_field_unsetifnull'),
                boxLabel: _('mspc2_field_unsetifnull_desc'),
                handler: function ($checkbox, checked) {
                    var $msg = Ext.getCmp(config['id'] + '-unsetifnull-msg');
                    $msg[checked ? 'show' : 'hide']();
                },
            }, {
                xtype: 'textfield',
                id: config['id'] + '-unsetifnull-msg',
                name: 'unsetifnull_msg',
                fieldLabel: _('mspc2_field_unsetifnull_msg'),
                emptyText: _('mspc2_field_unsetifnull_msg_desc'),
                anchor: '100%',
                hidden: true,
            }],
        }],
    }, {
        layout: 'column',
        border: false,
        style: {marginTop: '10px'},
        anchor: '100%',
        items: [{
            columnWidth: 1,
            layout: 'form',
            items: [{
                xtype: 'xcheckbox',
                id: config['id'] + '-oldprice',
                name: 'oldprice',
                fieldLabel: _('mspc2_field_oldprice'),
                boxLabel: _('mspc2_field_oldprice_desc'),
            }],
        }]
    });

    /**
     * Tab / Joins
     */
    tabJoins.items.push({
        xtype: 'modx-tabs',
        id: config['id'] + '-joins-tabs',
        border: true,
        autoHeight: true,
        cls: '',
        // style: {marginTop: '10px'},
        anchor: '100% 100%',
        hideDiscountColumn: function (status) {
            let $tabs = this; // Ext.getCmp(config.id + '-joins-tabs');
            if (!$tabs.rendered) {
                return;
            }
            $tabs.items.items.map($tab => {
                let $grid = $tab.items.items[0];
                let gridCategoriesColumns = $grid.getColumnModel();
                let discountIndex = gridCategoriesColumns.findColumnIndex('discount');
                if (discountIndex !== -1) {
                    gridCategoriesColumns.setHidden(discountIndex, status);
                }
            });
        },
        listeners: {
            afterrender: {
                fn: function ($tabs) {
                    let $allcart = Ext.getCmp(config['id'] + '-allcart');
                    if (!!$allcart) {
                        $tabs.hideDiscountColumn($allcart.getValue());
                    }
                },
                scope: this
            }
        },
        items: [{
            title: _('mspc2_tab_joins_categories'),
            layout: 'form',
            cls: 'modx-panel mspc2-panel mspc2-tab-panel',
            autoHeight: true,
            items: [{
                xtype: 'mspc2-grid-joins',
                id: 'mspc2-grid-joins-categories',
                type: 'category',
                coupon: coupon_id,
                // discount: true,
            }],
        }, {
            title: _('mspc2_tab_joins_products'),
            layout: 'form',
            cls: 'modx-panel mspc2-panel mspc2-tab-panel',
            autoHeight: true,
            items: [{
                xtype: 'mspc2-grid-joins',
                id: 'mspc2-grid-joins-products',
                type: 'product',
                coupon: coupon_id,
                // discount: true,
            }],
        }],
    });

    if (data) {
        tabMain.items.push({
            xtype: 'hidden',
            id: config['id'] + '-id',
            name: 'id',
        });
    }

    return fields;
};

/**
 * Окно добавления объекта
 *
 * @param config
 * @constructor
 */
msPromoCode2.window.CouponCreate = function (config) {
    config = config || {};
    if (!config['id']) {
        config['id'] = 'mspc2-window-coupon-create';
    }
    Ext.applyIf(config, {
        title: _('mspc2_window_coupon_create'),
        baseParams: {
            action: 'mgr/coupons/create',
        },
        modal: true,
        width: 800,
    });
    msPromoCode2.window.CouponCreate.superclass.constructor.call(this, config);
};
Ext.extend(msPromoCode2.window.CouponCreate, msPromoCode2.window.Default, {
    getFields: function (config) {
        return msPromoCode2.fields.Coupon(config);
    },
});
Ext.reg('mspc2-window-coupon-create', msPromoCode2.window.CouponCreate);

/**
 * Окно редактирования объекта
 *
 * @param config
 * @constructor
 */
msPromoCode2.window.CouponUpdate = function (config) {
    config = config || {};
    if (!config['id']) {
        config['id'] = 'mspc2-window-coupon-update';
    }
    Ext.applyIf(config, {
        title: _('mspc2_window_coupon_update'),
        baseParams: {
            action: 'mgr/coupons/update',
        },
        modal: true,
        width: 800,
    });
    msPromoCode2.window.CouponUpdate.superclass.constructor.call(this, config);
};
Ext.extend(msPromoCode2.window.CouponUpdate, msPromoCode2.window.Default, {
    getFields: function (config) {
        return msPromoCode2.fields.Coupon(config);
    },
});
Ext.reg('mspc2-window-coupon-update', msPromoCode2.window.CouponUpdate);