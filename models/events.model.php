<?php
class events_model extends appModel
{
	public $imageMinWidth = 320;
	public $imageMinHeight = 200;
	public $perPage = 5;
	
	function getEvents($sCategory)
	{
		$sWhere = " WHERE `events`.`datetime_show` < ".time()." AND (`events`.`use_kill` = 0 OR `events`.`datetime_kill` > ".time().")";
		$sWhere .= " AND `events`.`datetime_end` > ".time();
		$sWhere .= " AND `events`.`active` = 1";
		if(!empty($sCategory))
			$sWhere .= " AND `categories`.`id` = ".$this->dbQuote($sCategory, "integer");
		
		// Get all events for paging
		$aEvents = $this->dbResults(
			"SELECT `events`.* FROM `events` AS `events`"
				." INNER JOIN `events_categories_assign` AS `events_assign` ON `events`.`id` = `events_assign`.`eventid`"
				." INNER JOIN `events_categories` AS `categories` ON `events_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `events`.`id`"
				." ORDER BY `events`.`datetime_start`"
			,"model->events->getEvents"
			,"all"
		);
		
		foreach($aEvents as $x => $aEvent)
		{
			$aEventCategories = $this->dbResults(
				"SELECT `name` FROM `events_categories` AS `categories`"
					." INNER JOIN `events_categories_assign` AS `events_assign` ON `events_assign`.`categoryid` = `categories`.`id`"
					." WHERE `events_assign`.`eventid` = ".$aEvent["id"]
				,"model->events->getEvents->event_categories"
				,"col"
			);
		
			$aEvents[$x]["categories"] = implode(", ", $aEventCategories);
			
			if(file_exists($this->_settings->root_public."upload/events/".$aEvent["id"].".jpg"))
				$aEvents[$x]["image"] = 1;
		}
		
		return $aEvents;
	}
	function getEvent($sId)
	{
		$aEvent = $this->dbResults(
			"SELECT `events`.* FROM `events` AS `events`"
				." WHERE `events`.`id` = ".$this->dbQuote($sId, "integer")
				." AND `events`.`active` = 1"
				." AND `events`.`datetime_show` < ".time()
				." AND (`events`.`use_kill` = 0 OR `events`.`datetime_kill` > ".time().")"
			,"model->events->getEvent"
			,"row"
		);
		
		if(!empty($aEvent))
		{
			$aCategories = $this->dbResults(
				"SELECT `name` FROM `events_categories` AS `category`"
					." INNER JOIN `events_categories_assign` AS `events_assign` ON `events_assign`.`categoryid` = `category`.`id`"
					." WHERE `events_assign`.`eventid` = ".$aEvent["id"]
				,"model->events->getEvent->categories"
				,"col"
			);
			
			$aEvent["categories"] = implode(", ", $aCategories);
			
			if(file_exists($this->_settings->root_public."upload/events/".$aEvent["id"].".jpg"))
				$aEvent["image"] = 1;
		}
		
		return $aEvent;
	}
	function getCategories()
	{
		$aCategories = $this->dbResults(
			"SELECT * FROM `events_categories`"
				." ORDER BY `name`"
			,"model->events->getCategories"
			,"all"
		);
		
		return $aCategories;
	}
	function getImage($sId)
	{
		
	}
}