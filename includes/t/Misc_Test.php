<?php
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);

use DAO\Department;

class Misc_Test extends PHPUnit_Framework_TestCase {

	public function testFunctionsLib() {
		// Test Functions Lib
		$this->assertStringMatchesFormat("%s", returnDate('') );
	}

	public function testCacheJSON() {
		$object = new CacheJSON('test');
		PHPUnit_Framework_Assert::assertNotNull($object);
//		$object->write();
//		$object->cacheExists
//		$object->load
//		$object->isExpired
	}

	public function testSiteMessage() {
		$object = new SiteMessage('', '', '', '');
		PHPUnit_Framework_Assert::assertNotNull($object);
	}

	public function testStatistics() {
		$data = array();
		$object = new Statistics($data);
		PHPUnit_Framework_Assert::assertNotNull($object);
	}

	public function testPRMStatistics() {
		$object = new PRMStatistics('');
		PHPUnit_Framework_Assert::assertNotNull($object);
	}
}

?>
