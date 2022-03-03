<?php

/**
 * Class representing a Nurse
 * Automatically generated from SQL script by modelgen.py
 */
class Nurse {
	public int $ID;
	public string $firstName;
	public string $lastName;

	public function __construct($ID, $firstName, $lastName, ) {
		$this->ID = $ID;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
	}

	public function toAssoc() {
		return get_object_vars($this);
	}

	public static function fromAssoc($assoc) {
		return new Nurse(
			$assoc["ID"],
			$assoc["firstName"],
			$assoc["lastName"],
		);
	}
}