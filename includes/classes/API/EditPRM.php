<?php namespace API {

// Includes
use \Exception;
use Validation;

use \Process\EditPRM as ProcessToExecute;

# API Class EditPRM
class EditPRM extends APIBase {
	public $type;
	public $posted_var;

	public function __construct($posted_var) {
		$this->posted_var = $posted_var;

		return parent::__construct();
	}

	protected function executeRequest() {
		$var = $this->posted_var;
		$required = array('override_id', 'year', 'month', 'department', 'prm_value');
		$errors = Validation::isNotEmpty($var, $required);

		if (!Validation::isInteger($var['prm_value'])) {
			array_push($errors, "prm_value (".$var['prm_value'].") is not an integer");
		}

		if(count($errors) <= 0) {
			try {
				$process = new ProcessToExecute(
					$var['override_id'],
					$var['year'], $var['month'],
					$var['department'], $var['prm_value']
				);

				$new_prm = $process->execute();

				if ($new_prm) {

					$message = sprintf(
						"Record Id %d was updated successfully on %s",
						$new_prm->id, $new_prm->modified
					);
					$this->message = $message;
					$this->success = true;
				}
			}
			catch (Exception $e) {
				array_push($errors, $e->getMessage());
			}
		}

		$this->errors = $errors;

		return true;
	}
}

} ?>
