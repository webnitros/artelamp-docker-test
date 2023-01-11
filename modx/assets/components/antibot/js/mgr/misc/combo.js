antiBot.combo.Search = function (config) {
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
    antiBot.combo.Search.superclass.constructor.call(this, config);
    this.on('render', function () {
        this.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
            this._triggerSearch();
        }, this);
    });
    this.addEvents('clear', 'search');
};
Ext.extend(antiBot.combo.Search, Ext.form.TwinTriggerField, {

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
Ext.reg('antibot-combo-search', antiBot.combo.Search);
Ext.reg('antibot-field-search', antiBot.combo.Search);




/**
 * Methods
 */
antiBot.combo.Methods = function (config) {
    // Расширяем старый конфиг
    Ext.applyIf(config, {
        emptyText: _('antibot_combo_select_method'),
        hideMode: 'offsets',
        baseParams: {
            action: 'mgr/misc/method/getlist',
            combo: true,
            addall: config.addall || 0
        },
    });
    config = antiBot.combo.Default.call(this,config);
    antiBot.combo.Default.superclass.constructor.call(this, config);
}
Ext.extend(antiBot.combo.Methods, antiBot.combo.Default)
Ext.reg('antiBot-combo-methods', antiBot.combo.Methods)


/**
 * CodeResponse
 */
antiBot.combo.CodeResponse = function (config) {
    // Расширяем старый конфиг
    Ext.applyIf(config, {
        emptyText: _('antibot_combo_select_code_response'),
        baseParams: {
            action: 'mgr/misc/coderesponse/getlist',
            combo: true,
            addall: config.addall || 0
        },
        cls: 'input-combo-antibot-code-response',
    });
    config = antiBot.combo.Default.call(this,config);
    antiBot.combo.Default.superclass.constructor.call(this, config);
}
Ext.extend(antiBot.combo.CodeResponse, antiBot.combo.Default)
Ext.reg('antiBot-combo-code-response', antiBot.combo.CodeResponse)

/**
 * Bot
 */
antiBot.combo.Bot = function (config) {
    // Расширяем старый конфиг
    Ext.applyIf(config, {
        emptyText: _('antibot_bots_change'),
        baseParams: {
            action: 'mgr/misc/bot/getlist',
            combo: true,
            addall: config.addall || 0
        },
    });
    config = antiBot.combo.Default.call(this,config);
    antiBot.combo.Default.superclass.constructor.call(this, config);
}
Ext.extend(antiBot.combo.Bot, antiBot.combo.Default)
Ext.reg('antiBot-combo-bots', antiBot.combo.Bot)




/**
 * Guest
 */
antiBot.combo.Guest = function (config) {
    // Расширяем старый конфиг
    Ext.applyIf(config, {
        emptyText: _('antibot_guest_change'),
        minChars: 1,
        baseParams: {
            action: 'mgr/misc/guest/getlist',
            combo: true,
            addall: config.addall || 0
        },
        tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item">',
            '({value})<b>{name}</b></span>',
            '</div></tpl>', {
                compiled: true
            }),
    });
    config = antiBot.combo.Default.call(this,config);
    antiBot.combo.Default.superclass.constructor.call(this, config);
}
Ext.extend(antiBot.combo.Guest, antiBot.combo.Default)
Ext.reg('antiBot-combo-guest', antiBot.combo.Guest)


