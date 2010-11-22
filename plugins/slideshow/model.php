<?php
class slideshow_model extends appModel {
	public $useImage;
	public $imageMinWidth;
	public $imageMinHeight;
	public $imageFolder;
	public $shortContentCharacters;
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
		
		$aSlides = $this->dbQuery(
			"SELECT `slideshow`.* FROM `{dbPrefix}slideshow` AS `slideshow`"
				.$sWhere
			,"all"
		);
		
		return $aSlides;
	}
	function getSlide($sId) {	
		$aImage = $this->dbQuery(
			"SELECT `slideshow`.* FROM `{dbPrefix}slideshow` AS `slideshow`"
				." WHERE `slideshow`.`id` = ".$this->dbQuote($sId, "integer")
			,"row"
		);
		
		return $aImage;
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