<?php namespace API {

# API Class CheckExisting
class CheckExisting extends APIBase {

	public $type;
	public $posted_var;

	public function __construct($type, $posted_var) {
		$this->type = $type;
		$this->posted_var = $posted_var;

		return parent::__construct();
	}

	protected function executeRequest() {
		$existing;
		$errors;

		switch($this->type) {
			case 'prm':
				list($existing, $errors) = self::_checkExistingPRM(
					$this->posted_var['year'], $this->posted_var['month'], $this->posted_var['department']
				);
				break;
			default:
				break;
		}

		$success = ($existing) ? true : false;

		$this->success = $success;
		$this->errors = $errors;
		$this->item = $existing;

		return true;
	}

	private static function _checkExistingPRM($year, $month, $department) {

		 $errors = array();
		if($year && $month && $department) {
			$conditions = array(
				'year'      => $year,
				'month'     => $month,
				'department'=> $department,
			);
			$exists = \DAO\PRMData::Find($conditions);

			$existing = (is_array($exists) && count($exists) > 0)
				? $exists[0]
				: false;
		}

		return array ($existing, $errors);
	}
}

} ?>
