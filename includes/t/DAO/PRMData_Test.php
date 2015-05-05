<?php

use DAO\PRMData;

class PRMData_Test extends PHPUnit_Framework_TestCase {

	public $test_object;

	function setUp() {
		$this->test_object = new PRMData();
	}

	function tearDown() {
		unset($this->test_object);
	}

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
		$return = PRMData::load_all();
		$this->assertTrue(is_array($return));
	}
}

?>
