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

	public function toAssoc() {
		return get_object_vars($this);
	}

	public static function fromAssoc($assoc) {
		return new Practice(
			$assoc["name"],
			$assoc["phone"],
		);
	}
}