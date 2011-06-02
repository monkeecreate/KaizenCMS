<?php
class promos_model extends appModel {
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
	
	function getPromos($sPosition = null) {
		if(!empty($sPosition))
		{
			$sSQLPosition = " INNER JOIN `{dbPrefix}promos_positions_assign` AS `assign` ON `promos`.`id` = `assign`.`promoid`";
			$sSQLPosition .= " WHERE `assign`.`positionid` = ".$this->dbQuote($sPosition, "integer");
		}
		
		$aPromos = $this->dbQuery(
			"SELECT `promos`.* FROM `{dbPrefix}promos` AS `promos`"
				.$sSQLPosition
				." ORDER BY `promos`.`datetime_show` DESC"
			,"all"
		);
		
		foreach($aPromos as &$aPromo) {
			$aPromo["name"] = htmlspecialchars(stripslashes($aPromo["name"]));
		}
		
		return $aPromos;
	}	
	function getPromo($sTag = null, $sId = null, $sUsed = null, $sPromoId = null, $sAll = false, $sImpression = true) {
		if(!empty($sTag))
			$sWhere = " WHERE `positions`.`tag` = ".$this->dbQuote($sTag, "text");
		elseif(!empty($sId))
			$sWhere = " WHERE `positions`.`id` = ".$this->dbQuote($sId, "integer");
		elseif(!empty($sPromoId))
			$sWhere = " WHERE `promos`.`id` = ".$this->dbQuote($sPromoId, "integer");
		else
			return false;
		
		if(!empty($sUsed))
			$sWhere .= " AND `promos`.`id` NOT IN (".$sUsed.")";
		
		if($sAll == false) {
			$sWhere .= " AND `promos`.`datetime_show` < ".time();
			$sWhere .= " AND (`promos`.`datetime_kill` > ".time()." OR `promos`.`use_kill` = 0)";
			$sWhere .= " AND `active` = 1";
		}
		
		$aPromo = $this->dbQuery(
			"SELECT `promos`.* FROM `{dbPrefix}promos` AS `promos`"
				." INNER JOIN `{dbPrefix}promos_positions_assign` AS `assign` ON `promos`.`id` = `assign`.`promoid`"
				." INNER JOIN `{dbPrefix}promos_positions` AS `positions` ON `assign`.`positionid` = `positions`.`id`"
				.$sWhere
				." ORDER BY rand()"
				." LIMIT 1"
			,"row"
		);
		
		$aPromo = $this->_getPromoPosition($aPromo);
		
		if(!empty($aPromo) && $sImpression == true) {
			$this->dbUpdate(
				"promos",
				array(
					"impressions" => ($aPromo["impressions"] + 1)
				),
				$aPromo["id"]
			);
			
			$this->settings->displayedPromos[] = $aPromo["id"];
		}
		
		return $aPromo;
	}
	private function _getPromoPosition($aPromo) {
		if(!empty($aPromo)) {
			$aPromo["name"] = htmlspecialchars(stripslashes($aPromo["name"]));
		}
		
		return $aPromo;
	}
	function getPositions() {
		$aPositions = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}promos_positions`"
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
			"SELECT * FROM `{dbPrefix}promos_positions`"
				.$sWhere
			,"row"
		);
		
		$aPosition["name"] = htmlspecialchars(stripslashes($aPosition["name"]));
		
		return $aPosition;
	}
	function trackClick($sId) {
		
	}
}