extraExt.create(
	extraExt.google.charts.gauge.xtype,
	function(config) {
		Ext.applyIf(config, {
			options: {
				width: 400, height: 120,
				redFrom: 90, redTo: 100,
				yellowFrom: 75, yellowTo: 90,
				minorTicks: 5
			},
			autoUpdateInterval: 2500,
			autoUpdate: false,
		})
		extraExt.xTypes[extraExt.google.charts.gauge.xtype].superclass.constructor.call(this, config) // Магия
		this.packages = ['gauge']
	},
	extraExt.xTypes[extraExt.google.charts.line.xtype],
	[
		{
			draw: function() {
				this.google.chart = new google.visualization.Gauge(document.getElementById(this.chartBodyId))
				this.google.chart.draw(this.google.data, this.options)
			},
		},
	]
)