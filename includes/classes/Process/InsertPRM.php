<?php namespace Process {

// Includes
use \Exception;
use Validation;
use DAO\PRMData as PRMData;

# Process Class InsertPRM
class InsertPRM extends ProcessBase {
	public $year;
	public $month;
	public $department;
	public $prm_value;

	public function __construct($year, $month, $department, $prm_value) {

		if (empty($year) || empty($month) || empty($department)) {
			throw new Exception("Missing year, month or department ($year, $month, $department)");
		}

		if (!Validation::isInteger($prm_value)) {
			throw new Exception("prm_value $prm_value is not an integer");
		}

		$this->year = $year;
		$this->month = $month;
		$this->department = $department;
		$this->prm_value = $prm_value;

		return parent::__construct();
	}

	protected function _execute() {
		$conditions = array(
			'year'      => $this->year,
			'month'     => $this->month,
			'department'=> $this->department,
		);
		$records = PRMData::Find($conditions);

		$record;
		$records_count = count($records);
		if ($records_count == 0) {
			$record = new PRMData();
			$record->setYear($this->year);
			$record->setMonth($this->month);
			$record->setDepartment($this->department);
			$record->setPRMValue($this->prm_value);
			$record->Create();
		}
		else {
			$error = sprintf(
				"Record Count: %d for (%s,%s,%s)",
				$records_count, $this->year,
				$this->month, $this->department
			);
			throw new Exception($error);
		}

		return $record;
	}
}

} ?>
