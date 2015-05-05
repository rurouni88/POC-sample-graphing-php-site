<?php

// Includes
use \Exception;

// Serving up a lot of JSON, so of course I'll make a Cache class
// One thing that makes me sad, PHP doesn't support multiple inheritance
// There are workabouts, but I'd rather have it done properly than
// use some obscure hacky method to achieve it
class CacheJSON {
	# Temporary constants: Move to config file, if necessary
	const DIR_PATH = 'cache/'; 
	const EXTENSION = '.json';

	public $created;
	private $file_dir;
	public $file_path;
	public $data;

	public function __construct($filename) {

		// Validate this array
		if (empty($filename)) {
			throw new Exception (sprintf(
				"%s expects a string for filename", get_class($this)
			));
		}

		$date = new DateTime();
		$date_path = $date->format('Y').'/'.$date->format('m').'/';

		$this->file_dir = SITE_DIR.'/'.self::DIR_PATH . $date_path;
		$this->file_path = $this->file_dir . $filename . self::EXTENSION;

		$this->created = (file_exists($this->file_path))
			? date("F j, Y, G:i:s e", filemtime($this->file_path))
			: false;

		// Calculate generic statistics
		return $this;
	}

	public function load () {
		try {
			if (!file_exists($this->file_path)) {
				throw new Exception("No data file '".$this->file_path."'");
			}

			$this->data = json_decode(file_get_contents($this->file_path));
			$this->created = date("F j, Y, G:i:s e", filemtime($this->file_path));
		}
		catch (Exception $e) {
			die ($e->getMessage());
		}

		return true;
	}

	public function write($data) {
		try {
			if (!file_exists($this->file_dir)) {
				mkdir($this->file_dir, '0755', true);
			}

			$this->data = $data;
			file_put_contents($this->file_path, json_encode($data));
			$this->created = date("F j, Y, G:i:s e", filemtime($this->file_path));
		}
		catch (Exception $e) {
			die ($e->getMessage());
		}

		return true;
	}

	// Function to determine if the cache is expired
	public function isExpired($diff_in_secs) {

		if (!Validation::isInteger($diff_in_secs)) {
			throw new Exception ("Expects an integer for diff_in_secs");
		}

		// file doesn't exist yet, so return TRUE
		if (!$this->created) {
			return true ;
		}

		$created_ts = strtotime($this->created);
		$difference = abs( time() - $created_ts);

		return ($difference >= $diff_in_secs)
			? true
			: false;
	}

	// Function to determine if the cache exists
	public function cacheExists() {
		return (empty($this->created))
			? true
			: false;
	}
}

?>
