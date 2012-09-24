<?php
class services_model extends appModel {
	public $useImage;
	public $imageMinWidth;
	public $imageMinHeight;
	public $imageFolder;
	public $shortContentCharacters;
	public $perPage;
	public $sort;

	function __construct() {
		parent::__construct();

		include(dirname(__file__)."/config.php");

		foreach($aPluginInfo["config"] as $sKey => $sValue) {
			$this->$sKey = $sValue;
		}
	}

	function getServices($sAll = false) {
		$aWhere = array();
		$sJoin = "";

		// Filter those that are only active, unless told otherwise
		if($sAll == false) {
			$aWhere[] = "`services`.`active` = 1";
		}

		// Combine filters if atleast one was added
		if(!empty($aWhere)) {
			$sWhere = " WHERE ".implode(" AND ", $aWhere);
		}

		// Check if sort direction is set, and clean it up for SQL use
		$sSortDirection = array_pop(explode("-", $this->sort));
		if(empty($sSortDirection) || !in_array(strtolower($sSortDirection), array("asc", "desc"))) {
			$sSortDirection = "ASC";
		} else {
			$sSortDirection = strtoupper($sSortDirection);
		}

		// Choose sort method based on model setting
		switch(array_shift(explode("-", $this->sort))) {
			case "manual":
				$sOrderBy = " ORDER BY `sort_order` ".$sSortDirection;
				break;
			case "created":
				$sOrderBy = " ORDER BY `created_datetime` ".$sSortDirection;
				break;
			case "updated":
				$sOrderBy = " ORDER BY `updated_datetime` ".$sSortDirection;
				break;
			case "random":
				$sOrderBy = " ORDER BY RAND()";
				break;
			// Default to sort by name
			default:
				$sOrderBy = " ORDER BY `name` ".$sSortDirection;
		}

		// Get all services based on filters given
		$aServices = $this->dbQuery(
			"SELECT `services`.* FROM `{dbPrefix}services` AS `services`"
				.$sJoin
				.$sWhere
				." GROUP BY `services`.`id`"
				.$sOrderBy
			,"all"
		);

		foreach($aServices as $x => &$aService) {
			$aService = $this->_getServiceInfo($aService);
		}

		return $aServices;
	}
	function getService($sId, $sTag = null, $sAll = false) {
		if(!empty($sId))
			$sWhere = " WHERE `id` = ".$this->dbQuote($sId, "integer");
		else
			$sWhere = " WHERE `tag` = ".$this->dbQuote($sTag, "text");

		if($sAll == false)
			$sWhere .= " AND `active` = 1";

		$aService = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}services`"
				.$sWhere
			,"row"
		);

		$aService = $this->_getServiceInfo($aService);

		return $aService;
	}
	private function _getServiceInfo($aService) {
		if(!empty($aService)) {
			if(!empty($aService["created_by"]))
				$aService["user"] = $this->getUser($aService["created_by"]);

			$aService["title"] = htmlspecialchars(stripslashes($aService["title"]));
			$aService["short_content"] = stripslashes($aService["short_content"]);
			$aService["content"] = stripslashes($aService["content"]);
			$aService["url"] = "/services/".$aService["tag"]."/";

			if(file_exists($this->settings->rootPublic.substr($this->imageFolder, 1).$aService["id"].".jpg") && $aService["photo_x2"] > 0 && $this->useImage == true) {
				$aService["image"] = 1;
			} else {
				$aService["image"] = 0;
			}
		}

		return $aService;
	}
	function getURL($sID) {
		$aService = $this->getService($sID);

		return $aService["url"];
	}
	function getImage($sId) {
		$aService = $this->getService($sId);

		$sFile = $this->settings->rootPublic.substr($this->imageFolder, 1).$sId.".jpg";

		$aImage = array(
			"file" => $sFile
			,"info" => $aService
		);

		return $aImage;
	}
}