<?php
class promos_model extends appModel
{
	public $imageFolder = "/uploads/promos/";
	
	function getPromos($sPosition = null) {
		if(!empty($sPosition))
		{
			$sSQLPosition = " INNER JOIN `promos_positions_assign` AS `assign` ON `promos`.`id` = `assign`.`promoid`";
			$sSQLPosition .= " WHERE `assign`.`positionid` = ".$this->dbQuote($sPosition, "integer");
		}
		
		$aPromos = $this->dbResults(
			"SELECT `promos`.* FROM `promos`"
				.$sSQLPosition
				." ORDER BY `promos`.`datetime_show` DESC"
			,"all"
		);
		
		return $aPromos;
	}	
	function getPromo($sTag = null, $sId = null, $sUsed = null, $sPromoId = null) {
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
		
		$aPromo = $this->dbResults(
			"SELECT `promos`.* FROM `promos`"
				." INNER JOIN `promos_positions_assign` AS `assign` ON `promos`.`id` = `assign`.`promoid`"
				." INNER JOIN `promos_positions` AS `positions` ON `assign`.`positionid` = `positions`.`id`"
				.$sWhere
				." AND `promos`.`datetime_show` < ".time()
				." AND (`promos`.`datetime_kill` > ".time()." OR `promos`.`use_kill` = 0)"
				." AND `active` = 1"
				." ORDER BY rand()"
				." LIMIT 1"
			,"row"
		);
		
		if(!empty($aPromo)) {
			$this->dbResults(
				"UPDATE `promos` SET"
					." `impressions` = `impressions` + 1"
					." WHERE `id` = ".$aPromo["id"]
			);
			
			$this->_settings->displayedPromos[] = $aPromo["id"];
		}
		
		return $aPromo;
	}
	function getPositions() {
		$aPositions = $this->dbResults(
			"SELECT * FROM `promos_positions`"
				." ORDER BY `name`" 
			,"all"
		);
		
		return $aPositions;
	}
	function getPosition($sTag) {
		$aPosition = $this->dbResults(
			"SELECT * FROM `promos_positions`"
				." WHERE `tag` = ".$this->dbQuote($sTag, "text")
			,"row"
		);
		
		return $aPosition;
	}
	function trackClick($sId) {
		
	}
}