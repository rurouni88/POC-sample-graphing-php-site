<div id="form_insert">
	<h3>Data Entry</h3>

	<form id="form_insert_stats" method="post" action="handle_action.php?mode=prm_insert">
		<input type='hidden' id='override_id' name='override_id' value="" />
		<table width="100%" border="0" cellspacing="1" cellpadding="3" class="table_form">
			<col style="width:10%">
			<col style="width:5%">
			<col style="width:85%">
			<tr>
				<td class="keyname">Department</td>
				<td>:</td>
				<td><select id="department" name="department">
					<option value="">Please select...</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="keyname">Month</td>
				<td>:</td>
				<td><select id="month" name="month"></select></td>
			</tr>
			<tr>
				<td class="keyname">Year</td>
				<td>:</td>
				<td><select id="year" name="year"></select></td>
			</tr>
			<tr>
				<td class="keyname">PRM Value</td>
				<td>:</td>
				<td>
					<input type="text" id="prm_value" name="prm_value" value="" />
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><input type="submit" name="Submit1" value="Submit" />
					<input id="reset_form" type="reset" name="Submit2" value="Reset" />
				</td>
			</tr>
		</table>
	</form>
</div>

<script type="text/javascript">
	// Build dropdowns dynamically for Form
	try {
		$(document).ready(function() {
			var mode = getURLParam('mode');
			var get_url = './handle_action.php?mode='+mode+'&value=';
			buildDropdown($('#form_insert_stats #department'), get_url+'departments');
			buildDropdown($('#form_insert_stats #month'), get_url+'months');
			buildDropdown($('#form_insert_stats #year'), get_url+'years');
		});

	} catch(e) {
		console.log (e.message);
	}

	// AJAX - good times with JQuery :)
	var form_id = 'form_insert_stats';
	$('#'+form_id).submit(function(event) {

		var attempt = true;
		var data = $('#'+form_id).serialize();

//		console.log(data); // Remember to disable in IE. Buggers up the AJAX
		var duplicate = checkExisting(event, data);
		if (duplicate) {
			attempt = confirm('Record Id '+duplicate.id+' already exists. Override?');

			$('#override_id').attr('value', duplicate.id);
			$('#'+form_id).attr('action', 'handle_action.php?mode=prm_edit');
		}
		else {
			$('#'+form_id).attr('action', 'handle_action.php?mode=prm_insert');
		}

		if (attempt) {
			handleFormSubmit(event, form_id);
		}
	});

	$('#'+form_id+' #reset_form').click(function(event) {
		resetFormSubmit();
	});
</script>
