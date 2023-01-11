if ('minishop2-grid-order-logs' in Ext.ComponentMgr['types']) {
    msPromoCode2.grid.ms2OrderLogs = function (config) {
        // console.log('config', config);

        Ext.applyIf(config, {
            url: msPromoCode2.config['connector_url'],
            baseParams: {
                action: 'mgr/orders/getlog',
                order_id: config['order_id'],
                // type: 'status',

                // sort: 'timestamp',
                // dir: 'DESC',
            },
        });
        msPromoCode2.grid.ms2OrderLogs.superclass.constructor.call(this, config);
    };
    Ext.extend(msPromoCode2.grid.ms2OrderLogs, Ext.ComponentMgr.types['minishop2-grid-order-logs'], {});
    Ext.reg('minishop2-grid-order-logs', msPromoCode2.grid.ms2OrderLogs);
}