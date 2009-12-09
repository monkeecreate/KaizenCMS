<?php
class news_model extends appModel
{
	public $imageMinWidth = 320;
	public $imageMinHeight = 200;
	public $perPage = 5;
	
	function getArticles($sCategory = null)
	{	
		$sWhere = " WHERE `news`.`datetime_show` < ".time()." AND (`news`.`use_kill` = 0 OR `news`.`datetime_kill` > ".time().")";
		$sWhere .= " AND `news`.`active` = 1";
		if(!empty($sCategory))
			$sWhere .= " AND `categories`.`id` = ".$this->db_quote($sCategory, "integer");
		
		// Get all articles for paging
		$aArticles = $this->db_results(
			"SELECT `news`.* FROM `news` AS `news`"
				." INNER JOIN `news_categories_assign` AS `news_assign` ON `news`.`id` = `news_assign`.`articleid`"
				." INNER JOIN `news_categories` AS `categories` ON `news_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `news`.`id`"
				." ORDER BY `news`.`datetime_show` DESC"
			,"model->news->get_articles"
			,"all"
		);
	
		foreach($aArticles as $x => $aArticle)
		{
			/*# Categories #*/
			$aArticleCategories = $this->db_results(
				"SELECT `name` FROM `news_categories` AS `categories`"
					." INNER JOIN `news_categories_assign` AS `news_assign` ON `news_assign`.`categoryid` = `categories`.`id`"
					." WHERE `news_assign`.`articleid` = ".$aArticle["id"]
				,"model->new->get_articles->article_categories"
				,"col"
			);
		
			$aArticles[$x]["categories"] = implode(", ", $aArticleCategories);
			/*# Categories #*/
		
			/*# Image #*/
			if(file_exists($this->_settings->root_public."upload/news/".$aArticle["id"].".jpg") && $aArticle["photo_x2"] > 0)
				$aArticles[$x]["image"] = 1;
			/*# Image #*/
		}
		
		return $aArticles;
	}
	function getArticle($sId)
	{
		$aArticle = $this->db_results(
			"SELECT `news`.* FROM `news` AS `news`"
				." WHERE `news`.`id` = ".$this->db_quote($sId, "integer")
				." AND `news`.`active` = 1"
				." AND `news`.`datetime_show` < ".time()
				." AND (`news`.`use_kill` = 0 OR `news`.`datetime_kill` > ".time().")"
			,"news->article"
			,"row"
		);

		$aCategories = $this->db_results(
			"SELECT `name` FROM `news_categories` AS `category`"
				." INNER JOIN `news_categories_assign` AS `news_assign` ON `news_assign`.`categoryid` = `category`.`id`"
				." WHERE `news_assign`.`articleid` = ".$aArticle["id"]
			,"news->article->categories"
			,"col"
		);

		$aArticle["categories"] = implode(", ", $aCategories);
		
		/*# Image #*/
		if(file_exists($this->_settings->root_public."uploads/news/".$aArticle["id"].".jpg"))
			$aArticle["image"] = 1;
		/*# Image #*/
		
		return $aArticle;
	}
	function getCategories()
	{
		$aCategories = $this->db_results(
			"SELECT * FROM `news_categories`"
				." ORDER BY `name`"
			,"model->news->get_categories"
			,"all"
		);
		
		return $aCategories;
	}
	function getImage($sId)
	{
		
	}
}