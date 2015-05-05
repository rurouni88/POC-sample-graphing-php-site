<?php
/*
Stick all validation here

Usage:
Validation::isEmail($value);

*/

class Validation {

	public static function isNotEmpty($array_values, $array_keynames) {
		$errors = array();

		foreach ($array_keynames as $keyname){
			if (empty($array_values[$keyname])) {
				array_push($errors, "Missing $keyname");
//				array_push($errors, new Exception("Missing $keyname"));
			}
		}

		return $errors;
	}

	// Regular expression checking that value is an integer
	public static function isInteger($value) {
		return preg_match('/^\d+$/', $value);
	}
 
	// Regular expression checking that value is pretty similar to an email pattern
	public static function isEmail($email) {
		$email =  strtolower($email);
		if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)) {
			return $email;
		}

		return false;
	}
 
}

?>
