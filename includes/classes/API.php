<?php

// Includes
use \Exception;
use DAO\SiteNav as SiteNav;
use DAO\Department as Department;
use DAO\PRMData as PRMData;
use DAO\SiteConfig as SiteConfig;

# API function library
class API {

	# Static Functions

	# Get the Departments available in the system
	# Returns an Array of Department Objects
	public static function getDepartments() {
		$config_record = SiteConfig::FindByKeyname('CACHE_EXPIRY_DEPT');
		$expiry_in_seconds = (!empty($config_record))
			? $config_record->value
			: 0;

		$cache = new CacheJSON('departments');

		if ($cache->isExpired($expiry_in_seconds)) {
			$array = Department::load_all();

			# Sort out the department by name
			usort($array, function($a, $b) {
				$a->value = $a->name;
				$b->value = $b->name;
				return strcmp($a->name, $b->name);
			});

			$cache->write($array);
		}
		else {
			$cache->load();
		}

		return $cache;
	}

	# Get the Site Navigation
	# Returns an Array of SiteNav Objects
	public static function getSiteNav() {
		$config_record = SiteConfig::FindByKeyname('CACHE_EXPIRY_SITENAV');
		$expiry_in_seconds = (!empty($config_record))
			? $config_record->value
			: 0;

		$cache = new CacheJSON('site_nav');
		if ($cache->isExpired($expiry_in_seconds)) {

			# Load and sort out the navigation by order
			$array = SiteNav::load_all();
			usort($array, function($a, $b) {
				return strcmp($a->order, $b->order);
			});

			$cache->write($array);
		}
		else {
			$cache->load();
		}

		return $cache;
	}

	# Return a statistics object and let the front end decide
	# what to do with it
	public static function getPRMData($department) {
		$config_record = SiteConfig::FindByKeyname('CACHE_EXPIRY_PRMDATA');
		$expiry_in_seconds = (!empty($config_record))
			? $config_record->value
			: 0;

		$cache_filename = (empty($department))
			? 'prm_data'
			: sprintf("prm_data-%s", $department);

		$cache = new CacheJSON($cache_filename.'_'.date('Ymd'));

		if ($cache->isExpired($expiry_in_seconds)) {
			$statistics = new PRMStatistics($department);
			$statistics->run();
			$cache->write($statistics);
		}
		else {
			$cache->load();
		}

		return $cache;
	}

	# Get a Range of Months
	# Returns an Associative array where each element has 'display' and 'value'
	public static function getMonths() {

		$cache = new CacheJSON('available_months');

		if ($cache->cacheExists()) {
			$results = array();
			$current_month = intval(date('m'));

			for($x = $current_month; $x < $current_month + 12; $x++) {
				$months = array();
				$key = sprintf("%02d", date('n', mktime(0, 0, 0, $x, 1)));
				$value = date('F', mktime(0, 0, 0, $x, 1));

				$months['display'] = $value;
				$months['value'] = $key;

				array_push($results, $months);
			}
			$cache->write($results);
		}
		else {
			$cache->load();
		}

		return $cache;
	}

	# Get a Range of Years
	# Returns an Associative array where each element has 'display' and 'value'
	public static function getYears() {

		// Regenerate daily
		$expiry_in_seconds = 86400;
		$cache = new CacheJSON('available_years');

		if ($cache->isExpired($expiry_in_seconds)) {
			$results = array();
			$current_year = intval(date('Y'));

			$range = range($current_year, $current_year - 5);
			foreach ($range as $year) {
				$years = array();
				$years['display'] = $years['value'] = $year;

				array_push($results, $years);
			}
			$cache->write($results);
		}
		else {
			$cache->load();
		}

		return $cache;
	}

	/*
	 * I have deprecated all functions below
	 * They are all API functions which deal with posted variables, and
	 * my design for these is to ensure that these requests are logged,
	 * so I have moved these to the API namespace
	*/

	// This function is deprecated. See API\CheckExisting (2013-07-15)
	public static function checkExisting($value, $var) {
		die ('This function is deprecated. See API\CheckExisting');
		$site_message;

		switch($value) {
			case 'prm':
				$site_message = self::_checkPRM($var);
				break;
			default:
				break;
		}

		return $site_message;
	}

	// This function is deprecated. See API\CheckExisting (2013-07-15)
	private static function _checkPRM($var) {
		die ('This function is deprecated. See API\CheckExisting');

		$required = array('year', 'month', 'department');
		$errors = Validation::isNotEmpty($var, $required);

		$existing;
		if(count($errors) <= 0) {
			$existing = Process::checkPRM(
				$var['year'], $var['month'],
				$var['department']
			);
		}

		$success = ($existing) ? true : false;

		return new SiteMessage($success, $message, $errors, $existing);
	}

	// This function is deprecated. See API\InsertPRM (2013-07-15)
	public static function InsertPRM($var) {
		die ('This function is deprecated. See API\InsertPRM');
		$required = array('year', 'month', 'department', 'prm_value');
		$errors = Validation::isNotEmpty($var, $required);

		if (!Validation::isInteger($var['prm_value'])) {
			array_push($errors, "prm_value (".$var['prm_value'].") is not an integer");
		}

		$success = false;
		$message = '';

		if(count($errors) <= 0) {
			try {
				$new_prm = Process::InsertPRM(
					$var['year'], $var['month'],
					$var['department'], $var['prm_value']
				);

				if ($new_prm) {
					$message = 'Record Id '.$new_prm->id.' was created successfully';
					$success = true;
				}
			}
			catch (Exception $e) {
				array_push($errors, $e->getMessage());
			}
		}

		return new SiteMessage($success, $message, $errors, undef);
	}

	// This function is deprecated. See API\SubmitFeedback (2013-07-15)
	public static function submitFeedBack($var) {
		die ('This function is deprecated. See API\submitFeedBack');
		$required = array ('subject', 'name', 'email_from', 'details');
		$errors = Validation::isNotEmpty($var, $required);
		$to = 'cynicrock@hotmail.com';

		$success = false;
		$message = '';

		if (!Validation::isEmail($var['email_from'])) {
			array_push($errors, "'".$var['email_from']."'is not a valid email address");
		}

		if(count($errors) <= 0) {
			try {
				$errors = Process::emailFeedback(
					$to, $var['email_from'], $var['name'],
					$var['subject'], $var['details']
				);
			}
			catch (Exception $e) {
				array_push($errors, $e->getMessage());
			}
		}

		return new SiteMessage($success, $message, $errors, undef);
	}
}

?>
