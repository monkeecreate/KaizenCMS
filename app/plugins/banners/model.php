<?php
class banners_model extends appModel {
	public $imageFolder;
	public $shortContentCharacters;
	public $useDescription;

	function __construct() {
		parent::__construct();

		include(dirname(__file__)."/config.php");

		foreach($aPluginInfo["config"] as $sKey => $sValue) {
			$this->$sKey = $sValue;
		}
	}

	function getBanners($sPosition = null) {
		if(!empty($sPosition))
		{
			$sSQLPosition = " INNER JOIN `{dbPrefix}banners_positions_assign` AS `assign` ON `banners`.`id` = `assign`.`promoid`";
			$sSQLPosition .= " WHERE `assign`.`positionid` = ".$this->dbQuote($sPosition, "integer");
		}

		$aBanners = $this->dbQuery(
			"SELECT `banners`.* FROM `{dbPrefix}banners` AS `banners`"
				.$sSQLPosition
				." ORDER BY `banners`.`datetime_show` DESC"
			,"all"
		);

		foreach($aBanners as &$aBanner) {
			$aBanner["name"] = htmlspecialchars(stripslashes($aBanner["name"]));
		}

		return $aBanners;
	}
	function getBanner($sTag = null, $sId = null, $sUsed = null, $sBannerId = null, $sAll = false, $sImpression = true) {
		if(!empty($sTag))
			$sWhere = " WHERE `positions`.`tag` = ".$this->dbQuote($sTag, "text");
		elseif(!empty($sId))
			$sWhere = " WHERE `positions`.`id` = ".$this->dbQuote($sId, "integer");
		elseif(!empty($sBannerId))
			$sWhere = " WHERE `banners`.`id` = ".$this->dbQuote($sBannerId, "integer");
		else
			return false;

		if(!empty($sUsed))
			$sWhere .= " AND `banners`.`id` NOT IN (".$sUsed.")";

		if($sAll == false) {
			$sWhere .= " AND `banners`.`datetime_show` < ".time();
			$sWhere .= " AND (`banners`.`datetime_kill` > ".time()." OR `banners`.`use_kill` = 0)";
			$sWhere .= " AND `active` = 1";
		}

		$aBanner = $this->dbQuery(
			"SELECT `banners`.* FROM `{dbPrefix}banners` AS `banners`"
				." INNER JOIN `{dbPrefix}banners_positions_assign` AS `assign` ON `banners`.`id` = `assign`.`promoid`"
				." INNER JOIN `{dbPrefix}banners_positions` AS `positions` ON `assign`.`positionid` = `positions`.`id`"
				.$sWhere
				." ORDER BY rand()"
				." LIMIT 1"
			,"row"
		);

		$aBanner = $this->_getBannerPosition($aBanner);

		if(!empty($aBanner) && $sImpression == true) {
			$this->dbUpdate(
				"banners",
				array(
					"impressions" => ($aBanner["impressions"] + 1)
				),
				$aBanner["id"]
			);

			$this->settings->displayedBanners[] = $aBanner["id"];
		}

		return $aBanner;
	}
	private function _getBannerPosition($aBanner) {
		if(!empty($aBanner)) {
			$aBanner["name"] = htmlspecialchars(stripslashes($aBanner["name"]));
		}

		return $aBanner;
	}
	function getPositions() {
		$aPositions = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}banners_positions`"
				." ORDER BY `name`"
			,"all"
		);

		foreach($aPositions as &$aPosition) {
			$aPosition["name"] = htmlspecialchars(stripslashes($aPosition["name"]));
		}

		return $aPositions;
	}
	function getPosition($sTag = null, $sId = null) {
		if(!empty($sId))
			$sWhere = " WHERE `id` = ".$this->dbQuote($sId, "integer");
		elseif(!empty($sTag))
			$sWhere = " WHERE `tag` LIKE ".$this->dbQuote($sTag, "text");
		else
			return false;

		$aPosition = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}banners_positions`"
				.$sWhere
			,"row"
		);

		$aPosition["name"] = htmlspecialchars(stripslashes($aPosition["name"]));

		return $aPosition;
	}
	function trackClick($sId) {

	}
}