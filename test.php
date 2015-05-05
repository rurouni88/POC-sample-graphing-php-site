<?php

# TEST PHP Page
require_once('includes/functions.php');

#	$conditions = array('department' => 'medical_physics');
#	$rows = RPMData::Find($conditions);
#	$rows = RPMData::load_all();
#	$rows = SiteNav::load_all();

	$var['year'] = '2013';
	$var['month'] = '07';
	$var['department'] = 'cardiology';
//	$var['prm_value'] = 1;



//	$api = new API\CheckExisting('rpm', $var);
//	$site_message = $api->execute();
//	$exists = \DAO\PRMData::Find($var);
//	$exists = \DAO\PRMData::load_all();
//	$statistics = new PRMStatistics('');
//	$statistics->run();
//	$cache = new CacheJSON('prm_data');
//	$cache->write($statistics);
//	$cache->load();
//	preVar($cache);
/
	$object = API::getPRMData($department);
	preVar($object);

#	$object = new \API\GetRPMData();

//	$object = API::checkExisting('rpm',$var);
//	use DAO\SiteConfig;
//	$object = SiteConfig::FindByKeyname('LOG_LEVEL')->value;

//use DAO\SiteNav;
//$array = new SiteNav();
//$array = SiteNav::load_all();
// preVar($array);
//	use API\InsertRPM;
//	$object = new API\InsertRPM($var);
//	$object->execute();
//	preVar($object);

//	$exists = \DAO\PRMData::load_all();
?>
