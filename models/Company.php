<?php

/**
 * Class representing a Company
 * Automatically generated from SQL script by modelgen.py
 */
class Company {
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

	public function fromAssoc($assoc) {
		return new Company(
			$assoc["name"],
			$assoc["street"],
			$assoc["city"],
			$assoc["province"],
			$assoc["postalCode"],
		)
	}
}