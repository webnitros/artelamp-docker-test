extraExt.create(
	extraExt.google.charts.annotation.xtype,
	function(config) {
		this.options = {}
		extraExt.xTypes[extraExt.google.charts.annotation.xtype].superclass.constructor.call(this, config) // Магия
		this.packages = ['annotationchart']
	},
	extraExt.xTypes[extraExt.google.charts.line.xtype],
	[
		{
			draw: function() {
				this.google.chart = new google.visualization.AnnotationChart(document.getElementById(this.chartBodyId))
				this.google.chart.draw(this.google.data, this.options)
			},
		},
	]
)