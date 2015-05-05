<?php

// Includes
use \Exception;
use \DateTime;
use \DAO\PRMData as PRMData;
use \DAO\Department as Department;

# PRMStatistics Class
class PRMStatistics extends Statistics {

	public $department;
	public $records;

	public $highest_dates;
	public $lowest_dates;

	// Departmental Statistics - for shame and blame
	public $dept_lowest;
	public $dept_highest;

	// For graphing
	public $dept_breakdown;
	public $dept_ytd_prm;

	public function __construct($department) {

		$this->records = array();
		$this->department = $department;

		if (empty($department)){
			$this->records = DAO\PRMData::load_all();
		}
		else {
			$conditions = array(
				'department'=> $department,
			);
			$this->records = DAO\PRMData::Find($conditions);
		}

		$data = array();
		foreach ($this->records as $record) {
			array_push($data, $record->prm_value);
		}

		return parent::__construct($data);
	}

	// PRMObject Specific statistics
	public function run() {
		$this->highest_dates = self::getDatesWherePRMValueWas($this->maximum);
		$this->lowest_dates = self::getDatesWherePRMValueWas($this->minimum);

		// Set cross departmental statistics
		if (empty($this->department)){
			self::_setCrossDeptStats();
		}
	}

	// Get all the records WHere PRM Value Matches
	public function getDatesWherePRMValueWas($value) {

		$dates_array = array();
		$objects = self::_getWherePRMValueIs('prm_value', $value);

		foreach ($objects as $object) {
			$time = sprintf("%s-%s-01", $object->year, $object->month);
			$date = new DateTime($time);
			array_push($dates_array, $date->format("F Y"));
		}

		return implode(", ", $dates_array);
	}

	// Get all the records where PRM value matches
	private function _getWherePRMValueIs($keyname, $value) {
		return PRMStatistics::filter_array($this->records, $keyname, $value);
	}

	private function _setCrossDeptStats() {
		$this->dept_lowest = self::_getDeptWherePRMValueWas($this->minimum);
		$this->dept_highest = self::_getDeptWherePRMValueWas($this->maximum);

		$this->dept_breakdown = self::_breakdownByDept();
		$this->dept_ytd_prm = self::_PRMValuesYTD();
	}

	private function _getDeptWherePRMValueWas ($value) {
		$objects = self::_getWherePRMValueIs('prm_value', $value);

		$results = array();
		foreach ($objects as $object) {
			$display = DAO\Department::getDisplayName($object->department);
			array_push($results, $display);
		}

		return implode(", ", array_unique($results));
	}

	// Breakdown by Department for the current year
	private function _breakdownByDept() {
		$results = array();
		$current_year = intval(date('Y'));

		$records = PRMStatistics::filter_array($this->records, 'year', $current_year);

		foreach ($records as $record) {

			$keyname = DAO\Department::getDisplayName($record->department);

			$results[$keyname] = (empty($results[$keyname]))
				? $record->prm_value
				: $results[$keyname] + $record->prm_value;
		}

		return $results;
	}

	// Function to get all this years values
	private function _PRMValuesYTD() {

		$departments = DAO\Department::load_all();

		$current_year = intval(date('Y'));
		$results = array();

		foreach ($departments as $dept) {

			$results[$dept->display] = array();

			// Filter records to only records for this year
			$records = PRMStatistics::filter_array($this->records, 'year', $current_year);
			$records = PRMStatistics::filter_array($records, 'department', $dept->name);

			for($x = 1; $x <= 12; $x++) {
				$month = sprintf("%02d", date('n', mktime(0, 0, 0, $x, 1)));

				// Get records where the month correlates
				$month_records = array_values(PRMStatistics::filter_array($records, 'month', $month));

				if (count($month_records) > 0) {
					array_push($results[$dept->display], (int)($month_records[0]->prm_value));
				}
				else {
					array_push($results[$dept->display], 0);
				}
			}
		}

		return $results;
	}

	// Filter array function
	// Utility used to find an object in an array where keyname == value
	public static function filter_array($records, $keyname, $value) {
		return array_filter(
			$records,
			function ($e) use(&$keyname, &$value) {
				return $e->$keyname == $value;
			}
		);
	}
}

?>
