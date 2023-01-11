extraExt.create(
	extraExt.google.charts.line.xtype,
	function(config) {
		config = config || {}
		config = Ext.applyIf(config, {
			cls: '',
			layout: 'anchor',
			baseParams: {},
			columns: {},
			leftTbar: [],
			leftBbar: [],
			rightTbar: [],
			rightBbar: [],
			updateBtn: true,
			dateFormat: false,
			anchor: '99.8%',
			height: window.innerHeight / 2,
			data: false,
			action: false,
			options: {
				title: '',
				curveType: 'function',
				legend: {position: 'bottom'},
			},
			autoUpdateInterval: 2500,
			autoUpdate: false,
			url: MODx.config.connector_url,
		})
		if(config.updateBtn) {
			config.leftBbar.push({
				xtype: 'button',
				text: '<i class="fad fa-sync-alt"></i>',
				listeners: {
					click: () => {
						this.refresh()
					}
				}
			})
		}
		config.options.title = config.title
		config.cls = 'extraExt-google-chart' + config.cls
		config.tbar = this.getTopBar.call(this, config)
		config.bbar = this.getBotBar.call(this, config)
		this.chartBodyId = Ext.id()
		var html = `<div class="extraExt-chartBody" id="${this.chartBodyId}"></div>`
		config.html = html
		this.html = html
		extraExt.xTypes[extraExt.google.charts.line.xtype].superclass.constructor.call(this, config) // Магия
		this.reload()
		this.options.width = this.width - 10
		this.options.height = this.height - 100
		this.packages = ['corechart', 'line']

	},
	Ext.Panel,
	[
		{
			store: {},
			initialId: 0,
			google: {
				data: null,
				chart: null,
				load: false,
			},
			load: false,
			restore: function() {
				var self = this
				try {
					this.store.reader.jsonData.results.data = null
					document.getElementById(this.chartBodyId).innerHTML = ''
				} catch(e) {

				}
				this.store = new Ext.data.Store({
					baseParams: Object.assign({
						action: self.action
					}, self.baseParams),
					url: self.url,
					storeId: 'extraExt-chart',
					autoLoad: true,
					autoDestroy: true,
					reader: new Ext.data.JsonReader({
						totalProperty: 'total',
						root: 'results',
						fields: ['data', 'columns']

					}),
					listeners: {
						load: function() {
							self.dataload = true
							self.drawChart()
						}
					}
				})
			},
			reload: function() {
				var self = this
				self.dataload = false

				if(!this.mode) {
					if(!this.data && this.action) {
						this.mode = 'store'
					} else {
						this.mode = 'data'
					}
				}
				if(this.mode === 'store') {
					this.restore()
				} else {
					this.dataload = true
					this.drawChart()
				}
			},
			refresh: function() {
				try {
					this.reload()
				} catch(e) {
					console.warn(e)
				}
			},
			getData: function() {
				try {
					if(this.data && this.mode == 'data') {

					} else if(this.mode == 'store') {
						this.store.response = this.store.reader.jsonData.results
						if(this.store.reader.jsonData.results.hasOwnProperty('dateFormat')) {
							this.dateFormat = this.store.reader.jsonData.results.dateFormat
						}
						if(this.store.reader.jsonData.results.hasOwnProperty('columns')) {
							this.columns = this.store.reader.jsonData.results.columns
						}
						if(this.store.reader.jsonData.results.hasOwnProperty('data')) {
							this.data = this.store.reader.jsonData.results.data
						}
					}
					if(this.dateFormat) {
						var i = 0
						for(const columnsKey in this.columns) {
							if(typeof this.columns[columnsKey] == 'string' && this.columns[columnsKey].toLowerCase() === 'date') {
								for(const dataKey in this.data) {
									this.data[dataKey][i] = moment(this.data[dataKey][i], this.dateFormat).toDate()
								}
							}
							i++
						}

					}
					// console.log(this.xtype,this)
					return this.data
				} catch(e) {
					return false
				}
			},
			send: function() {},
			afterRender: function() {
				Ext.Panel.superclass.afterRender.call(this)
				this.charRender()
			},
			charRender: function() {
				google.charts.load('current', {'packages': this.packages})
				google.charts.setOnLoadCallback(() => {
					this.google.load = true
				})
				this.interval = setInterval(() => {
					if(this.google.load && this.dataload) {
						try {
							this.init()
						} catch(e) {
							console.warn(e)
						}
						clearInterval(this.interval)
					}
				}, 50)
			},
			init() {
				try {
					this.drawChart()
					if(this.autoUpdate) {
						this.autoUpdateFn()
					}
				} catch(e) {
					console.warn(e)
				}
			},
			drawChart() {
				try {
					var data = this.getData()
					if(data) {
						this.google.data = new google.visualization.DataTable()
						for(const columnsKey in this.columns) {
							this.google.data.addColumn(this.columns[columnsKey], columnsKey)
						}
						this.google.data.addRows(data)
						this.draw()
					} else {
						document.getElementById(this.chartBodyId).innerHTML = ''
					}
				} catch(e) {
					console.warn(e)
				}
			},
			draw: function() {
				this.google.chart = new google.charts.Line(document.getElementById(this.chartBodyId))
				this.google.chart.draw(this.google.data, google.charts.Line.convertOptions(this.options))
			},
			getTopBar: function(config) {
				var tbar = []
				for(const leftTbarKey in config.leftTbar) {
					if(config.leftTbar.hasOwnProperty(leftTbarKey)) {
						tbar.push(config.leftTbar[leftTbarKey])
					}
				}
				tbar.push('->')
				for(const rightTbarKey in config.rightTbar) {
					if(config.rightTbar.hasOwnProperty(rightTbarKey)) {
						tbar.push(config.rightTbar[rightTbarKey])
					}
				}
				return tbar
			},
			getBotBar: function(config) {
				var bbar = []
				for(const leftBbarKey in config.leftBbar) {
					if(config.leftBbar.hasOwnProperty(leftBbarKey)) {
						bbar.push(config.leftBbar[leftBbarKey])
					}
				}
				bbar.push('->')
				for(const rightBbarKey in config.rightBbar) {
					if(config.rightBbar.hasOwnProperty(rightBbarKey)) {
						bbar.push(config.rightBbar[rightBbarKey])
					}
				}
				return bbar
			},
			autoUpdateFn: function() {
				if(this.initialId) {
					clearInterval(this.initialId)
				}
				this.initialId = setInterval(() => {
					this.refresh()
				}, this.autoUpdateInterval)

			}
		},
	]
)
