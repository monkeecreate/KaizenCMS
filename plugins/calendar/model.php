<?php
class calendar_model extends appModel {
	public $useImage;
	public $imageMinWidth;
	public $imageMinHeight;
	public $imageFolder;
	public $useCategories;
	public $perPage;
	public $shortContentCharacters;
	public $calendarView;
	
	function __construct() {
		parent::__construct();
		
		include(dirname(__file__)."/config.php");
		
		foreach($aPluginInfo["config"] as $sKey => $sValue) {
			$this->$sKey = $sValue;
		}
	}
	
	function getEvents($sCategory = null, $sAll = false) {
		$aWhere = array();
		$sJoin = "";
		
		// Filter those that are only active, unless told otherwise
		if($sAll == false) {
			$aWhere[] = "`calendar`.`datetime_show` < ".time();
			$aWhere[] = "(`calendar`.`use_kill` = 0 OR `calendar`.`datetime_kill` > ".time().")";
			$aWhere[] = "`calendar`.`datetime_end` > ".time();
			$aWhere[] = "`calendar`.`active` = 1";
		}
		
		// Filter by category if given
		if(!empty($sCategory)) {
			$aWhere[] = "`categories`.`id` = ".$this->dbQuote($sCategory, "integer");
			$sJoin .= " LEFT JOIN `{dbPrefix}calendar_categories_assign` AS `calendar_assign` ON `calendar`.`id` = `calendar_assign`.`eventid`";
			$sJoin .= " LEFT JOIN `{dbPrefix}calendar_categories` AS `categories` ON `calendar_assign`.`categoryid` = `categories`.`id`";
		}
		
		// Combine filters if atleast one was added
		if(!empty($aWhere)) {
			$sWhere = " WHERE ".implode(" AND ", $aWhere);
		}
		
		$aEvents = $this->dbQuery(
			"SELECT `calendar`.* FROM `{dbPrefix}calendar` AS `calendar`"
				.$sJoin
				.$sWhere
				." GROUP BY `calendar`.`id`"
				." ORDER BY `calendar`.`datetime_start`"
			,"all"
		);
	
		foreach($aEvents as $x => &$aEvent) {
			$aEvent = $this->_getEventInfo($aEvent);
		}
		
		return $aEvents;
	}
	function getEvent($sId, $sTag = null, $sAll = false) {
		if(!empty($sId))
			$sWhere = " WHERE `calendar`.`id` = ".$this->dbQuote($sId, "integer");
		else
			$sWhere = " WHERE `calendar`.`tag` = ".$this->dbQuote($sTag, "text");
		
		if($sAll == false) {
			$sWhere .= " AND `calendar`.`active` = 1";
		//	$sWhere .= " AND `calendar`.`datetime_show` < ".time();
		//	$sWhere .= " AND (`calendar`.`use_kill` = 0 OR `calendar`.`datetime_kill` > ".time().")";
		//	$sWhere .= " AND `calendar`.`datetime_end` > ".time();
		}
		
		$aEvent = $this->dbQuery(
			"SELECT `calendar`.* FROM `{dbPrefix}calendar` AS `calendar`"
				.$sWhere
			,"row"
		);
		
		$aEvent = $this->_getEventInfo($aEvent);
		
		return $aEvent;
	}
	private function _getEventInfo($aEvent) {
		if(!empty($aEvent)) {
			$aEvent["title"] = htmlspecialchars(stripslashes($aEvent["title"]));
			if(!empty($aEvent["short_content"]))
				$aEvent["short_content"] = nl2br(htmlspecialchars(stripslashes($aEvent["short_content"])));
			else
				$aEvent["short_content"] = (string)substr(nl2br(htmlspecialchars(stripslashes(strip_tags($aEvent["content"])))), 0, $this->shortContentCharacters);
			$aEvent["content"] = stripslashes($aEvent["content"]);			
			$aEvent["url"] = "/calendar/".date("Y", $aEvent["datetime_start"])."/".date("m", $aEvent["datetime_start"])."/".date("d", $aEvent["datetime_start"])."/".$aEvent["tag"]."/";
		
			$aEvent["categories"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}calendar_categories` AS `category`"
					." INNER JOIN `{dbPrefix}calendar_categories_assign` AS `calendar_assign` ON `calendar_assign`.`categoryid` = `category`.`id`"
					." WHERE `calendar_assign`.`eventid` = ".$aEvent["id"]
				,"all"
			);
		
			foreach($aEvent["categories"] as &$aCategory) {
				$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
			}
		
			if(file_exists($this->settings->rootPublic.substr($this->imageFolder, 1).$aEvent["id"].".jpg")
			 && $aEvent["photo_x2"] > 0
			 && $this->useImage == true)
				$aEvent["image"] = 1;
			else
				$aEvent["image"] = 0;
		}
			
		return $aEvent;
	}
	function getURL($sID) {
		$aEvent = $this->getEvent($sID);
		
		return $aEvent["url"];
	}
	function getCategories($sEmpty = true) {
		$sJoin = "";
		
		if($sEmpty == false) {		
			$sJoin .= " INNER JOIN `{dbPrefix}calendar_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`";
		} else {
			$sJoin .= " LEFT JOIN `{dbPrefix}calendar_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`";
		}
		
		// Check if sort direction is set, and clean it up for SQL use
		$sSortDirection = array_pop(explode("-", $this->sortCategory));
		if(empty($sSortDirection) || !in_array(strtolower($sSortDirection), array("asc", "desc"))) {
			$sSortDirection = "ASC";
		} else {
			$sSortDirection = strtoupper($sSortDirection);
		}
		
		// Choose sort method based on model setting
		switch(array_shift(explode("-", $this->sortCategory))) {
			case "manual":
				$sOrderBy = " ORDER BY `sort_order` ".$sSortDirection;
				break;
			case "items":
				$sOrderBy = " ORDER BY `items` ".$sSortDirection;
				break;
			case "random":
				$sOrderBy = " ORDER BY RAND()";
				break;
			// Default to sort by name
			default:
				$sOrderBy = " ORDER BY `name` ".$sSortDirection;
		}
		
		$aCategories = $this->dbQuery(
			"SELECT `id`, `name`, `sort_order`, COUNT('categoryid') AS `items` FROM `{dbPrefix}calendar_categories` AS `categories`"
				.$sJoin
				." GROUP BY `id`"
				.$sOrderBy
			,"all"
		);
	
		foreach($aCategories as &$aCategory) {
			$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		}
		
		return $aCategories;
	}
	function getCategory($sId = null, $sName = null) {
		if(!empty($sId))
			$sWhere = " WHERE `id` = ".$this->dbQuote($sId, "integer");
		elseif(!empty($sName))
			$sWhere = " WHERE `name` LIKE ".$this->dbQuote($sName, "text");
		else
			return false;
		
		$aCategory = $this->dbQuery(
			"SELECT `id`, `name`, `sort_order`, COUNT('categoryid') AS `items` FROM `{dbPrefix}calendar_categories` AS `categories`"
				." LEFT JOIN `{dbPrefix}calendar_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`"
				.$sWhere
			,"row"
		);
		
		if(!empty($aCategory)) {
			$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		}
		
		return $aCategory;
	}
	function getImage($sId) {
		$aEvent = $this->getEvent($sId, true);
		
		$sFile = $this->settings->root_public.substr($this->imageFolder, 1).$sId.".jpg";
		
		$aImage = array(
			"file" => $sFile
			,"info" => $aEvent
		);
		
		return $aImage;
	}
}