<?php

# SiteMessage class
class SiteMessage {

	public $success;    // Boolean
	public $message;    // Singular string
	public $errors;     // Array of strings
	public $item;       // An Item, expecting Singular Object return

	public function __construct($success, $message, $errors, $item) {
		# Set stuff
		$this->success = $success;
		$this->message = $message;
		$this->errors = $errors;
		$this->item = $item;

		return $this;
	}

}

?>