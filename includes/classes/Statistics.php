<?php

// Includes
use \Exception;

// Statistics class to calculate general statistics
class Statistics {

	// Class Vars
	public $data;

	public $count = 0;
	public $total = 0;
	public $mean = 0.00;
	public $median = 0.00;
	public $mode = 0;
	public $range = 0;
	public $minimum = 0;
	public $maximum = 0;

	public function __construct($data) {

		// Validate this array
		if (!$data && !is_array($data)) {
			throw new Exception (sprintf(
				"%s expects an array for data", get_class($this)
			));
		}

		// Set data
		$this->data = $data;

		// Calculate generic statistics
		$this->count = count($this->data);
		$this->total = array_sum($this->data);

		if ($this->count > 0) {
			$this->mean = $this->Mean();
			$this->median = $this->Median();
			$this->mode = $this->Mode();
			$this->range = $this->Range();
			$this->minimum = min(array_values($this->data));
			$this->maximum = max(array_values($this->data));
		}

		return $this;
	}

	// Mean = The average of all the numbers 
	public function Mean() {

		$mean = array_sum($this->data) / count($this->data);

		return sprintf("%.2f", $mean);
	}

	// Median = The middle value after the numbers
	// are sorted smallest to largest 
	public function Median() {

		rsort($this->data); 
		$mid = round(count($this->data) / 2); 
		$median = $this->data[$mid-1];

		return sprintf("%.2f", $median);
	}

	// Mode = The number that is in the array the most times 
	public function Mode() {

		$array_cv = array_count_values($this->data);
		arsort($array_cv);

		$mode = 0;

		foreach($array_cv as $key => $array_cv){
			$mode = $key;
			break;
		}

		return $mode;
	}

	// Range = The difference between the highest & the lowest
	public function Range() {
		sort($this->data); 
		$lowest = $this->data[0]; 

		rsort($this->data); 
		$highest = $this->data[0]; 

		$range = $highest - $lowest;

		return $range;
	}
}

?>
