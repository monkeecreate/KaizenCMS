<?php
class calendar_model extends appModel
{
	public $useImage = true;
	public $imageMinWidth = 320;
	public $imageMinHeight = 200;
	public $imageFolder = "/uploads/calendar/";
	public $perPage = 5;
	
	function getEvents($sCategory = null, $sAll = false)
	{
		// Start the WHERE
		$sWhere = " WHERE `calendar`.`id` > 0";// Allways true
		
		if($sAll == false)
		{
			$sWhere .= " AND `calendar`.`datetime_show` < ".time();
			$sWhere .= " AND (`calendar`.`use_kill` = 0 OR `calendar`.`datetime_kill` > ".time().")";
			$sWhere .= " AND `calendar`.`datetime_end` > ".time();
			$sWhere .= " AND `calendar`.`active` = 1";
		}
		
		if(!empty($sCategory))
			$sWhere .= " AND `categories`.`id` = ".$this->dbQuote($sCategory, "integer");
		
		$aEvents = $this->dbResults(
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
			$aEvents[$x] = $this->getEventInfo($aEvent);
		
		return $aEvents;
	}
	function getEvent($sId)
	{
		$aEvent = $this->dbResults(
			"SELECT `calendar`.* FROM `calendar` AS `calendar`"
				." WHERE `calendar`.`id` = ".$this->dbQuote($sId, "integer")
				." AND `calendar`.`active` = 1"
				." AND `calendar`.`datetime_show` < ".time()
				." AND (`calendar`.`use_kill` = 0 OR `calendar`.`datetime_kill` > ".time().")"
			,"calendar->event"
			,"row"
		);
		
		if(!empty($aEvent))
			$aEvent = $this->getEventInfo($aEvent);
		
		return $aEvent;
	}
	private function getEventInfo($aEvent)
	{
		$aCategories = $this->dbResults(
			"SELECT `name` FROM `calendar_categories` AS `category`"
				." INNER JOIN `calendar_categories_assign` AS `calendar_assign` ON `calendar_assign`.`categoryid` = `category`.`id`"
				." WHERE `calendar_assign`.`eventid` = ".$aEvent["id"]
			,"calendar->event->categories"
			,"col"
		);
	
		$aEvent["categories"] = implode(", ", $aCategories);
	
		if(file_exists($this->_settings->rootPublic.substr($this->imageFolder, 1).$aEvent["id"].".jpg")
		 && $aEvent["photo_x2"] > 0
		 && $this->useImage == true)
			$aEvent["image"] = 1;
		else
			$aEvent["image"] = 0;
			
		return $aEvent;
	}
	function getCategories()
	{
		$aCategories = $this->dbResults(
			"SELECT * FROM `calendar_categories`"
				." ORDER BY `name`"
			,"model->calendar->getCategories"
			,"all"
		);
		
		return $aCategories;
	}
	function getCategory($sId = null, $sName = null)
	{
		if(!empty($sId))
			$sWhere = " WHERE `id` = ".$this->dbQuote($sId, "integer");
		elseif(!empty($sName))
			$sWhere = " WHERE `name` LIKE ".$this->dbQuote($sName, "text");
		else
			return false;
		
		$aCategory = $this->dbResults(
			"SELECT * FROM `calendar_categories`"
				.$sWhere
				." LIMIT 1"
			,"model->calendar->getCategory"
		);
		
		return $aCategory;
	}
	function getImage($sId)
	{
		$aEvent = $this->getEvent($sId);
		
		$sFile = $this->_settings->root_public.substr($this->imageFolder, 1).$sId.".jpg";
		
		$aImage = array(
			"file" => $sFile
			,"info" => $aEvent
		);
		
		return $aImage;
	}
}