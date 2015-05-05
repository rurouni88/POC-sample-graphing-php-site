<?php namespace DAO {

use \Exception;

# Department class
class Department extends DAOBase {

	private static $SCHEMA = 'RPH';
	private static $TABLE = 'departments';
	private static $COLUMNS = array('id', 'name', 'display', 'created', 'modified');

	public $name;
	public $display;

	public function __construct() {
		return parent::__construct(self::$SCHEMA, self::$TABLE);
	}

	public function getColumns() {
		return self::$COLUMNS;
	}

	# Static Functions
	public static function getDisplayName($name) {
		if (empty($name)) {
			throw new Exception("No name was supplied");
		}

		$class = new Department();
		$conditions = array(
			'name'  => $name
		);

		$objects = $class->select_object($conditions);
		$count = count($objects);

		if ($count != 1) {
			throw new Exception("Record count $count for '$name'");
		}

		return $objects[0]->display;
	}

	public static function load_all() {
		$class = new Department();
		$conditions = array();
		return $class->select_object($conditions);
	}
}

} ?>
