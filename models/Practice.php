<?php

/**
 * Class representing a Practice
 * Automatically generated from SQL script by modelgen.py
 */
class Practice {
	public string $name;
	public string $phone;

	public function __construct($name, $phone, ) {
		$this->name = $name;
		$this->phone = $phone;
	}

	public function fromAssoc($assoc) {
		return new Practice(
			$assoc["name"],
			$assoc["phone"],
		)
	}
}