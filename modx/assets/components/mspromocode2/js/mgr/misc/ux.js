msPromoCode2.ux.OrderFieldset = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: config['id'] || 'mspc2-order-fieldset',
        url: msPromoCode2.config['connector_url'],
        items: this.getItems(config),
        listeners: this.getListeners(config),
        layout: config['layout'] || 'column',
        cls: (config['cls'] || '') + ' mspc2-order-fieldset',
        style: config['style'] || {padding: '0px', marginTop: '0px', marginBottom: '15px'},
        defaults: config['defaults'] || {msgTarget: 'under', border: false},
        onRender: function () {
            var fs = this;
            msPromoCode2.ux.OrderFieldset.superclass.onRender.apply(fs, arguments);
            // console.log('msPromoCode2.ux.OrderFieldset onRender fs', fs);

            // Get order window
            fs['window'] = fs.findParentBy(function(a) {
                return a['xtype'] === 'minishop2-window-order-update';
            }) || undefined;
        },
        isRequest: false,
    });
    msPromoCode2.ux.OrderFieldset.superclass.constructor.call(this, config);
    this['config'] = config;

    //
    this.on('afterrender', function (fs) {
        // console.log('msPromoCode2.ux.OrderFieldset afterrender fs', fs);

        //
        fs['loadMask'] = new Ext.LoadMask(fs['bwrap'], {
            msg: _('mspc2_ms2_message_loading'),
        });
        if (fs.isRequest) {
            fs.loadMask.show();
        }
    });

    this.initialize(config);
};

Ext.extend(msPromoCode2.ux.OrderFieldset, Ext.form.FieldSet, {
    /**
     * @param config
     * @constructor
     */
    initialize: function (config) {
        var fs = this;
        fs.addEvents({
            beforeInit: true,
            afterInit: true,
        });

        //
        if (fs.fireEvent('beforeInit')) {
            fs.request(
                'get',
                {},
                function (response) {
                    // console.log('initialize callback response', response);
                }
            );

            fs.fireEvent('afterInit');
        }
    },

    /**
     * @param button
     * @param e
     */
    submit: function (button, e) {
        var fs = this;

        // console.log('msPromoCode2.ux.OrderFieldset submit fs', fs);
        // console.log('msPromoCode2.ux.OrderFieldset submit button', button);

        this.request(
            (fs.status ? 'unset' : 'set'),
            {},
            function (response) {
                // console.log('submit callback response', response);
            }
        );
    },

    /**
     */
    request: function (action, data, callback) {
        var fs = this;
        var field = fs.findBy(function (a) {return a['id'] === fs['id'] + '-code'});
        field = field['length'] ? field[0] : undefined;
        var button = fs.findBy(function (a) {return a['id'] === fs['id'] + '-submit'});
        button = button['length'] ? button[0] : undefined;
        if (!field || !button) {
            return;
        }

        data = typeof(data) === 'object' ? data : {};
        data['order'] = fs['order'];
        data['code'] = field.getValue();

        // console.log('msPromoCode2.ux.OrderFieldset request fs', fs);
        // console.log('msPromoCode2.ux.OrderFieldset request action', action);
        // console.log('msPromoCode2.ux.OrderFieldset request data', data);

        fs.isRequest = true;
        fs.loadMask && fs.loadMask.show();

        MODx.Ajax.request({
            url: fs.config['url'],
            params: Ext.apply({
                action: 'mgr/coupons/orders/' + action,
            }, data),
            listeners: {
                success: {
                    fn: function (response) {
                        // console.log('request success response', response);

                        fs.isRequest = false;
                        fs.loadMask && fs.loadMask.hide();

                        if (response['success'] && response['object']) {
                            // Set status
                            fs.status = response.object['status'] || false;

                            // Set field value and disabled
                            field.setValue(response.object.coupon['code']);
                            field.setDisabled(fs.status); // field.setReadOnly(fs.status);

                            // Set button text
                            var buttonLexicon = fs.status
                                ? 'mspc2_ms2_window_unset' : 'mspc2_ms2_window_set';
                            button.setText(_(buttonLexicon));

                            // Set coupon discounts
                            ['discount', 'discount_amount'].forEach(function (k) {
                                var fieldCmp = fs.findBy(function (a) {return a.name === 'mspc2_' + k});
                                if (fieldCmp['length'] && k in response.object['coupon']) {
                                    fieldCmp = fieldCmp[0];
                                    fieldCmp.setValue(response.object.coupon[k] || 0);
                                }
                            });

                            // Set order amounts
                            if (response.object['order']) {
                                ['cost', 'cart_cost', 'delivery_cost'].forEach(function (k) {
                                    var fieldCmp = fs.window.findBy(function (a) {return a.name === k});
                                    if (fieldCmp['length'] && k in response.object['order']) {
                                        fieldCmp = fieldCmp[0];
                                        fieldCmp.setValue(response.object.order[k]);
                                    }
                                });
                            }
                        }

                        callback && callback(response);
                    },
                    scope: fs,
                },
                failure: {
                    fn: function (response) {
                        console.log('request failure response', response);

                        fs.isRequest = false;
                        fs.loadMask && fs.loadMask.hide();

                        callback && callback(response);
                    },
                    scope: fs,
                },
            }
        });
    },

    /**
     * @param config
     * @returns {*[]}
     */
    getItems: function (config) {
        return [{
            columnWidth: .5,
            layout: 'form',
            style: {marginTop: '-5px'},
            items: [{
                layout: 'column',
                border: false,
                style: {marginTop: '0px'},
                anchor: '100%',
                items: [{
                    columnWidth: .7,
                    layout: 'form',
                    style: {marginRight: '5px'},
                    items: [{
                        xtype: 'textfield',
                        id: config['id'] + '-code',
                        name: 'mspc2_code',
                        fieldLabel: _('mspc2_ms2_field_code'),
                        anchor: '100%',
                        listeners: {
                            specialkey: {
                                fn: this.onSpecialKeyDown,
                                scope: this,
                            },
                        },
                    }],
                }, {
                    columnWidth: .3,
                    layout: 'form',
                    style: {marginTop: '37px', marginLeft: '5px'},
                    items: [{
                        xtype: 'button',
                        id: config['id'] + '-submit',
                        text: _('mspc2_ms2_window_set'),
                        cls: 'mspc2-button primary-button',
                        handler: this.submit,
                        scope: this,
                    }],
                }],
            }]
        }, {
            columnWidth: .5,
            layout: 'form',
            style: {marginTop: '0px', textAlign: 'center'},
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
                        xtype: 'displayfield',
                        id: config['id'] + '-discount',
                        name: 'mspc2_discount',
                        fieldLabel: _('mspc2_ms2_field_discount'),
                        anchor: '100%',
                    }],
                }, {
                    columnWidth: .5,
                    layout: 'form',
                    style: {marginLeft: '5px'},
                    items: [{
                        xtype: 'displayfield',
                        id: config['id'] + '-discount-amount',
                        name: 'mspc2_discount_amount',
                        fieldLabel: _('mspc2_ms2_field_discount_amount'),
                        anchor: '100%',
                    }],
                }],
            }]
        }];
    },

    /**
     * @param config
     * @returns {{}}
     */
    getListeners: function (config) {
        return {};
    },

    /**
     * @param field
     * @param e
     */
    onSpecialKeyDown: function (field, e) {
        var fs = this;
        var button = fs.findBy(function (a) {
            return a['id'] === fs['id'] + '-submit';
        });
        button = button['length'] ? button[0] : undefined;
        if (e.getKey() === e.ENTER) {
            fs.submit(button, e);
        }
    },
});
Ext.reg('mspc2-order-fieldset', msPromoCode2.ux.OrderFieldset);