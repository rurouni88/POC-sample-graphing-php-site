<div id="feedback">
	<h3>Submit Feedback</h3>
	<form id="form_feedback" method="post" action="handle_action.php?mode=feedback">
		<input type='hidden' id='reset_on_success' value="1" />
		<table width="100%" border="0" cellspacing="1" cellpadding="3" class="table_form">
			<col style="width:10%">
			<col style="width:5%">
			<col style="width:85%">
			<tr>
				<td class="keyname">Subject</td>
				<td>:</td>
				<td><input name="subject" type="text" id="subject" size="50" /></td>
			</tr>
			<tr>
				<td class="keyname">Name</td>
				<td>:</td>
				<td><input name="name" type="text" id="name" size="50" /></td>
			</tr>
			<tr>
				<td class="keyname">Email</td>
				<td>:</td>
				<td><input name="email_from" type="text" id="email_from" size="50" /></td>
			</tr>
			<tr>
				<td class="keyname">Details</td>
				<td>:</td>
				<td><textarea name="details" cols="50" rows="4" id="details"></textarea></td>
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
	// AJAX - good times with JQuery :)
	var form_id = 'form_feedback';
	$('#'+form_id).submit(function(event) {
		resetFormSubmit();
		handleFormSubmit(event, form_id);
	});

	$('#'+form_id+' #reset_form').click(function(event) {
		resetFormSubmit();
	});

</script>
