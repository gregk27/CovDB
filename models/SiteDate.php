<?php

/**
 * Class representing a SiteDate
 * Automatically generated from SQL script by modelgen.py
 */
class SiteDate {
	public string $site;
	public int $date;

	public function __construct($site, $date, ) {
		$this->site = $site;
		$this->date = $date;
	}

	public function fromAssoc($assoc) {
		return new SiteDate(
			$assoc["site"],
			$assoc["date"],
		)
	}
}