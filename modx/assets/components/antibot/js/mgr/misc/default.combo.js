antiBot.combo.Default = function (config) {
    config = config || {}
    if (config.custm) {
        config.triggerConfig = [{
            tag: 'div',
            cls: 'x-field-search-btns',
            style: String.format('width: {0}px;', config.clear ? 62 : 31),
            cn: [{
                tag: 'div',
                cls: 'x-form-trigger x-field-antibot-active-go'
            }]
        }]
        if (config.clear) {
            config.triggerConfig[0].cn.push({
                tag: 'div',
                cls: 'x-form-trigger x-field-antibot-active-clear'
            })
        }

        config.initTrigger = function () {
            var ts = this.trigger.select('.x-form-trigger', true)
            this.wrap.setStyle('overflow', 'hidden')
            var triggerField = this
            ts.each(function (t, all, index) {
                t.hide = function () {
                    var w = triggerField.wrap.getWidth()
                    this.dom.style.display = 'none'
                    triggerField.el.setWidth(w - triggerField.trigger.getWidth())
                }
                t.show = function () {
                    var w = triggerField.wrap.getWidth()
                    this.dom.style.display = ''
                    triggerField.el.setWidth(w - triggerField.trigger.getWidth())
                }
                var triggerIndex = 'Trigger' + (index + 1)

                if (this['hide' + triggerIndex]) {
                    t.dom.style.display = 'none'
                }
                t.on('click', this['on' + triggerIndex + 'Click'], this, {
                    preventDefault: true
                })
                t.addClassOnOver('x-form-trigger-over')
                t.addClassOnClick('x-form-trigger-click')
            }, this)
            this.triggers = ts.elements
        }
    }
    Ext.applyIf(config, {
        url: antiBot.config.connectorUrl,
        name: config.name || 'method',
        hiddenName: config.name || 'method',
        displayField: 'name',
        valueField: 'value',
        editable: true,
        fields: ['name', 'value'],
        pageSize: 10,
        emptyText: _('antibot_combo_select_method'),
        hideMode: 'offsets',
        baseParams: {
            action: 'mgr/misc/default/getlist',
            combo: true,
            addall: config.addall || 0
        },
        tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item">',
            '<b>{name}</b></span>',
            '</div></tpl>', {
                compiled: true
            }),
        cls: 'input-combo-antibot-default',
        clearValue: function () {
            if (this.hiddenField) {
                this.hiddenField.value = ''
            }
            this.setRawValue('')
            this.lastSelectionText = ''
            this.applyEmptyText()
            this.value = ''
            this.fireEvent('select', this, null, 0)
        },

        getTrigger: function (index) {
            return this.triggers[index]
        },

        onTrigger1Click: function () {
            this.onTriggerClick()
        },

        onTrigger2Click: function () {
            this.clearValue()
        }
    })
    return config
}
Ext.extend(antiBot.combo.Default, MODx.combo.ComboBox)
Ext.reg('antibot-combo-default', antiBot.combo.Default);

