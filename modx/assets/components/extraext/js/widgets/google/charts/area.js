extraExt.create(
	extraExt.google.charts.area.xtype,
	function(config) {
		extraExt.xTypes[extraExt.google.charts.area.xtype].superclass.constructor.call(this, config) // Магия
	},
	extraExt.xTypes[extraExt.google.charts.line.xtype],
	[
		{
			draw: function() {
				this.google.chart = new google.visualization.AreaChart(document.getElementById(this.chartBodyId))
				this.google.chart.draw(this.google.data, this.options)
			},
		},
	]
)