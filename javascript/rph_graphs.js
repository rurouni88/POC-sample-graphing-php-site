/*
	Javascript functions to generate RPH Statistics Graphs
	Requires the following libraries
		JQuery of some sorts
		highcharts.js
*/

function RPHGraph() {
	this.url = './handle_action.php?mode=get&value=prm_data';
}

RPHGraph.prototype.execute = function() {
	var json = $.getJSON(this.url, function(cache) {
		var data = cache.data;
		var year = new Date().getFullYear();

		graphBarGraph_YTD(data.dept_ytd_prm, year);
		graphLineGraph_YTD(data.dept_ytd_prm, year);
		graphPRMData_PieChart(data.dept_breakdown, year);
	});
}

// Graph a Pie Chart of breakdown by department for YTD
function graphPRMData_PieChart (data, year) {
	var breakdown = new Array();
	$.each(data, function(index, value) {
		breakdown.push([ index, value ]);
	});

	var chart = new Highcharts.Chart({
		chart: {
			renderTo: 'canvasPRMData_PieChart',
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false
		},
		title: {
			text: 'PRM Record Breakdown by Department for '+year
		},
		tooltip: {
//			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			pointFormat: '<strong>{point.y} RPMs<strong></strong>'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					color: '#000000',
					connectorColor: '#000000',
					format: '<b>{point.name}</b>: {point.percentage:.1f} %'
				}
			}
		},
		series: [{
			type: 'pie',
			name: 'PRM Record Breakdown for '+ year,
			data: breakdown
		}]
	});

}

// Graph a Bar Graph of breakdown by department for YTD
function graphBarGraph_YTD (records, year) {

	var series_array = new Array();

	$.each(records, function(dept_name, mtd_data) {
		var tmp = new Array();
		tmp['name'] = dept_name;
		tmp['data'] = mtd_data;
		series_array.push( tmp );
	});

	var chart = new Highcharts.Chart({
		chart: {
			renderTo: 'canvasPRMData_BarGraph',
			type: 'column',
			zoomType: 'xy'
		},
		title: {
			text: 'Monthly PRM Returns for '+ year
		},
		subtitle: {
			text: 'for RPH'
		},
		xAxis: {
			categories: [
				'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
				'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
			]
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Total Number of PRMs'
			}
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
				'<td style="padding:0"><b>{point.y:.1f} PRMs</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
		},
		plotOptions: {
			column: {
				pointPadding: 0.2,
				borderWidth: 0
			}
		},
		series: series_array
	});
}

// Graph a Line Graph of breakdown by department for YTD
function graphLineGraph_YTD (records, year) {
	var series_array = new Array();

	$.each(records, function(dept_name, mtd_data) {
		var tmp = new Array();
		tmp['name'] = dept_name;
		tmp['data'] = mtd_data;
		series_array.push( tmp );
	});

	var chart = new Highcharts.Chart({
		chart: {
			renderTo: 'canvasPRMData_LineGraph',
			type: 'line',
			zoomType: 'xy',
			marginRight: 130,
			marginBottom: 25
		},
		title: {
			text: 'Monthly PRM Returns for '+ year,
			x: -20 //center
		},
		subtitle: {
			text: 'for RPH',
			x: -20
		},
		xAxis: {
			categories: [
				'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
				'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
			]
		},
		yAxis: {
			title: {
				text: 'Total Number of PRMs'
			},
			plotLines: [{
				value: 0,
				width: 1,
				color: '#808080'
			}]
		},
		tooltip: {
			valueSuffix: 'RPMs'
		},
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'top',
			x: -10,
			y: 100,
			borderWidth: 0
		},
		series: series_array
	});
}
