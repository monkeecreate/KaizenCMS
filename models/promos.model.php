<?php
class promos_model extends appModel
{
	function getPromo($sTag)
	{
		$aPromo = $this->dbResults(
			"SELECT `promos`.* FROM `promos`"
				." INNER JOIN `promos_positions_assign` AS `assign` ON `promos`.`id` = `assign`.`promoid`"
				." INNER JOIN `promos_positions` AS `positions` ON `assign`.`positionid` = `positions`.`id`"
				." WHERE `positions`.`tag` = ".$this->dbQuote($sTag, "text")
				." AND `promos`.`datetime_show` < ".time()
				." AND (`promos`.`datetime_kill` > ".time()." OR `promos`.`use_kill` = 0)"
				." ORDER BY rand()"
				." LIMIT 1"
			,"smarty->getPromos->promo"
			,"row"
		);
		
		if(!empty($aPromo))
		{
			$this->dbResults(
				"UPDATE `promos` SET"
					." `impressions` = `impressions` + 1"
					." WHERE `id` = ".$aPromo["id"]
				,"smarty->getPromos->promo->impressions"
			);
		}
		
		return $aPromo;
	}
	function getPosition($sTag)
	{
		$aPosition = $this->dbResults(
			"SELECT * FROM `promos_positions`"
				." WHERE `tag` = ".$this->dbQuote($sTag, "text")
			,"model->promos->getPosition"
			,"row"
		);
		
		return $aPosition;
	}
	function trackClick($sId)
	{
		
	}
}