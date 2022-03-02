<?php

/**
 * Class representing a NurseCredential
 * Automatically generated from SQL script by modelgen.py
 */
class NurseCredential {
	public int $ID;
	public string $credential;

	public function __construct($ID, $credential, ) {
		$this->ID = $ID;
		$this->credential = $credential;
	}

	public function fromAssoc($assoc) {
		return new NurseCredential(
			$assoc["ID"],
			$assoc["credential"],
		)
	}
}