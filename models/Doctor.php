<?php

/**
 * Class representing a Doctor
 * Automatically generated from SQL script by modelgen.py
 */
class Doctor {
	public int $ID;
	public string $firstName;
	public string $lastName;
	public string $practice;

	public function __construct($ID, $firstName, $lastName, $practice, ) {
		$this->ID = $ID;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->practice = $practice;
	}

	public function toAssoc() {
		return get_object_vars($this);
	}

	public static function fromAssoc($assoc) {
		return new Doctor(
			$assoc["ID"],
			$assoc["firstName"],
			$assoc["lastName"],
			$assoc["practice"],
		);
	}
}