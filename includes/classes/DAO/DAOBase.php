<?php namespace DAO {

// Includes
include (SITE_DIR.'/noDB/nodb_functions.php');

use Validation;
use \Exception;
use \DateTime;

# Simple DAO Wrapper for noDB
abstract class DAOBase {

	# Temporary constants: Move to config file, if necessary
	private static $EXTENSION = 'nodb';
	private static $DB_TYPE = 'nodb';
	private static $NODB_PATH = 'noDB/db';

	private $db_schema;
	private $db_table;
	private $db_file;
	private $db_type;
	private $db_path;
	public $id;
	public $created;
	public $modified;

	public function __construct($schema, $table) {

		# Set stuff
		$this->db_schema = $schema;
		$this->db_table = $table;
		$this->db_file = $this->db_table.'.'.self::$EXTENSION;

		$this->db_type = self::$DB_TYPE;
		$this->db_path = SITE_DIR .'/'. self::$NODB_PATH;

		return $this;
	}

	# Function to perform a select with conditional matching
	public function select_object($conditions) {
		$xml = readDatabase($this->db_file, $this->db_schema, $this->db_path);
		$results = array();

		foreach ($xml->row as $row) {
			$columns = $this->getColumns();
			$class = get_class($this);
			$object = new $class();
			foreach ($this->getColumns() as $column) {
				$object->$column = $row->$column->__toString();
			}

			# Determine whether to return the result set
			$match = false;
			$condition_count = count($conditions);
			if (is_array($conditions) && $condition_count) {
				$match_count = 0;
				foreach ($conditions as $key => $value) {
					if ($object->$key && ($object->$key == $value)) {
						$match_count++;
					}
				}

				if ($condition_count == $match_count) {
					$match = true;
				}
			}
			else {
				$match = true;
			}

			if ($match) {
				array_push($results, $object);
			}
		}

		return $results;
	}

	# Function to insert a Row into the table
	public function insert() {
		$row = array();
		foreach ($this->getColumns() as $column) {
			$row[$column] = $this->$column;
		}

		# Set auditing properties
		$date = new DateTime();
		$row['created'] = $date->format('Y-m-d H:i:s');
		$row['modified'] = $date->format('Y-m-d H:i:s');

		if (insertRow($row, $this->db_file, $this->db_schema, $this->db_path, false)) {
			# Reload records and assign id to current object instance
			# Also traps race conditions :)
			unset($row['id']);
			$records = self::select_object($row);
			$record_count = count($records);
			if ($record_count == 1) {
				$this->id = $records[0]->id;
			}
			else {
				$error = sprintf(
					"%s has %d record count after insertion (%s)",
					__FUNCTION__, $record_count, json_encode($row)
				);
				throw new Exception($error);
			}
			return true;
		}

		return false;
	}

	# Function to edit a row
	public function edit($id) {
		if (!Validation::isInteger($id)) {
			throw new Exception(__FUNCTION__.' expects and integer for id.');
		}

		$row = array();
		foreach ($this->getColumns() as $column) {
				$row[$column] = $this->$column;
		}

		$date = new DateTime();
		$row['modified'] = $date->format('Y-m-d H:i:s');
		$this->modified = $row['modified'];

		return editRow($id, $row, $this->db_file, $this->db_schema, $this->db_path);
	}
}

} ?>
