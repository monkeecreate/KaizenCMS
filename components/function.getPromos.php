<?php
function smarty_function_getPromos($aParams, &$oSmarty)
{
	$oApp = $oSmarty->get_registered_object("appController");
	
	$aPromo = $oApp->db_results(
		"SELECT `promos`.* FROM `promos`"
			." INNER JOIN `promos_positions_assign` AS `assign` ON `promos`.`id` = `assign`.`promoid`"
			." INNER JOIN `promos_positions` AS `positions` ON `assign`.`positionid` = `positions`.`id`"
			." WHERE `positions`.`tag` = ".$oApp->db_quote($aParams["tag"], "text")
			." AND `promos`.`datetime_show` < ".time()
			." AND (`promos`.`datetime_kill` > ".time()." OR `promos`.`use_kill` = 0)"
			." ORDER BY rand()"
			." LIMIT 1"
		,"smarty->getPromos->promo"
		,"row"
	);
	
	if(!empty($aPromo))
	{
		$oApp->db_results(
			"UPDATE `promos` SET"
				." `impressions` = `impressions` + 1"
				." WHERE `id` = ".$aPromo["id"]
			,"smarty->getPromos->promo->impressions"
		);
		
		echo "<a href=\"/promos/".$aPromo["id"]."/\"><img src=\"/uploads/promos/".$aPromo["promo"]."\" /></a>";
	}
}