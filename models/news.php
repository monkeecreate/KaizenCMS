<?php
class news_model extends appModel
{
	public $useImage = true;
	public $imageMinWidth = 140;
	public $imageMinHeight = 87;
	public $imageFolder = "/uploads/news/";
	public $perPage = 5;
	
	function getArticles($sCategory = null) {	
		// Start the WHERE
		$sWhere = " WHERE `news`.`id` > 0";// Allways true
		
		if($sAll == false) {
			$sWhere .= " AND `news`.`datetime_show` < ".time()." AND (`news`.`use_kill` = 0 OR `news`.`datetime_kill` > ".time().")";
			$sWhere .= " AND `news`.`active` = 1";
		}
		
		if(!empty($sCategory))
			$sWhere .= " AND `categories`.`id` = ".$this->dbQuote($sCategory, "integer");
		
		$aArticles = $this->dbResults(
			"SELECT `news`.* FROM `news` AS `news`"
				." INNER JOIN `news_categories_assign` AS `news_assign` ON `news`.`id` = `news_assign`.`articleid`"
				." INNER JOIN `news_categories` AS `categories` ON `news_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `news`.`id`"
				." ORDER BY `news`.`datetime_show` DESC"
			,"all"
		);
	
		foreach($aArticles as $x => $aArticle)
			$aArticles[$x] = $this->getArticleInfo($aArticle);
		
		return $aArticles;
	}
	function getArticle($sId) {
		$aArticle = $this->dbResults(
			"SELECT `news`.* FROM `news` AS `news`"
				." WHERE `news`.`id` = ".$this->dbQuote($sId, "integer")
				." AND `news`.`active` = 1"
				." AND `news`.`datetime_show` < ".time()
				." AND (`news`.`use_kill` = 0 OR `news`.`datetime_kill` > ".time().")"
			,"row"
		);
		
		if(!empty($aArticle))
			$aArticle = $this->getArticleInfo($aArticle);
		
		return $aArticle;
	}
	private function getArticleInfo($aArticle) {
		$aArticle["user"] = $this->getUser($aArticle["created_by"]);
			
		$aCategories = $this->dbResults(
			"SELECT `name` FROM `news_categories` AS `categories`"
				." INNER JOIN `news_categories_assign` AS `news_assign` ON `news_assign`.`categoryid` = `categories`.`id`"
				." WHERE `news_assign`.`articleid` = ".$aArticle["id"]
			,"col"
		);
	
		$aArticle["categories"] = implode(", ", $aCategories);
		
		if(file_exists($this->_settings->rootPublic.substr($this->imageFolder, 1).$aArticle["id"].".jpg")
		 && $aArticle["photo_x2"] > 0
		 && $this->useImage == true)
			$aArticle["image"] = 1;
		else
			$aArticle["image"] = 0;
			
		return $aArticle;
	}
	function getCategories($sEmpty = true) {
		if($sEmpty == true) {		
			$aCategories = $this->dbResults(
				"SELECT * FROM `news_categories`"
					." ORDER BY `name`"
				,"all"
			);
		} else {
			$aCategories = $this->dbResults(
				"SELECT * FROM `news_categories_assign`"
					." GROUP BY `categoryid`"
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
		
		$aCategory = $this->dbResults(
			"SELECT * FROM `news_categories`"
				.$sWhere
			,"row"
		);
		
		return $aCategory;
	}
	function getImage($sId) {
		$aArticle = $this->getArticle($sId);
		
		$sFile = $this->_settings->rootPublic.substr($this->imageFolder, 1).$sId.".jpg";
		
		$aImage = array(
			"file" => $sFile
			,"info" => $aArticle
		);
		
		return $aImage;
	}
}