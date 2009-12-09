<?php
class calendar_model extends appModel
{
	public $imageMinWidth = 320;
	public $imageMinHeight = 200;
	public $perPage = 5;
	
	function getEvents($sCategory = null)
	{
		$sWhere = " WHERE `calendar`.`datetime_show` < ".time();
		$sWHERE .= " AND (`calendar`.`use_kill` = 0 OR `calendar`.`datetime_kill` > ".time().")";
		$sWhere .= " AND `calendar`.`datetime_end` > ".time();
		$sWhere .= " AND `calendar`.`active` = 1";
		if(!empty($_GET["category"]))
			$sWhere .= " AND `categories`.`id` = ".$this->db_quote($sCategory, "integer");
		
		// Get all events for paging
		$aEvents = $this->db_results(
			"SELECT `calendar`.* FROM `calendar` AS `calendar`"
				." INNER JOIN `calendar_categories_assign` AS `calendar_assign` ON `calendar`.`id` = `calendar_assign`.`eventid`"
				." INNER JOIN `calendar_categories` AS `categories` ON `calendar_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `calendar`.`id`"
				." ORDER BY `calendar`.`datetime_start`"
			,"calendar->all_calendar_pages"
			,"all"
		);
	
		foreach($aEvents as $x => $aEvent)
		{
			/*# Categories #*/
			$aEventCategories = $this->db_results(
				"SELECT `name` FROM `calendar_categories` AS `categories`"
					." INNER JOIN `calendar_categories_assign` AS `calendar_assign` ON `calendar_assign`.`categoryid` = `categories`.`id`"
					." WHERE `calendar_assign`.`eventid` = ".$aEvent["id"]
				,"calendar->event_categories"
				,"col"
			);
		
			$aEvents[$x]["categories"] = implode(", ", $aEventCategories);
			/*# Categories #*/
		
			/*# Image #*/
			if(file_exists($this->_settings->root_public."uploads/calendar/".$aEvent["id"].".jpg"))
				$aEvents[$x]["image"] = 1;
			/*# Image #*/
		}
		
		return $aEvents;
	}
	function getEvent($sId)
	{
		$aEvent = $this->db_results(
			"SELECT `calendar`.* FROM `calendar` AS `calendar`"
				." WHERE `calendar`.`id` = ".$this->db_quote($sId, "integer")
				." AND `calendar`.`active` = 1"
				." AND `calendar`.`datetime_show` < ".time()
				." AND (`calendar`.`use_kill` = 0 OR `calendar`.`datetime_kill` > ".time().")"
			,"calendar->event"
			,"row"
		);

		$aCategories = $this->db_results(
			"SELECT `name` FROM `calendar_categories` AS `category`"
				." INNER JOIN `calendar_categories_assign` AS `calendar_assign` ON `calendar_assign`.`categoryid` = `category`.`id`"
				." WHERE `calendar_assign`.`eventid` = ".$aEvent["id"]
			,"calendar->event->categories"
			,"col"
		);

		$aEvent["categories"] = implode(", ", $aCategories);
		
		/*# Image #*/
		if(file_exists($this->_settings->root_public."uploads/calendar/".$aEvent["id"].".jpg"))
			$aEvent["image"] = 1;
		/*# Image #*/
		
		return $aEvent;
	}
	function getCategories()
	{
		$aCategories = $this->db_results(
			"SELECT * FROM `calendar_categories`"
				." ORDER BY `name`"
			,"model->calendar->get_categories"
			,"all"
		);
		
		return $aCategories;
	}
}