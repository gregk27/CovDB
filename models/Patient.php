<?php

/**
 * Class representing a Patient
 * Automatically generated from SQL script by modelgen.py
 */
class Patient {
	public string $OHIP;
	public string $firstName;
	public string $lastName;
	public string $dateOfBirth;

	public function __construct($OHIP, $firstName, $lastName, $dateOfBirth, ) {
		$this->OHIP = $OHIP;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->dateOfBirth = $dateOfBirth;
	}

	public function toAssoc() {
		return get_object_vars($this);
	}

	public static function fromAssoc($assoc) {
		return new Patient(
			$assoc["OHIP"],
			$assoc["firstName"],
			$assoc["lastName"],
			$assoc["dateOfBirth"],
		);
	}
}