<?php

use DAO\SiteConfig;

class SiteConfig_Test extends PHPUnit_Framework_TestCase {

	public $test_object;

	function setUp() {
		$this->test_object = new SiteConfig();
	}

	function tearDown() {
		unset($this->test_object);
	}

	// I have broken the one Assertion per test recommendation, because
	// all DAOs should have these
	public function testGenerics() {
		$object = $this->test_object;
		$this->assertNotNull($object);

		// All DAOs have these
		$this->assertObjectHasAttribute('SCHEMA', $object);
		$this->assertObjectHasAttribute('TABLE', $object);
		$this->assertObjectHasAttribute('COLUMNS', $object);

		$this->assertObjectHasAttribute('id', $object);
		$this->assertObjectHasAttribute('created', $object);
		$this->assertObjectHasAttribute('modified', $object);

		$this->assertTrue(is_array($object->getColumns()));
	}

	public function test_load_all() {
		$return = SiteConfig::load_all();
		$this->assertTrue(is_array($return));
	}
}

?>
