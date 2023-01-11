/**
 *
 * @param config
 * @constructor
 */
msPromoCode2.combo.Search = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        xtype: 'twintrigger',
        ctCls: 'x-field-search',
        allowBlank: true,
        msgTarget: 'under',
        emptyText: _('search'),
        name: 'query',
        triggerAction: 'all',
        clearBtnCls: 'x-field-search-clear',
        searchBtnCls: 'x-field-search-go',
        onTrigger1Click: this._triggerSearch,
        onTrigger2Click: this._triggerClear,
    });
    msPromoCode2.combo.Search.superclass.constructor.call(this, config);
    this.on('render', function () {
        this.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
            this._triggerSearch();
        }, this);
        this.positionEl.setStyle('margin-right', '1px');
    });
    this.addEvents('clear', 'search');
};
Ext.extend(msPromoCode2.combo.Search, Ext.form.TwinTriggerField, {
    initComponent: function () {
        Ext.form.TwinTriggerField.superclass.initComponent.call(this);
        this.triggerConfig = {
            tag: 'span',
            cls: 'x-field-search-btns',
            cn: [
                {tag: 'div', cls: 'x-form-trigger ' + this.searchBtnCls},
                {tag: 'div', cls: 'x-form-trigger ' + this.clearBtnCls}
            ]
        };
    },
    _triggerSearch: function () {
        this.fireEvent('search', this);
    },
    _triggerClear: function () {
        this.fireEvent('clear', this);
    },
});
Ext.reg('mspc2-field-search', msPromoCode2.combo.Search);


/**
 *
 * @param config
 * @constructor
 */
msPromoCode2.combo.Coupon = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        xtype: 'twintrigger',
        id: 'mspc2-field-coupon',
        name: 'coupon',
        emptyText: '',
        allowBlank: true,
        msgTarget: 'under',
        triggerAction: 'all',
        ctCls: 'x-field-coupon',
        fieldClass: 'x-field-coupon-input x-form-field',
        regexInputCls: 'x-field-coupon-regex__input',
        genBtnCls: 'x-field-coupon-regex__btn',
        onTrigger1Click: this._triggerGen,
        // onTrigger2Click: this._triggerGen,
        regexValue: '',
    });
    msPromoCode2.combo.Coupon.superclass.constructor.call(this, config);
    this.on('render', function () {
        this.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
            //this._triggerSearch();
        }, this);
        this.positionEl.setStyle('margin-right', '1px');
    });
    this.addEvents('gen');
};
Ext.extend(msPromoCode2.combo.Coupon, Ext.form.TwinTriggerField, {
    /**
     *
     */
    initComponent: function () {
        Ext.form.TwinTriggerField.superclass.initComponent.call(this);

        this.triggerConfig = {
            id: this.initialConfig['id'] + '-regex',
            tag: 'span',
            cls: 'x-field-coupon-regex',
            cn: [{
                id: this.initialConfig['id'] + '-regex-input',
                tag: 'input',
                cls: 'x-form-input ' + this.regexInputCls,
                value: this.regexValue,
            }, {
                id: this.initialConfig['id'] + '-regex-button',
                tag: 'div',
                cls: 'x-form-trigger ' + this.genBtnCls,
            }],
        };
    },

    /**
     *
     * @private
     */
    _triggerGen: function () {
        if (typeof(RandExp) !== 'undefined') {
            var regex = this.getRegexValue();
            var value = (new RandExp(regex)).gen();
            this.setValue(value);
        }
        this.fireEvent('gen', this);
    },

    /**
     *
     */
    getRegexValue: function () {
        var value = '';
        var regexInput = document.getElementById(this.initialConfig['id'] + '-regex-input');
        if (typeof(regexInput) === 'object') {
            value = regexInput.value;
        }
        return value;
    },
});
Ext.reg('mspc2-field-coupon', msPromoCode2.combo.Coupon);


/**
 *
 * @param config
 * @constructor
 */
msPromoCode2.combo.DateTime = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        timePosition: 'right',
        allowBlank: true,
        hiddenFormat: 'U', // 'Y-m-d H:i:s',
        dateFormat: MODx.config['manager_date_format'],
        timeFormat: MODx.config['manager_time_format'],
        dateWidth: 120,
        timeWidth: 120,
    });
    msPromoCode2.combo.DateTime.superclass.constructor.call(this, config);
};
Ext.extend(msPromoCode2.combo.DateTime, Ext.ux.form.DateTime);
Ext.reg('mspc2-datetime', msPromoCode2.combo.DateTime);


/**
 *
 * @param config
 * @constructor
 */
msPromoCode2.combo.Resource = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        name: 'resource',
        fieldLabel: config['name'] || 'resource',
        hiddenName: config['name'] || 'resource',
        displayField: 'pagetitle',
        valueField: 'id',
        fields: ['id', 'pagetitle', 'parents'],
        url: msPromoCode2.config['connector_url'],
        baseParams: {
            action: 'mgr/combo/getresources',
            type: config['type'] || '',
            coupon: config['coupon'] || 0,
            context_key: config['context_key'] || 'web',
            parents: 1,
        },
        pageSize: 20,
        typeAhead: false,
        editable: true,
        minChars: 1,
        anchor: '100%',
        listEmptyText: '<div style="padding: 7px;">' + _('mspc2_combo_list_empty') + '</div>',
        tpl: new Ext.XTemplate('\
            <tpl for="."><div class="x-combo-list-item mspc2-combo__list-item {[values.published % 2 === 0 ? "mspc2-combo__list-item_unpublished" : ""]}">\
                <tpl if="parents">\
                    <div class="parents">\
                        <tpl for="parents">\
                            <nobr><small>{pagetitle} / </small></nobr>\
                        </tpl>\
                    </div>\
                </tpl>\
                <span>\
                    <small>{id}</small> <b>{pagetitle}</b>\
                </span>\
            </div></tpl>',
            {compiled: true}
        ),
    });
    msPromoCode2.combo.Resource.superclass.constructor.call(this, config);

    // Обновляем список при открытии
    this.on('expand', function () {
        this.getStore().load();
    }, this);
};
Ext.extend(msPromoCode2.combo.Resource, MODx.combo.ComboBox);
Ext.reg('mspc2-combo-resource', msPromoCode2.combo.Resource);


/**
 *
 * @param config
 * @constructor
 */
msPromoCode2.combo.List = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        name: 'list',
        fieldLabel: config['name'] || 'list',
        hiddenName: config['name'] || 'list',
        displayField: 'display',
        valueField: 'value',
        fields: ['value', 'display'],
        url: msPromoCode2.config['connector_url'],
        baseParams: {
            action: 'mgr/combo/getlists',
            filter: config['filter'] || 0,
            notempty: config['notempty'] || 1,
        },
        pageSize: 20,
        typeAhead: false,
        editable: true,
        anchor: '100%',
        listEmptyText: '<div style="padding: 7px;">' + _('mspc2_combo_list_empty') + '</div>',
        tpl: new Ext.XTemplate('\
            <tpl for="."><div class="x-combo-list-item mspc2-combo__list-item">\
                <span class="mspc2-combo__row-list mspc2-combo__row-{value}">\
                    {display}\
                </span>\
            </div></tpl>',
            {compiled: true}
        ),
    });
    msPromoCode2.combo.List.superclass.constructor.call(this, config);

    // Обновляем список при открытии
    this.on('expand', function () {
        this.getStore().load();
    }, this);
};
Ext.extend(msPromoCode2.combo.List, MODx.combo.ComboBox);
Ext.reg('mspc2-combo-list', msPromoCode2.combo.List);