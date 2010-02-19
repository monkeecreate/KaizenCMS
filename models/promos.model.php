<?php
class promos_model extends appModel
{
	function getPromo($sTag, $sId = null, $sUsed = null)
	{
		if(!empty($sTag))
			$sWhere = " WHERE `positions`.`tag` = ".$this->dbQuote($sTag, "text");
		elseif(!empty($sId))
			$sWhere = " WHERE `positions`.`tag` = ".$this->dbQuote($sTag, "text");
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
		
		if(!empty($aPromo))
		{
			$this->dbResults(
				"UPDATE `promos` SET"
					." `impressions` = `impressions` + 1"
					." WHERE `id` = ".$aPromo["id"]
			);
			
			$this->_settings->displayedPromos[] = $aPromo["id"];
		}
		
		return $aPromo;
	}
	function getPosition($sTag)
	{
		$aPosition = $this->dbResults(
			"SELECT * FROM `promos_positions`"
				." WHERE `tag` = ".$this->dbQuote($sTag, "text")
			,"row"
		);
		
		return $aPosition;
	}
	function trackClick($sId)
	{
		
	}
}