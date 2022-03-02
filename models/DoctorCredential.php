<?php

/**
 * Class representing a DoctorCredential
 * Automatically generated from SQL script by modelgen.py
 */
class DoctorCredential {
	public int $ID;
	public string $credential;

	public function __construct($ID, $credential, ) {
		$this->ID = $ID;
		$this->credential = $credential;
	}

	public function fromAssoc($assoc) {
		return new DoctorCredential(
			$assoc["ID"],
			$assoc["credential"],
		)
	}
}