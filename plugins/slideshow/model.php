<?php
class slideshow_model extends appModel {
	public $useImage = true;
	public $imageMinWidth = 262;
	public $imageMinHeight = 100;
	public $imageFolder = "/uploads/slideshow/";
	public $shortContentCharacters = 250; // max characters for short content
	public $useDescription = true;
	
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