<?php
class promos_model extends appModel
{
	function getPromo($sTag)
	{
		$aPromo = $this->db_results(
			"SELECT `promos`.* FROM `promos`"
				." INNER JOIN `promos_positions_assign` AS `assign` ON `promos`.`id` = `assign`.`promoid`"
				." INNER JOIN `promos_positions` AS `positions` ON `assign`.`positionid` = `positions`.`id`"
				." WHERE `positions`.`tag` = ".$this->db_quote($sTag, "text")
				." AND `promos`.`datetime_show` < ".time()
				." AND (`promos`.`datetime_kill` > ".time()." OR `promos`.`use_kill` = 0)"
				." ORDER BY rand()"
				." LIMIT 1"
			,"smarty->getPromos->promo"
			,"row"
		);
		
		if(!empty($aPromo))
		{
			$this->db_results(
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
		$aPosition = $this->db_results(
			"SELECT * FROM `promos_positions`"
				." WHERE `tag` = ".$this->db_quote($sTag, "text")
			,"model->promos->getPosition"
			,"row"
		);
		
		return $aPosition;
	}
	function trackClick($sId)
	{
		
	}
}