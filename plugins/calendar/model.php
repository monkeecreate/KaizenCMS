<?php
class calendar_model extends appModel {
	public $useImage = true;
	public $imageMinWidth = 320;
	public $imageMinHeight = 200;
	public $imageFolder = "/uploads/calendar/";
	public $useCategories = true;
	public $perPage = 5;
	public $shortContentCharacters = 250; // max characters for short content
	
	function getEvents($sCategory = null, $sAll = false) {
		// Start the WHERE
		$sWhere = " WHERE `calendar`.`id` > 0";// Allways true
		
		if($sAll == false) {
			$sWhere .= " AND `calendar`.`datetime_show` < ".time();
			$sWhere .= " AND (`calendar`.`use_kill` = 0 OR `calendar`.`datetime_kill` > ".time().")";
			$sWhere .= " AND `calendar`.`datetime_end` > ".time();
			$sWhere .= " AND `calendar`.`active` = 1";
		}
		
		if(!empty($sCategory))
			$sWhere .= " AND `categories`.`id` = ".$this->dbQuote($sCategory, "integer");
		
		$aEvents = $this->dbQuery(
			"SELECT `calendar`.* FROM `{dbPrefix}calendar` AS `calendar`"
				." LEFT JOIN `{dbPrefix}calendar_categories_assign` AS `calendar_assign` ON `calendar`.`id` = `calendar_assign`.`eventid`"
				." LEFT JOIN `{dbPrefix}calendar_categories` AS `categories` ON `calendar_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `calendar`.`id`"
				." ORDER BY `calendar`.`datetime_start`"
			,"all"
		);
	
		foreach($aEvents as $x => &$aEvent)
			$aEvent = $this->_getEventInfo($aEvent);
		
		return $aEvents;
	}
	function getEvent($sId, $sTag = "", $sAll = false) {
		if(!empty($sId))
			$sWhere = " WHERE `calendar`.`id` = ".$this->dbQuote($sId, "integer");
		else
			$sWhere = " WHERE `calendar`.`tag` = ".$this->dbQuote($sTag, "text");
		
		if($sAll == false) {
			$sWhere .= " AND `calendar`.`active` = 1";
			$sWhere .= " AND `calendar`.`datetime_show` < ".time();
			$sWhere .= " AND (`calendar`.`use_kill` = 0 OR `calendar`.`datetime_kill` > ".time().")";
			$sWhere .= " AND `calendar`.`datetime_end` > ".time();
		}
		
		$aEvent = $this->dbQuery(
			"SELECT `calendar`.* FROM `{dbPrefix}calendar` AS `calendar`"
				.$sWhere
			,"row"
		);
		
		if(!empty($aEvent))
			$aEvent = $this->_getEventInfo($aEvent);
		
		return $aEvent;
	}
	private function _getEventInfo($aEvent) {
		$aEvent["title"] = htmlspecialchars(stripslashes($aEvent["title"]));
		if(!empty($aEvent["short_content"]))
			$aEvent["short_content"] = nl2br(htmlspecialchars(stripslashes($aEvent["short_content"])));
		else
			$aEvent["short_content"] = (string)substr(nl2br(htmlspecialchars(stripslashes(strip_tags($aEvent["content"])))), 0, $this->shortContentCharacters);
		$aEvent["content"] = stripslashes($aEvent["content"]);
		
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
			
		return $aEvent;
	}
	function getURL($sID) {
		$aEvent = $this->getEvent($sID);
		
		$sTitle = strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($aEvent["title"])))));
		
		if(strlen($sURL) > 50)
			$sTitle = substr($sTitle, 0, 50)."...";
		
		$sURL = "/events/".$aEvent["id"]."/".$sTitle."/";
		
		return $sURL;
	}
	function getCategories($sEmpty = true) {
		if($sEmpty == true) {		
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}calendar_categories`"
					." ORDER BY `name`"
				,"all"
			);
		
			foreach($aCategories as &$aCategory) {
				$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
			}
		} else {
			$aCategories = $this->dbQuery(
				"SELECT DISTINCT(`categoryid`) FROM `{dbPrefix}calendar_categories_assign`"
				,"all"
			);
			
			foreach($aCategories as $x => $aCategory)
				$aCategories[$x] = $this->getCategory($aCategory["categoryid"]);
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
			"SELECT * FROM `{dbPrefix}calendar_categories`"
				.$sWhere
			,"row"
		);
		
		$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		
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