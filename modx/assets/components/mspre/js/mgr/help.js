mspre.panel.All = function (config) {

    config = config || {}
    Ext.apply(config, {
        title: '',
        baseCls: 'modx-formpanel',
        cls: 'mspre-formpanel',
        header: true,
        standardSubmit: true,
        buttons: this.getButtons(config),
        layout: 'anchor',
        hideMode: 'offsets',
        items: [{
            xtype: 'modx-tabs',
            id: 'mspre-panel-home-tabs',
            defaults: {
                border: false,
                autoHeight: true
            },
            border: true,
            hideMode: 'offsets',
            //items: this.getItems()
        }]
    })
    mspre.panel.All.superclass.constructor.call(this, config)

}
//Ext.extend(mspre.panel.All, MODx.Panel)
Ext.extend(mspre.panel.All, MODx.Panel, {
    getButtons: function (config) {
        var buttons = [];

        buttons.push({
            text: '<i class="icon icon-arrow-right"></i>  ' + _('mspre_panel_resource'),
            handler: function () {
                MODx.loadPage('index.php?a=resource&namespace=mspre')
            }
        })
        buttons.push({
            text: '<i class="icon icon-arrow-right"></i>  ' + _('mspre_panel_product'),
            handler: function () {
                MODx.loadPage('index.php?a=product&namespace=mspre')
            }
        })

        return buttons
    },
})
Ext.reg('mspre-panel-all', mspre.panel.All)