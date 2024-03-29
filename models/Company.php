<?php

/**
 * Class representing a Company
 * Automatically generated from SQL script by modelgen.py
 */
class Company {
	public string $name;
	public string | null $street;
	public string | null $city;
	public string | null $province;
	public string | null $postalCode;

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
		return new Company(
			$assoc["name"],
			$assoc["street"],
			$assoc["city"],
			$assoc["province"],
			$assoc["postalCode"],
		);
	}
}