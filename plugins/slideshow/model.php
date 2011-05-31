<?php
class slideshow_model extends appModel {
	public $useImage;
	public $imageMinWidth;
	public $imageMinHeight;
	public $imageFolder;
	public $shortContentCharacters;
	public $sort;
	public $useDescription;
	
	function __construct() {
		parent::__construct();
		
		include(dirname(__file__)."/config.php");
		
		foreach($aPluginInfo["config"] as $sKey => $sValue) {
			$this->$sKey = $sValue;
		}
	}
	
	function getSlides($sAll = false) {
		// Start the WHERE
		$sWhere = " WHERE `slideshow`.`id` > 0";// Allways true
		
		if($sAll == false) {
			$sWhere .= " AND `slideshow`.`active` = 1";
		}
		
		// Check if sort direction is set, and clean it up for SQL use
		$sSortDirection = array_pop(explode("-", $this->sort));
		if(empty($sSortDirection) || !in_array(strtolower($sSortDirection), array("asc", "desc"))) {
			$sSortDirection = "ASC";
		} else {
			$sSortDirection = strtoupper($sSortDirection);
		}
		
		// Choose sort method based on model setting
		switch(array_shift(explode("-", $this->sort))) {
			case "manual":
				$sOrderBy = " ORDER BY `sort_order` ".$sSortDirection;
				break;
			case "created":
				$sOrderBy = " ORDER BY `created_datetime` ".$sSortDirection;
				break;
			case "updated":
				$sOrderBy = " ORDER BY `updated_datetime` ".$sSortDirection;
				break;
			case "random":
				$sOrderBy = " ORDER BY RAND()";
				break;
			// Default to sort by name
			default:
				$sOrderBy = " ORDER BY `title` ".$sSortDirection;
		}
		
		$aSlides = $this->dbQuery(
			"SELECT `slideshow`.* FROM `{dbPrefix}slideshow` AS `slideshow`"
				.$sWhere
				." GROUP BY `slideshow`.`id`"
				.$sOrderBy
			,"all"
		);
		
		foreach($aSlides as &$aSlide) {
			$this->_getSlideInfo($aSlide);
		}
		
		return $aSlides;
	}
	function getSlide($sId) {	
		$aSlide = $this->dbQuery(
			"SELECT `slideshow`.* FROM `{dbPrefix}slideshow` AS `slideshow`"
				." WHERE `slideshow`.`id` = ".$this->dbQuote($sId, "integer")
			,"row"
		);
		
		$this->_getSlideInfo($aSlide);
		
		return $aSlide;
	}
	private function _getSlideInfo(&$aSlide) {
		if(!empty($aSlide)) {
			if(!empty($aSlide["created_by"]))
				$aSlide["user"] = $this->getUser($aSlide["created_by"]);
		
			$aSlide["title"] = htmlspecialchars(stripslashes($aSlide["title"]));
			$aSlide["description"] = nl2br(htmlspecialchars(stripslashes($aSlide["description"])));
		}
	}
	function getImage($sId) {
		$aSlide = $this->getSlide($sId);
		
		$sFile = $this->_settings->rootPublic.substr($this->imageFolder, 1).$sId.".jpg";
		
		$aImage = array(
			"file" => $sFile
			,"info" => $aSlide
		);
		
		return $aImage;
	}
}