<div id="graph">
	<h3>Graphs</h3>
	<div id="canvasPRMData_BarGraph" style="z-index:10; width:auto; height:30%;">[Building Graph]</div>
	<div style="display:block"><p></p></div>
	<div id="canvasPRMData_LineGraph" style="z-index:10; width:auto; height:30%;">[Building Graph]</div>
	<div style="display:block"><p></p></div>
	<div id="canvasPRMData_PieChart" style="z-index:10; width:auto; height:30%;">[Building Graph]</div>
</div>


<script type="text/javascript">
	try {
		$(document).ready(function() {
			var graph = new RPHGraph();
			graph.execute();
		});
	} catch(e) {
		console.log (e.message);
	}
</script>
