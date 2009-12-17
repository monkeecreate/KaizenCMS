<?php
class news_model extends appModel
{
	public $imageMinWidth = 140;
	public $imageMinHeight = 87;
	public $perPage = 5;
	
	function getArticles($sCategory = null)
	{	
		$sWhere = " WHERE `news`.`datetime_show` < ".time()." AND (`news`.`use_kill` = 0 OR `news`.`datetime_kill` > ".time().")";
		$sWhere .= " AND `news`.`active` = 1";
		if(!empty($sCategory))
			$sWhere .= " AND `categories`.`id` = ".$this->dbQuote($sCategory, "integer");
		
		$aArticles = $this->dbResults(
			"SELECT `news`.* FROM `news` AS `news`"
				." INNER JOIN `news_categories_assign` AS `news_assign` ON `news`.`id` = `news_assign`.`articleid`"
				." INNER JOIN `news_categories` AS `categories` ON `news_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `news`.`id`"
				." ORDER BY `news`.`datetime_show` DESC"
			,"model->news->getArticles"
			,"all"
		);
	
		foreach($aArticles as $x => $aArticle)
		{
			$aArticleCategories = $this->dbResults(
				"SELECT `name` FROM `news_categories` AS `categories`"
					." INNER JOIN `news_categories_assign` AS `news_assign` ON `news_assign`.`categoryid` = `categories`.`id`"
					." WHERE `news_assign`.`articleid` = ".$aArticle["id"]
				,"model->new->getArticles->article_categories"
				,"col"
			);
		
			$aArticles[$x]["categories"] = implode(", ", $aArticleCategories);
			
			if(file_exists($this->_settings->root_public."upload/news/".$aArticle["id"].".jpg") && $aArticle["photo_x2"] > 0)
				$aArticles[$x]["image"] = 1;
		}
		
		return $aArticles;
	}
	function getArticle($sId)
	{
		$aArticle = $this->dbResults(
			"SELECT `news`.* FROM `news` AS `news`"
				." WHERE `news`.`id` = ".$this->dbQuote($sId, "integer")
				." AND `news`.`active` = 1"
				." AND `news`.`datetime_show` < ".time()
				." AND (`news`.`use_kill` = 0 OR `news`.`datetime_kill` > ".time().")"
			,"model->news->getArticle"
			,"row"
		);
		
		if(!empty($aArticle))
		{
			$aCategories = $this->dbResults(
				"SELECT `name` FROM `news_categories` AS `category`"
					." INNER JOIN `news_categories_assign` AS `news_assign` ON `news_assign`.`categoryid` = `category`.`id`"
					." WHERE `news_assign`.`articleid` = ".$aArticle["id"]
				,"model->news->getArticle->categories"
				,"col"
			);
			
			$aArticle["categories"] = implode(", ", $aCategories);
			
			if(file_exists($this->_settings->root_public."uploads/news/".$aArticle["id"].".jpg"))
				$aArticle["image"] = 1;
		}
		
		return $aArticle;
	}
	function getCategories()
	{
		$aCategories = $this->dbResults(
			"SELECT * FROM `news_categories`"
				." ORDER BY `name`"
			,"model->news->getCategories"
			,"all"
		);
		
		return $aCategories;
	}
	function getImage($sId)
	{
		$aArticle = $this->getArticle($sId);
		
		$sFile = $this->_settings->root_public."uploads/news/".$sId.".jpg";
		
		$image = imagecreatefromjpeg($sFile);
		
		$aImage = array(
			"image" => $image
			,"file" => $sFile
			,"info" => $aArticle
		);
		
		return $aImage;
	}
}