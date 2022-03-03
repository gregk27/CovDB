<?php

/**
 * Class representing a Lot
 * Automatically generated from SQL script by modelgen.py
 */
class Lot {
	public string $number;
	public string $productionDate;
	public string $expiryDate;
	public int $doses;
	public string $company;
	public string $site;

	public function __construct($number, $productionDate, $expiryDate, $doses, $company, $site, ) {
		$this->number = $number;
		$this->productionDate = $productionDate;
		$this->expiryDate = $expiryDate;
		$this->doses = $doses;
		$this->company = $company;
		$this->site = $site;
	}

	public function toAssoc() {
		return get_object_vars($this);
	}

	public static function fromAssoc($assoc) {
		return new Lot(
			$assoc["number"],
			$assoc["productionDate"],
			$assoc["expiryDate"],
			$assoc["doses"],
			$assoc["company"],
			$assoc["site"],
		);
	}
}