<?php
class links_model extends appModel {
	public $useImage;
	public $imageMinWidth;
	public $imageMinHeight;
	public $imageFolder;
	public $useCategories;
	public $perPage;
	public $sort;
	public $sortCategory;

	function __construct() {
		parent::__construct();

		include(dirname(__file__)."/config.php");

		foreach($aPluginInfo["config"] as $sKey => $sValue) {
			$this->$sKey = $sValue;
		}
	}

	function getLinks($sCategory = null, $sAll = false, $sRandom = false) {
		$aWhere = array();
		$sJoin = "";

		// Filter those that are only active, unless told otherwise
		if($sAll == false) {
			$aWhere[] = "`links`.`active` = 1";
		}

		// Filter by category if given
		if(!empty($sCategory)) {
			$aWhere[] = "`categories`.`id` = ".$this->dbQuote($sCategory, "integer");
			$sJoin .= " LEFT JOIN `{dbPrefix}links_categories_assign` AS `links_assign` ON `links`.`id` = `links_assign`.`linkid`";
			$sJoin .= " LEFT JOIN `{dbPrefix}links_categories` AS `categories` ON `links_assign`.`categoryid` = `categories`.`id`";
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

		if($sRandom == true)
			$sOrderBy = " ORDER BY RAND() ";

		// Get all links based on filters given
		$aLinks = $this->dbQuery(
			"SELECT `links`.* FROM `{dbPrefix}links` AS `links`"
				.$sJoin
				.$sWhere
				." GROUP BY `links`.`id`"
				.$sOrderBy
			,"all"
		);

		foreach($aLinks as $x => &$aLink) {
			$aLink = $this->_getLinkInfo($aLink);
		}

		return $aLinks;
	}
	function getLink($sId, $sTag = null, $sAll = false) {
		if(!empty($sId))
			$sWhere = " WHERE `id` = ".$this->dbQuote($sId, "integer");
		else
			$sWhere = " WHERE `tag` = ".$this->dbQuote($sTag, "text");

		if($sAll == false)
			$sWhere .= " AND `active` = 1";

		$aLink = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}links`"
				.$sWhere
			,"row"
		);

		$aLink = $this->_getLinkInfo($aLink);

		return $aLink;
	}
	private function _getLinkInfo($aLink) {
		if(!empty($aLink)) {
			$aLink["name"] = htmlspecialchars(stripslashes($aLink["name"]));
			$aLink["description"] = nl2br(htmlspecialchars(stripslashes($aLink["description"])));
			$aLink["url"] = "/links/".$aLink["tag"]."/";

			$aLink["categories"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}links_categories` AS `categories`"
					." INNER JOIN `{dbPrefix}links_categories_assign` AS `links_assign` ON `links_assign`.`categoryid` = `categories`.`id`"
					." WHERE `links_assign`.`linkid` = ".$aLink["id"]
				,"all"
			);

			foreach($aLink["categories"] as &$aCategory) {
				$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
			}

			if(file_exists($this->settings->rootPublic.substr($this->imageFolder, 1).$aLink["id"].".jpg")
			 && $aLink["photo_x2"] > 0
			 && $this->useImage == true) {
				$aLink["image"] = 1;
			} else {
				$aLink["image"] = 0;
			}
		}

		return $aLink;
	}
	function getURL($sID) {
		$aLink = $this->getLink($sID);

		return $aLink["url"];
	}
	function getCategories($sEmpty = true) {
		$sJoin = "";

		if($sEmpty == false) {
			$sJoin .= " INNER JOIN `{dbPrefix}links_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`";
		} else {
			$sJoin .= " LEFT JOIN `{dbPrefix}links_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`";
		}

		// Check if sort direction is set, and clean it up for SQL use
		$sSortDirection = array_pop(explode("-", $this->sortCategory));
		if(empty($sSortDirection) || !in_array(strtolower($sSortDirection), array("asc", "desc"))) {
			$sSortDirection = "ASC";
		} else {
			$sSortDirection = strtoupper($sSortDirection);
		}

		// Choose sort method based on model setting
		switch(array_shift(explode("-", $this->sortCategory))) {
			case "manual":
				$sOrderBy = " ORDER BY `sort_order` ".$sSortDirection;
				break;
			case "items":
				$sOrderBy = " ORDER BY `items` ".$sSortDirection;
				break;
			case "random":
				$sOrderBy = " ORDER BY RAND()";
				break;
			// Default to sort by name
			default:
				$sOrderBy = " ORDER BY `name` ".$sSortDirection;
		}

		$aCategories = $this->dbQuery(
			"SELECT `id`, `name`, `sort_order`, COUNT('categoryid') AS `items` FROM `{dbPrefix}links_categories` AS `categories`"
				.$sJoin
				." GROUP BY `id`"
				.$sOrderBy
			,"all"
		);

		foreach($aCategories as &$aCategory) {
			$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		}

		return $aCategories;
	}
	function getCategory($sId = null, $sName = null) {
		if(!empty($sId)) {
			$sWhere = " WHERE `id` = ".$this->dbQuote($sId, "integer");
		} elseif(!empty($sName)) {
			$sWhere = " WHERE `name` LIKE ".$this->dbQuote($sName, "text");
		} else {
			return false;
		}

		$aCategory = $this->dbQuery(
			"SELECT `id`, `name`, `sort_order`, COUNT('categoryid') AS `items` FROM `{dbPrefix}links_categories` AS `categories`"
				." LEFT JOIN `{dbPrefix}links_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`"
				.$sWhere
			,"row"
		);

		if(!empty($aCategory)) {
			$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		}

		return $aCategory;
	}
	function getImage($sId) {
		$aLink = $this->getLink($sId);

		$sFile = $this->settings->rootPublic.substr($this->imageFolder, 1).$sId.".jpg";

		$aImage = array(
			"file" => $sFile
			,"info" => $aLink
		);

		return $aImage;
	}
}