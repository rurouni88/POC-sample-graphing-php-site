<noscript>
	Warning: This page needs JavaScript activated to work.
</noscript>

<div id="content_wrapper">
<?php
	$mode = (isset($_GET["mode"])) ? $_GET["mode"] : false;
	switch ($mode) {
		case 'form':
			include('template/content/form.php');
			break;
		case 'calculations':
			include('template/content/calculations.php');
			break;
		case 'graph':
			include('template/content/graph.php');
			break;
		case 'feedback':
			include('template/content/feedback.php');
			break;
		case 'about':
			include('template/content/about.php');
			break;
		case 'test':
			include('test.php');
			break;
		default:
			include('template/content/home.php');
			break;
	}
?>
<div style="display: block;"><br /></div>

	<!--SITE MESSAGE OBJECT displays out here -->
	<div id="sm_result" style="display:none;">
		<div id="sm_success"></div>
		<div id="sm_message" class="success"></div>
		<div id="sm_errors" class="error"></div>
	</div>
</div>
