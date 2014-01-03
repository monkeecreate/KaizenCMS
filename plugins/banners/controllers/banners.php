<?php
class banners extends appController {
	function __construct() {
		// Load model when creating appController
		parent::__construct("banners");
	}

	function index() {
		$aPromo = $this->dbQuery(
			"SELECT `banners`.* FROM `{dbPrefix}banners` AS `banners`"
				." WHERE `id` = ".$this->dbQuote($this->urlVars->dynamic["id"], "integer")
			,"row"
		);

		$this->dbUpdate(
			"banners",
			array(
				"clicks" => ($aPromo["clicks"] + 1)
			),
			$aPromo["id"]
		);

		$this->forward($aPromo["link"], "301");
	}
}