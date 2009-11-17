<?php
class calendar extends appController
{
	function index()
	{
		$sPerPage = 5;
		
		## FIND CATEGORIES ##
		$aCategories = $this->db_results(
			"SELECT * FROM `calendar_categories`"
				." ORDER BY `name`"
			,"calendar->get_categories->categories"
			,"all"
		);
		
		## GET CURRENT PAGE EVENTS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$sWhere = " WHERE `calendar`.`datetime_show` < ".time()." AND (`calendar`.`use_kill` = 0 OR `calendar`.`datetime_kill` > ".time().")";
		$sWhere .= " AND `calendar`.`datetime_end` > ".time();
		$sWhere .= " AND `calendar`.`active` = 1";
		if(!empty($_GET["category"]))
			$sWhere .= " AND `categories`.`id` = ".$this->db_quote($_GET["category"], "integer");
		
		// Get all events for paging
		$aEvents = $this->db_results(
			"SELECT `calendar`.* FROM `calendar` AS `calendar`"
				." INNER JOIN `calendar_categories_assign` AS `calendar_assign` ON `calendar`.`id` = `calendar_assign`.`eventid`"
				." INNER JOIN `calendar_categories` AS `categories` ON `calendar_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `calendar`.`id`"
			,"calendar->all_calendar_pages"
			,"all"
		);
		
		$oPage = new Paginate($sPerPage, count($aEvents), $sCurrentPage);
	
		$start = $oPage->get_start();
		
		$aEvents = $this->db_results(
			"SELECT `calendar`.* FROM `calendar` AS `calendar`"
				." INNER JOIN `calendar_categories_assign` AS `calendar_assign` ON `calendar`.`id` = `calendar_assign`.`eventid`"
				." INNER JOIN `calendar_categories` AS `categories` ON `calendar_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `calendar`.`id`"
				." ORDER BY `calendar`.`datetime_start` ASC"
				." LIMIT ".$start.",".$sPerPage
			,"calendar->current_page"
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

		$this->tpl_assign("aCategories", $aCategories);
		$this->tpl_assign("aEvents", $aEvents);
		$this->tpl_assign("aPaging", $oPage->build_array());
		
		$this->tpl_display("calendar/index.tpl");
	}
	function ics()
	{
		$sWhere = " WHERE `calendar`.`datetime_show` < ".time()." AND (`calendar`.`use_kill` = 0 OR `calendar`.`datetime_kill` > ".time().")";
		$sWhere .= " AND `calendar`.`datetime_end` > ".time();
		$sWhere .= " AND `calendar`.`active` = 1";
		if(!empty($_GET["category"]))
			$sWhere .= " AND `categories`.`id` = ".$this->db_quote($_GET["category"], "integer");
		
		$aEvents = $this->db_results(
			"SELECT `calendar`.* FROM `calendar` AS `calendar`"
				." INNER JOIN `calendar_categories_assign` AS `calendar_assign` ON `calendar`.`id` = `calendar_assign`.`eventid`"
				." INNER JOIN `calendar_categories` AS `categories` ON `calendar_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `calendar`.`id`"
				." ORDER BY `calendar`.`datetime_start` ASC"
				." LIMIT 0,15"
			,"calendar->ics"
			,"all"
		);

		$this->tpl_assign("domain", $_SERVER["SERVER_NAME"]);
		$this->tpl_assign("aEvents", $aEvents);
		
		$this->tpl_display("calendar/ics.tpl");
	}
	function event($aParams)
	{
		$aEvent = $this->db_results(
			"SELECT `calendar`.* FROM `calendar` AS `calendar`"
				." WHERE `calendar`.`id` = ".$this->db_quote($aParams["id"], "integer")
				." AND `calendar`.`active` = 1"
				." AND `calendar`.`datetime_show` < ".time()
				." AND (`calendar`.`use_kill` = 0 OR `calendar`.`datetime_kill` > ".time().")"
			,"calendar->event"
			,"row"
		);
		
		if(empty($aEvent))
			$this->error('404');

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

		$this->tpl_assign("aEvent", $aEvent);
		
		$this->tpl_display("calendar/event.tpl");
	}
	function event_ics($aParams)
	{
		$aEvent = $this->db_results(
			"SELECT `calendar`.* FROM `calendar` AS `calendar`"
				." WHERE `calendar`.`id` = ".$this->db_quote($aParams["id"], "integer")
				." AND `calendar`.`active` = 1"
				." AND `calendar`.`datetime_show` < ".time()
				." AND (`calendar`.`use_kill` = 0 OR `calendar`.`datetime_kill` > ".time().")"
			,"calendar->event"
			,"row"
		);
		
		if(empty($aEvent))
			$this->error('404');

		$this->tpl_assign("domain", $_SERVER["SERVER_NAME"]);
		$this->tpl_assign("aEvent", $aEvent);
		
		$this->tpl_display("calendar/event_ics.tpl");
	}
}