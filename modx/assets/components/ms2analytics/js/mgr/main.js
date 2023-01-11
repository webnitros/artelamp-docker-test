var ms2analytics = {}
Ext.onReady(function() {
	MODx.add({
		xtype: 'ms2analytics-panel'
	})
})
ms2analytics.panel = function(config) {
	config = config || {}
	Ext.apply(config, {
		cls: 'container', // Добавляем отступы
		items: [{
			html: `<h2>${_('settings')}</h2>`,
		},
			{
				xtype: extraExt.tabs.xtype,
				items: [
					{
						id: 'basic_settings',
						title: _('area_ms2a_main'),
						layout: 'anchor',
						items: [
							{
								cls:'panel-desc',
								html:_('basic_settings_description')
							},{
								xtype: extraExt.grid.xtype,
								url: ms2analyticsConnectorUrl+'?category=basic',
								action: 'mgr/config/get',
								save_action: 'mgr/config/update',
								autosave: true,
								fields: ['id','key', 'value', 'default'],
								keyField: 'key',
								nameField: 'key',
								viewConfig: {
									forceFit: true,
									scrollOffset: 0
								},
								columns: [
									{
										hidden:true,
										dataIndex: 'id',
										header: _('id'),
										sortable: true,
										extraExtEditor: {
											visible: false,
										},
										renderer: extraExt.grid.renderers.default
									},
									{
										dataIndex: 'key',
										header: _('key'),
										sortable: true,
										extraExtEditor: {
											visible: false,
										},
										renderer: extraExt.grid.renderers.default
									},
									{
										dataIndex: 'value',
										header: _('value'),
										sortable: true,
										extraExtEditor: {
											xtype: 'textarea'
										},
										editor: {
											xtype: 'textfield'
										},
										renderer: extraExt.grid.renderers.default
									},
									{
										dataIndex: 'default',
										header: _('tv_default'),
										sortable: false,
										extraExtEditor: {
											visible: false,
										},
										renderer: extraExt.grid.renderers.default
									},
								],
								paging: true,

							}]
					},
					{
						id: 'product_settings',
						title: _('area_ms2a_product'),
						layout: 'anchor',
						items: [
							{
								cls:'panel-desc',
								html:_('product_settings_description')
							},
							{
								xtype: extraExt.grid.xtype,
								url: ms2analyticsConnectorUrl+'?category=product',
								action: 'mgr/config/get',
								save_action: 'mgr/config/update',
								autosave: true,
								fields: ['id','key', 'value', 'default'],
								keyField: 'key',
								nameField: 'key',
								viewConfig: {
									forceFit: true,
									scrollOffset: 0
								},
								columns: [
									{
										hidden:true,
										dataIndex: 'id',
										header: _('id'),
										sortable: true,
										extraExtEditor: {
											visible: false,
										},
										renderer: extraExt.grid.renderers.default
									},
									{
										dataIndex: 'key',
										header: _('key'),
										sortable: true,
										extraExtEditor: {
											visible: false,
										},
										renderer: extraExt.grid.renderers.default
									},
									{
										dataIndex: 'value',
										header: _('value'),
										sortable: true,
										extraExtEditor: {
											xtype: 'textarea'
										},
										editor: {
											xtype: 'textfield'
										},
										renderer: extraExt.grid.renderers.default
									},
									{
										dataIndex: 'default',
										header: _('tv_default'),
										sortable: false,
										extraExtEditor: {
											visible: false,
										},
										renderer: extraExt.grid.renderers.default
									},
								],
								paging: true,

							}]
					},
					{
						id: 'order_settings',
						title: _('area_ms2a_order'),
						layout: 'anchor',
						items: [
							{
								cls:'panel-desc',
								html:_('order_settings_description')
							},
							{
								xtype: extraExt.grid.xtype,
								url: ms2analyticsConnectorUrl+'?category=order',
								action: 'mgr/config/get',
								save_action: 'mgr/config/update',
								autosave: true,
								fields: ['id','key', 'value', 'default'],
								keyField: 'key',
								nameField: 'key',
								viewConfig: {
									forceFit: true,
									scrollOffset: 0
								},
								columns: [
									{
										hidden:true,
										dataIndex: 'id',
										header: _('id'),
										sortable: true,
										extraExtEditor: {
											visible: false,
										},
										renderer: extraExt.grid.renderers.default
									},
									{
										dataIndex: 'key',
										header: _('key'),
										sortable: true,
										extraExtEditor: {
											visible: false,
										},
										renderer: extraExt.grid.renderers.default
									},
									{
										dataIndex: 'value',
										header: _('value'),
										sortable: true,
										extraExtEditor: {
											xtype: 'textarea'
										},
										editor: {
											xtype: 'textfield'
										},
										renderer: extraExt.grid.renderers.default
									},
									{
										dataIndex: 'default',
										header: _('tv_default'),
										sortable: false,
										extraExtEditor: {
											visible: false,
										},
										renderer: extraExt.grid.renderers.default
									},
								],
								paging: true,

							}]
					},
					{
						id: 'yandex_settings',
						title: _('area_ms2a_yandex'),
						layout: 'anchor',
						items: [
							{
								cls:'panel-desc',
								html:_('yandex_settings_description')
							},
							{
								xtype: extraExt.grid.xtype,
								url: ms2analyticsConnectorUrl+'?category=yandex',
								action: 'mgr/config/get',
								save_action: 'mgr/config/update',
								autosave: true,
								fields: ['id','key', 'value', 'default'],
								keyField: 'key',
								nameField: 'key',
								viewConfig: {
									forceFit: true,
									scrollOffset: 0
								},
								columns: [
									{
										hidden:true,
										dataIndex: 'id',
										header: _('id'),
										sortable: true,
										extraExtEditor: {
											visible: false,
										},
										renderer: extraExt.grid.renderers.default
									},
									{
										dataIndex: 'key',
										header: _('key'),
										sortable: true,
										extraExtEditor: {
											visible: false,
										},
										renderer: extraExt.grid.renderers.default
									},
									{
										dataIndex: 'value',
										header: _('value'),
										sortable: true,
										extraExtEditor: {
											xtype: 'textarea'
										},
										editor: {
											xtype: 'textfield'
										},
										renderer: extraExt.grid.renderers.default
									},
									{
										dataIndex: 'default',
										header: _('tv_default'),
										sortable: false,
										extraExtEditor: {
											visible: false,
										},
										renderer: extraExt.grid.renderers.default
									},
								],
								paging: true,

							}]
					},
				]
			}]
	})
	ms2analytics.panel.superclass.constructor.call(this, config) // Чёртова магия =)
}
Ext.extend(ms2analytics.panel, MODx.Panel)
Ext.reg('ms2analytics-panel', ms2analytics.panel)

var ModGrid = function(config) { // Придумываем название, например, «Names»
	ModGrid.superclass.constructor.call(this, config) // Магия
}
Ext.extend(ModGrid, MODx.grid.Grid) // Наша табличка расширяет GridPanel
Ext.reg('ModGrid', ModGrid) // Регистрируем новый xtype