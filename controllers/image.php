<?php
class image extends appController
{
	### DISPLAY ######################
	function resize() {
		$sFile = $this->settings->rootPublic.substr($_GET["file"], 1);
		
		if(filesize($sFile) == 0)
			$this->error('404');
		
		include($this->settings->root."helpers/makeImage.php");
		$oImage = new makeImage($sFile, false);
		
		if(!empty($_GET["scale"]))
			$oImage->scale($_GET["scale"]);
		elseif(!empty($_GET["width"]) && empty($_GET["height"]))
			$oImage->resizeWidth($_GET["width"]);
		elseif(!empty($_GET["height"]) && empty($_GET["width"]))
			$oImage->resizeHeight($_GET["height"]);
		elseif(!empty($_GET["width"]) && !empty($_GET["height"]))
			$oImage->resize($_GET["width"], $_GET["height"]);
		else
			$this->error("500");
		
		$oImage->draw(null, 85);
	}
	function crop() {
		$sFile = $this->_settings->rootPublic.substr($_GET["file"], 1);
		
		if(filesize($sFile) == 0 || empty($_GET["width"]) || empty($_GET["height"]))
			$this->error("404");
		
		if(!is_numeric($_GET["width"]) || !is_numeric($_GET["height"]))
			$this->error("500");
		
		$sNewWidth = $_GET["width"];
		$sNewHeight = $_GET["height"];
		
		include($this->_settings->root."helpers/makeImage.php");
		$oImage = new makeImage($sFile, true);
		$oImage->cropCenter($sNewWidth, $sNewHeight);
		$oImage->draw(null, 85);
	}
	function itemImage() {
		$oModel = $this->loadModel($this->_urlVars->dynamic["model"]);
		
		if(empty($oModel))
			$this->error("404");
		
		$aImage = $oModel->getImage($this->_urlVars->dynamic["id"]);
		
		if(empty($aImage))
			$this->error("404");
		
		include($this->_settings->root."helpers/makeImage.php");
		$oImage = new makeImage($aImage["file"], true);
		$oImage->crop($aImage["info"]["photo_width"], $aImage["info"]["photo_height"], $aImage["info"]["photo_x1"], $aImage["info"]["photo_y1"]);
		$oImage->resize($oModel->imageMinWidth, $oModel->imageMinHeight, true);
		
		if(!empty($_GET["scale"]))
			$oImage->scale($_GET["scale"]);
		elseif(!empty($_GET["width"]) && empty($_GET["height"]))
			$oImage->resizeWidth($_GET["width"]);
		elseif(!empty($_GET["height"]) && empty($_GET["width"]))
			$oImage->resizeHeight($_GET["height"]);
		elseif(!empty($_GET["width"]) && !empty($_GET["height"]))
			$oImage->resize($_GET["width"], $_GET["height"]);
		
		$oImage->draw(null, 85);
	}
	##################################
} 