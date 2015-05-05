<?php

# Bootstrap code
define('BASE_DIR', __DIR__);
define('SITE_DIR', dirname(__DIR__));

set_include_path(get_include_path() . PATH_SEPARATOR . BASE_DIR);
ini_set('display_errors', 'on');

spl_autoload_register('autoload_class');

# Autoload classes
function autoload_class($class_name){
	//class directories
	$directorys = array(
		'/',
		'/classes/',
//		'/classes/API/',
//		'/classes/Process/',
//		'/classes/DAO/',
	);

	//for each directory
	foreach($directorys as $directory) {

		//see if the file exists
		$class_sub_path = $directory.$class_name.'.php';
		$class_sub_path = str_replace('\\', '/', $class_sub_path);

		$expected_file_path = BASE_DIR.$class_sub_path;
		$expected_file_path = str_replace('\\', '/', $expected_file_path);

		if(file_exists($expected_file_path)) {
//			preVar($expected_file_path);
//			include_once($class_sub_path);
			include_once($expected_file_path);
			return;
		}
	}
}

# Returns today's date (server) in a string
function returnDate($timestamp) {
	$format = "F j, Y, G:i:s e";
	$timestamp = ($timestamp > 0 || (int)$timestamp === 0) ? (int)$timestamp : time();
	$f_date = date($format, time());

	return $f_date;
}

# Dump a variable to HTML encapsulated in "pre" tags
function preVar($variable){
	echo "<pre>";
	var_dump($variable);
	echo "</pre>";
}

?>
