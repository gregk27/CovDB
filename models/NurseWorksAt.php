<?php

/**
 * Class representing a NurseWorksAt
 * Automatically generated from SQL script by modelgen.py
 */
class NurseWorksAt {
	public int $nurse;
	public string $site;

	public function __construct($nurse, $site, ) {
		$this->nurse = $nurse;
		$this->site = $site;
	}

	public function toAssoc() {
		return get_object_vars($this);
	}

	public static function fromAssoc($assoc) {
		return new NurseWorksAt(
			$assoc["nurse"],
			$assoc["site"],
		);
	}
}