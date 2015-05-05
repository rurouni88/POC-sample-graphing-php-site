<?php namespace Process {

// Includes
use \Exception;
use Validation;
use DAO\PRMData as PRMData;

# Process Class EditPRM
class EditPRM extends ProcessBase {
	public $id;
	public $year;
	public $month;
	public $department;
	public $prm_value;

	public function __construct($id, $year, $month, $department, $prm_value) {

		if (empty($year) || empty($month) || empty($department)) {
			throw new Exception("Missing year, month or department ($year, $month, $department)");
		}

		if (!Validation::isInteger($prm_value)) {
			throw new Exception("prm_value $prm_value is not an integer");
		}

		if (!Validation::isInteger($id)) {
			throw new Exception("Id $id is not an integer");
		}

		$this->id = $id;
		$this->year = $year;
		$this->month = $month;
		$this->department = $department;
		$this->prm_value = $prm_value;

		return parent::__construct();
	}

	protected function _execute() {
		$conditions = array(
			'id'        => $this->id,
			'year'      => $this->year,
			'month'     => $this->month,
			'department'=> $this->department,
		);
		$records = PRMData::Find($conditions);

		$record;
		$records_count = count($records);
		if ($records_count == 1) {
			// Edit the damn thing
			$record = $records[0];
			$record->prm_value = $this->prm_value;
			$record->Update();
		}
		else {
			throw new Exception("Record Count: $records_count for id ".$this->id);
		}

		return $record;
	}
}

} ?>
