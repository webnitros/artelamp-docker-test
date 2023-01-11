extraExt.create(
	extraExt.google.charts.pie.xtype,
	function(config) {
		extraExt.xTypes[extraExt.google.charts.pie.xtype].superclass.constructor.call(this, config) // Магия
	},
	extraExt.xTypes[extraExt.google.charts.line.xtype],
	[
		{
			draw: function() {
				this.google.chart = new google.visualization.PieChart(document.getElementById(this.chartBodyId))
				this.google.chart.draw(this.google.data, this.options)
			},
		},
	]
)