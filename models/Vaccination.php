<?php

/**
 * Class representing a Vaccination
 * Automatically generated from SQL script by modelgen.py
 */
class Vaccination {
	public string $patient;
	public string $site;
	public string $lot;
	public int $datetime;

	public function __construct($patient, $site, $lot, $datetime, ) {
		$this->patient = $patient;
		$this->site = $site;
		$this->lot = $lot;
		$this->datetime = $datetime;
	}

	public function fromAssoc($assoc) {
		return new Vaccination(
			$assoc["patient"],
			$assoc["site"],
			$assoc["lot"],
			$assoc["datetime"],
		)
	}
}