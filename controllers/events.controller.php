<?php
class events extends appController
{
	function index()
	{
		$sPerPage = 5;
		
		## FIND CATEGORIES ##
		$aCategories = $this->db_results(
			"SELECT * FROM `events_categories`"
				." ORDER BY `name`"
			,"events->get_categories->categories"
			,"all"
		);
		
		## GET CURRENT PAGE NEWS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$sWhere = " WHERE `events`.`datetime_show` < ".time()." AND (`events`.`use_kill` = 0 OR `events`.`datetime_kill` > ".time().")";
		$sWhere .= " AND `events`.`datetime_end` > ".time();
		$sWhere .= " AND `events`.`active` = 1";
		if(!empty($_GET["category"]))
			$sWhere .= " AND `categories`.`id` = ".$this->db_quote($_GET["category"], "integer");
		
		// Get all events for paging
		$aEvents = $this->db_results(
			"SELECT `events`.* FROM `events` AS `events`"
				." INNER JOIN `events_categories_assign` AS `events_assign` ON `events`.`id` = `events_assign`.`eventid`"
				." INNER JOIN `events_categories` AS `categories` ON `events_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `events`.`id`"
			,"events->all_events_pages"
			,"all"
		);
		
		$oPage = new Paginate($sPerPage, count($aEvents), $sCurrentPage);
	
		$start = $oPage->get_start();
		
		$aEvents = $this->db_results(
			"SELECT `events`.* FROM `events` AS `events`"
				." INNER JOIN `events_categories_assign` AS `events_assign` ON `events`.`id` = `events_assign`.`eventid`"
				." INNER JOIN `events_categories` AS `categories` ON `events_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `events`.`id`"
				." ORDER BY `events`.`datetime_start` ASC"
				." LIMIT ".$start.",".$sPerPage
			,"events->current_page"
			,"all"
		);
	
		foreach($aEvents as $x => $aEvent)
		{
			/*# Categories #*/
			$aEventCategories = $this->db_results(
				"SELECT `name` FROM `events_categories` AS `categories`"
					." INNER JOIN `events_categories_assign` AS `events_assign` ON `events_assign`.`categoryid` = `categories`.`id`"
					." WHERE `events_assign`.`eventid` = ".$aEvent["id"]
				,"events->event_categories"
				,"col"
			);
		
			$aEvents[$x]["categories"] = implode(", ", $aEventCategories);
			/*# Categories #*/
		
			/*# Image #*/
			if(file_exists($this->_settings->root_public."upload/events/".$aEvent["id"].".jpg"))
				$aEvents[$x]["image"] = 1;
			/*# Image #*/
		}

		$this->tpl_assign("aCategories", $aCategories);
		$this->tpl_assign("aEvents", $aEvents);
		$this->tpl_assign("aPaging", $oPage->build_array());
		
		$this->tpl_display("events/index.tpl");
	}
	function event($aParams)
	{
		$aEvent = $this->db_results(
			"SELECT `events`.* FROM `events` AS `events`"
				." WHERE `events`.`id` = ".$this->db_quote($aParams["id"], "integer")
				." AND `events`.`active` = 1"
				." AND `events`.`datetime_show` < ".time()
				." AND (`events`.`use_kill` = 0 OR `events`.`datetime_kill` > ".time().")"
			,"events->event"
			,"row"
		);
		
		if(empty($aEvent))
			$this->error('404');

		$aCategories = $this->db_results(
			"SELECT `name` FROM `events_categories` AS `category`"
				." INNER JOIN `events_categories_assign` AS `events_assign` ON `events_assign`.`categoryid` = `category`.`id`"
				." WHERE `events_assign`.`eventid` = ".$aEvent["id"]
			,"events->event->categories"
			,"col"
		);

		$aEvent["categories"] = implode(", ", $aCategories);
		
		/*# Image #*/
		if(file_exists($this->_settings->root_public."upload/events/".$aEvent["id"].".jpg"))
			$aEvent["image"] = 1;
		/*# Image #*/

		$this->tpl_assign("aEvent", $aEvent);
		
		if(!empty($aEvent["template"]))
			$this->tpl_display("events/tpl/".$aEvent["template"]);
		else
			$this->tpl_display("events/event.tpl");
	}
}