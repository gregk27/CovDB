<?php

/**
 * Class representing a SiteDate
 * Automatically generated from SQL script by modelgen.py
 */
class SiteDate {
	public string $site;
	public string $date;

	public function __construct($site, $date, ) {
		$this->site = $site;
		$this->date = $date;
	}

	public function toAssoc() {
		return get_object_vars($this);
	}

	public static function fromAssoc($assoc) {
		return new SiteDate(
			$assoc["site"],
			$assoc["date"],
		);
	}
}