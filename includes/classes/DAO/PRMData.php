<?php namespace DAO {

# PRMData Data Class
class PRMData extends DAOBase {

	private static $SCHEMA = 'RPH';
	private static $TABLE = 'prm_data';
	private static $COLUMNS = array('id', 'department', 'year', 'month', 'prm_value', 'created', 'modified');

	public $year;
	public $month;
	public $department;
	public $prm_value;

	public function __construct() {
		return parent::__construct(self::$SCHEMA, self::$TABLE);
	}

	public function getColumns() {
		return self::$COLUMNS;
	}

	public function setYear($value) {
		if(is_numeric($value)) {
			$this->year = $value;
		}
	}

	public function setMonth($value) {
		if(!empty($value)) {
			$this->month = $value;
		}
	}

	public function setDepartment($value) {
		if(!empty($value)) {
			$this->department = $value;
		}
	}

	public function setPRMValue($value) {
		if(is_numeric($value)) {
			$this->prm_value = $value;
		}
	}

	public function Create() {
		return parent::insert();
	}

	public function Update() {
		return parent::edit($this->id);
	}

	# Static Functions
	public static function Find($conditions) {
		$class = new PRMData();
		return $class->select_object($conditions);
	}

	public static function load_all() {
		$class = new PRMData();
		$conditions = array();
		return $class->select_object($conditions);
	}
}

} ?>
