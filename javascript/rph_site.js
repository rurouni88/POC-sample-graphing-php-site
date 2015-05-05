/*
	Custom Javascript Functions for RPH Site
	All the AJAX stuff
	* $.ajax() will be used to handle POSTs
	* $.getJSON() will be used to handle GETs
*/

function getURLParam (keyname){
	var results = new RegExp('[\\?&]' + keyname + '=([^&#]*)').exec(window.location.href);
	return results[1] || 0;
}

function buildDropdown (select, url) {
	$.getJSON(url, function(cache) {
		var data = cache.data;
		$.each(data, function(index, object) {
			select.append($("<option/>", {
				value: object.value,
				text:  object.display,
				style: "width:100px;"
			}));
		});
	});
}

function buildSiteNavigation () {
	var url = './handle_action.php?mode=get&value=site_nav';
	$.getJSON(url, function(cache) {
		var data = cache.data;
		$.each(data, function(index, object) {
			href = (object.path)
				? object.path
				: 'index.php?mode=' + object.display.toLowerCase();
			$('#site_navigation').append(
				$('<li>').append(
					$('<a>').attr('href', href).append(
						object.display
			)));
		});
	});
}

function buildStatistics(dept) {
	var url = './handle_action.php?mode=get&value=prm_data&dept='+dept;

	$.blockUI();

	$.getJSON(url, function(cache) {
		// Reset the entire table
		$("#results_table tr").remove(); 

		// Output the statistics object
		var object = cache.data;
		$('#results_table').append('<tr><td class="keyname">PRM Record Count</td><td>:</td><td>'+object.count+'</td></tr>');
		$('#results_table').append('<tr><td class="keyname">Total PRM</td><td>:</td><td>'+object.total+'</td></tr>');
		$('#results_table').append('<tr><td class="keyname">Lowest PRM</td><td>:</td><td>'+object.minimum+'</td></tr>');
		$('#results_table').append('<tr><td class="keyname">Highest PRM</td><td>:</td><td>'+object.maximum+'</td></tr>');
		$('#results_table').append('<tr><td class="keyname">Lowest PRM Month</td><td>:</td><td>'+object.lowest_dates+'</td></tr>');
		$('#results_table').append('<tr><td class="keyname">Highest PRM Month</td><td>:</td><td>'+object.highest_dates+'</td></tr>');
		$('#results_table').append('<tr><td class="keyname">Average PRM</td><td>:</td><td>'+object.mean+'</td></tr>');
		$('#results_table').append('<tr><td class="keyname">PRM Median Value</td><td>:</td><td>'+object.median+'</td></tr>');
		$('#results_table').append('<tr><td class="keyname">PRM Most Reoccurring Value</td><td>:</td><td>'+object.mode+'</td></tr>');
		$('#results_table').append('<tr><td class="keyname">PRM Range</td><td>:</td><td>'+object.range+'</td></tr>');

		// Department wide statistics
		if (object.dept_highest && object.dept_lowest) {
			$('#results_table').append('<tr><td class="keyname">Dept Recording Highest PRM</td><td>:</td><td>'+object.dept_highest+'</td></tr>');
			$('#results_table').append('<tr><td class="keyname">Dept Recording Lowest PRM</td><td>:</td><td>'+object.dept_lowest+'</td></tr>');
		}

		$.unblockUI();
	});
}

// Generic function to handle Form POSTS using JQuery AJAX
// Events get displayed to sm_result div which should be a direct dump of
// my SiteMessage object class
function handleFormSubmit (event, form_id) {
	// Lockdown the default event
	event.preventDefault();

	$.blockUI();

	// JQuery AJAX call
	$.ajax({
		url:  $('#'+form_id).attr('action'),
		type: 'POST',
		data: $('#'+form_id).serialize(),
		cache: false,
		success: function(result){
			try {
				$.unblockUI();

				var site_message = $.parseJSON(result);
				$('#sm_result').show();
				$('#sm_result #sm_success').text(site_message.success);
				$('#sm_result #sm_success').hide();

				// Display Errors if success isn't happening
				if (!site_message.success) {
					$('#sm_result #sm_message').hide();
					$('#sm_result #sm_errors').show();
					$('#sm_result #sm_errors').text('ERRORS:').append('<br />');
					$.each(site_message.errors, function(index, error_string) {
						$('#sm_result #sm_errors').append(error_string + '<br />');
					});
				}
				else {
					$('#sm_result #sm_errors').hide();
					$('#sm_result #sm_message').show();
					$('#sm_result #sm_message').text(site_message.message);

					if ($('#'+form_id+' #reset_on_success').val()) {
						$('#'+form_id).each(function(){
							this.reset();
						});
					}
				}
			}
			catch(exception) {
				console.log(exception);
				console.log(result);
			}  
		}
	});

	return false;
}

// Additional events upon form reset
function resetFormSubmit() {

	$('#sm_result').hide();
	$('#sm_result #sm_success').empty();
	$('#sm_result #sm_message').empty();
	$('#sm_result #sm_errors').empty();

	return false;
}

// Make an AJAX call to check whether a record is existing
// Needs to be synchronous to trigger off corresponding events
function checkExisting(event, data) {
	event.preventDefault();

	var item;

	$.ajax({
		url: './handle_action.php?mode=check_existing&value=prm',
		type: 'POST',
		data: data,
		async: false,
		cache: false,
		success: function(result){
			var object;
			try {
				object = $.parseJSON(result);

				if(object.success) {
					item = object.item;
				}
			}
			catch(exception) {
				console.log(exception);
				console.log(result);
			}
		}
	});

	return item;
}

