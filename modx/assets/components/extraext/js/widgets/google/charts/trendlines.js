extraExt.create(
	extraExt.google.charts.trendlines.xtype,
	function(config) {
		this.options = {}
		this.options.trendlines = {0: {}}
		this.options.legend = 'none'
		extraExt.xTypes[extraExt.google.charts.trendlines.xtype].superclass.constructor.call(this, config) // Магия
	},
	extraExt.xTypes[extraExt.google.charts.line.xtype],
	[
		{
			draw: function() {
				this.google.chart = new google.visualization.ScatterChart(document.getElementById(this.chartBodyId))
				this.google.chart.draw(this.google.data, this.options)
			},
		},
	]
)
