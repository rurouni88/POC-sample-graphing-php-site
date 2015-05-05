<?php

require_once('includes/functions.php');

// Routing script

// All post functions are expected to use API namespace classes
// This is because API namespace performs logging by virtue of KLogger
// implemented in the API_OO class.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ($_GET['mode']) {
		$site_message = '';
		switch($_GET['mode']) {
			case 'prm_insert':
//				$site_message = API::insertPRM($_POST);
				$api = new API\InsertPRM($_POST);
				$site_message = $api->execute();
				break;
			case 'prm_edit':
//				$site_message = API::insertPRM($_POST);
				$api = new API\EditPRM($_POST);
				$site_message = $api->execute();
				break;
			case 'check_existing':
//				$site_message = API::checkExisting($_POST);
				$api = new API\CheckExisting($_GET['value'], $_POST);
				$site_message = $api->execute();
				break;
			case 'feedback':
//				$site_message = API::submitFeedBack($_POST);
				$api = new API\SubmitFeedBack($_POST);
				$site_message = $api->execute();
				break;
			default:
				break;
		}

		echo json_encode($site_message);
	}
}
else {
// GET functions still invoke methods from API.php
// This is because haven't had time to implement a different base class
// for this. I will need to implement a different base class, as
// my decision is that I am NOT interested in logging anything related to
// these. Also, they return an array list of keyname, value, due to early implementation.

	$list = '';
	if ($_GET['mode'] && $_GET['value']) {
		switch($_GET['value']) {
			case 'departments':
				$list = API::getDepartments();
				break;
			case 'site_nav':
				$list = API::getSiteNav();
				break;
			case 'months':
				$list = API::getMonths();
				break;
			case 'years':
				$list = API::getYears();
				break;
			case 'prm_data':
				$dept = (isset ($_GET['dept']) && !empty($_GET['dept']))
					? $_GET['dept']
					: '';
				$list = API::getPRMData($dept);
				break;
			default:
				break;
		}
	}

	echo json_encode($list);
}

?>
