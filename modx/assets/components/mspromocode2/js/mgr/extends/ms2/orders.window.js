/**
 *
 */
Ext.ComponentMgr.onAvailable('minishop2-window-order-update', function () {
    var w = this;
    var order_id = w.record['id'] || 0;
    var tabs = w.fields.items;

    // console.log('msPromoCode2 w', w);
    // console.log('msPromoCode2 tabs', tabs);

    //
    var mainTabItems = [];
    tabs[0].items.forEach(function (row) {
        if (row.xtype === 'fieldset' && row.items[0].items.some(function (item) {
            return item.name === 'num';
        })) {
            var add = {
                xtype: 'mspc2-order-fieldset',
                id: w['id'] + '-mspc2',
                order: order_id,
            };
            // row['style'] = {
            //     padding: '0 5px',
            //     marginTop: '10px',
            //     textAlign: 'center',
            // };
        }

        // Push
        if (typeof(add) !== 'undefined') {
            mainTabItems.push(add);
        }
        mainTabItems.push(row);
    });
    tabs[0].items = mainTabItems;

    //
    tabs.forEach(function (tab) {
        if (tab['xtype'] === 'minishop2-grid-order-products') {
            tab['listeners'] = typeof(tab['listeners']) === 'object' ? tab['listeners'] : {};
            tab.listeners['show'] = function (grid) {
                grid && grid.refresh();
            }
        }
    });

    // //
    // w.on('beforerender', function () {
    //     console.log('msPromoCode2 window beforerender w', w);
    // });
});