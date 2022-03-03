<?php

/**
 * Class representing a DoctorWorksAt
 * Automatically generated from SQL script by modelgen.py
 */
class DoctorWorksAt {
	public int $doctor;
	public string $site;

	public function __construct($doctor, $site, ) {
		$this->doctor = $doctor;
		$this->site = $site;
	}

	public function fromAssoc($assoc) {
		return new DoctorWorksAt(
			$assoc["doctor"],
			$assoc["site"],
		);
	}
}