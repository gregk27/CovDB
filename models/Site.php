<?php

/**
 * Class representing a Site
 * Automatically generated from SQL script by modelgen.py
 */
class Site {
	public string $name;
	public string $street;
	public string $city;
	public string $province;
	public string $postalCode;

	public function __construct($name, $street, $city, $province, $postalCode, ) {
		$this->name = $name;
		$this->street = $street;
		$this->city = $city;
		$this->province = $province;
		$this->postalCode = $postalCode;
	}

	public function toAssoc() {
		return get_object_vars($this);
	}

	public static function fromAssoc($assoc) {
		return new Site(
			$assoc["name"],
			$assoc["street"],
			$assoc["city"],
			$assoc["province"],
			$assoc["postalCode"],
		);
	}
}