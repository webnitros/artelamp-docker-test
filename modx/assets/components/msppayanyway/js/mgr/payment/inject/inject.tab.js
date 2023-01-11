Ext.override(miniShop2.window.UpdatePayment, {

	msppayanywayOriginals: {
		getFields: miniShop2.window.UpdatePayment.prototype.getFields
	},

	getFields: function (config) {
		var fields = this.msppayanywayOriginals.getFields.call(this, config);

		if (!msppayanyway.tools.inArray(config.record.id, msppayanyway.config.miniShop2.payment.ids)) {
			return fields;
		}
		
		var tabs = this.msppayanywayGetTabs(config);

		fields.filter(function (row) {
			if (row.xtype == 'modx-tabs') {
				row.items.push(tabs);
			}
		});

		return fields;

	},

	msppayanywayGetTabs: function (config) {
		var tabs = [];

		var add = {
			add: {
				bodyStyle: 'margin: 5px 0;',
				items: [{
					layout: 'column',
					items: [{
						columnWidth: 1,
						layout: 'form',
						defaults: {msgTarget: 'under', anchor: '100%'},
						items: [/*{
							xtype: 'xcheckbox',
							hideLabel: true,
							boxLabel: _('msppayanyway_properties'),
							name: '_properties',
							checked: false,
							listeners: {
								check: msppayanyway.tools.handleChecked,
								afterrender: msppayanyway.tools.handleChecked
							}
						},*/ {
							xtype: 'textarea',
							fieldLabel: _('msppayanyway_properties'),
							msgTarget: 'under',
							name: 'properties',
							height:'110',
							allowBlank: true,
							setValue: function(value) {
								MODx.Ajax.request({
									url: miniShop2.config.connector_url,
									params: {
										action: 'mgr/settings/payment/get',
										id: config.record.id
									},
									listeners: {
										success: {
											fn: function (response) {
												value = response.object.properties || {};
												return Ext.form.TextField.superclass.setValue.call(this, Ext.util.JSON.encode(value));
											},
											scope: this
										},
										failure: {
											fn: function (response) {
												value = {};
												return Ext.form.TextField.superclass.setValue.call(this, Ext.util.JSON.encode(value));
											},
											scope: this
										}
									}
								});
							}
						}]
					}]
				}]
			}
		};

		msppayanyway.config.inject_payment_tabs.filter(function (tab) {
			if (add[tab]) {
				Ext.applyIf(add[tab], {
					title: _('msppayanyway_tab_' + tab)
				});
				tabs.push(add[tab]);
			}
		});

		return tabs;
	}

});