<?php

/**
 * Class representing a Spouse
 * Automatically generated from SQL script by modelgen.py
 */
class Spouse {
	public string $OHIP;
	public string $firstName;
	public string $lastName;
	public string $phone;
	public string $patientOHIP;

	public function __construct($OHIP, $firstName, $lastName, $phone, $patientOHIP, ) {
		$this->OHIP = $OHIP;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->phone = $phone;
		$this->patientOHIP = $patientOHIP;
	}

	public function fromAssoc($assoc) {
		return new Spouse(
			$assoc["OHIP"],
			$assoc["firstName"],
			$assoc["lastName"],
			$assoc["phone"],
			$assoc["patientOHIP"],
		)
	}
}