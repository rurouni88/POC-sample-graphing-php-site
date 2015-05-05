<div id="calculations">
	<h3>Statistics</h3>
	<div>
	<form id="form_get_statistics" method="post" action="handle_action.php?mode=calculation">
		<select id="department" name="department">
			<option value="">All Departments...</option>
		</select>
	</form>
	</div>

	<div id="div_statistics">
		<table id="results_table" width="100%" border="0" cellspacing="1" cellpadding="3" class="table_form">
			<col style="width:30%">
			<col style="width:1%">
			<col style="width:69%">
		</table>
	</div>
</div>

<script type="text/javascript">
	// Build dropdowns dynamically for Form
	try {
		$(document).ready(function() {
			var mode = getURLParam('mode');
			var get_url = './handle_action.php?mode='+mode+'&value=';
			buildDropdown($('#form_get_statistics #department'), get_url+'departments');

			listenDropdown();
			 $('#form_get_statistics #department').change();
		});
	}
	catch(e) {
		console.log (e.message);
	}

	function listenDropdown() {
		$('#form_get_statistics #department').change(function() {
			var url = $('#form_get_statistics').attr('action');
			var dept = $('#form_get_statistics #department :selected').val();

			try {
				buildStatistics(dept);
			}
			catch(e) {
				console.log (e.message);
			}
		});
	}

</script>
