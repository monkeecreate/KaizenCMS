<?php
class image extends appController
{
	### DISPLAY ######################
	function resize()
	{
		$sFile = $this->_settings->root_public.substr($_GET["file"], 1);
		
		if(filesize($sFile) == 0 || empty($_GET["width"]) || empty($_GET["height"]))
			$this->error('404');
		
		if(!is_numeric($_GET["width"]) || !is_numeric($_GET["height"]))
			$this->error('505');
		
		$sNewWidth = $_GET["width"];
		$sNewHeight = $_GET["height"];
		
		$oImage = new makeImage($sFile, true);
		$oImage->resize($sNewWidth, $sNewHeight);
		$oImage->draw(null, 85);
	}
	function itemImage($aParams)
	{
		$oModel = $this->loadModel($aParams["model"]);
		
		if(empty($oModel))
			$this->error("404");
		
		$aImage = $oModel->getImage($aParams["id"]);
		
		if(empty($aImage))
			$this->error("404");
		
		$oImage = new makeImage($aImage["file"], true);
		$oImage->crop($aImage["info"]["photo_width"], $aImage["info"]["photo_height"], $aImage["info"]["photo_x1"], $aImage["info"]["photo_y1"]);
		$oImage->resize($oModel->imageMinWidth, $oModel->imageMinHeight, true);
		
		if(!empty($_GET["width"]) && $_GET["width"] <= $oModel->imageMinWidth)
			$oImage->resize($_GET["width"], $_GET["width"]);
			
		$oImage->draw(null, 85);
	}
	##################################
} 