<?php
class events extends appModel
{
	public $useImage = true;
	public $imageMinWidth = 320;
	public $imageMinHeight = 200;
	public $imageFolder = "/uploads/events/";
	public $perPage = 5;
	
	function getEvents($sCategory, $sAll = false)
	{
		// Start the WHERE
		$sWhere = " WHERE `events`.`id` > 0";// Allways true
		
		if($sAll == false)
		{
			$sWhere .= " AND `events`.`datetime_show` < ".time();
			$sWhere .= " AND (`events`.`use_kill` = 0 OR `events`.`datetime_kill` > ".time().")";
			$sWhere .= " AND `events`.`datetime_end` > ".time();
			$sWhere .= " AND `events`.`active` = 1";
		}
		
		if(!empty($sCategory))
			$sWhere .= " AND `categories`.`id` = ".$this->dbQuote($sCategory, "integer");
		
		$aEvents = $this->dbResults(
			"SELECT `events`.* FROM `events` AS `events`"
				." INNER JOIN `events_categories_assign` AS `events_assign` ON `events`.`id` = `events_assign`.`eventid`"
				." INNER JOIN `events_categories` AS `categories` ON `events_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `events`.`id`"
				." ORDER BY `events`.`datetime_start`"
			,"all"
		);
		
		foreach($aEvents as $x => $aEvent)
			$aEvents[$x] = $this->getEventInfo($aEvent);
		
		return $aEvents;
	}
	function getEvent($sId, $sAll = false)
	{
		if($sAll == false)
		{
			$sWhere .= " AND `events`.`active` = 1";
			$sWhere .= " AND `events`.`datetime_show` < ".time();
			$sWhere .= " AND (`events`.`use_kill` = 0 OR `events`.`datetime_kill` > ".time().")";
		}
		
		$aEvent = $this->dbResults(
			"SELECT `events`.* FROM `events` AS `events`"
				." WHERE `events`.`id` = ".$this->dbQuote($sId, "integer")
				.$sWhere
			,"row"
		);
		
		if(!empty($aEvent))
			$aEvent = $this->getEventInfo($aEvent);
			
		return $aEvent;
	}
	function getEventInfo($aEvent)
	{
		$aCategories = $this->dbResults(
			"SELECT `name` FROM `events_categories` AS `categories`"
				." INNER JOIN `events_categories_assign` AS `events_assign` ON `events_assign`.`categoryid` = `categories`.`id`"
				." WHERE `events_assign`.`eventid` = ".$aEvent["id"]
			,"col"
		);
	
		$aEvent["categories"] = implode(", ", $aCategories);
		
		$aEvent["datetime_start_date"] = date("m/d/Y", $aEvent["datetime_start"]);
		$aEvent["datetime_end_date"] = date("m/d/Y", $aEvent["datetime_end"]);
		$aEvent["datetime_show_date"] = date("m/d/Y", $aEvent["datetime_show"]);
		$aEvent["datetime_kill_date"] = date("m/d/Y", $aEvent["datetime_kill"]);
		
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
			"SELECT * FROM `events_categories`"
				." ORDER BY `name`"
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
			"SELECT * FROM `events_categories`"
				.$sWhere
				." LIMIT 1"
			,"all"
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