<?php
class news extends appController
{
	function index()
	{
		$sPerPage = 5;
		
		## FIND CATEGORIES ##
		$aCategories = $this->db_results(
			"SELECT * FROM `news_categories`"
				." ORDER BY `name`"
			,"news->get_categories->categories"
			,"all"
		);
		
		## GET CURRENT PAGE NEWS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$sWhere = " WHERE `news`.`datetime_show` < ".time()." AND (`news`.`use_kill` = 0 OR `news`.`datetime_kill` > ".time().")";
		$sWhere .= " AND `news`.`active` = 1";
		if(!empty($_GET["category"]))
			$sWhere .= " AND `categories`.`id` = ".$this->db_quote($_GET["category"], "integer");
		
		// Get all articles for paging
		$aArticles = $this->db_results(
			"SELECT `news`.* FROM `news` AS `news`"
				." INNER JOIN `news_categories_assign` AS `news_assign` ON `news`.`id` = `news_assign`.`articleid`"
				." INNER JOIN `news_categories` AS `categories` ON `news_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `news`.`id`"
			,"news->all_news_pages"
			,"all"
		);
		
		$oPage = new Paginate($sPerPage, count($aArticles), $sCurrentPage);
	
		$start = $oPage->get_start();
		
		$aArticles = $this->db_results(
			"SELECT `news`.* FROM `news` AS `news`"
				." INNER JOIN `news_categories_assign` AS `news_assign` ON `news`.`id` = `news_assign`.`articleid`"
				." INNER JOIN `news_categories` AS `categories` ON `news_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `news`.`id`"
				." ORDER BY `news`.`sticky` DESC, `news`.`datetime_show` DESC"
				." LIMIT ".$start.",".$sPerPage
			,"news->current_page"
			,"all"
		);
	
		foreach($aArticles as $x => $aArticle)
		{
			/*# Categories #*/
			$aArticleCategories = $this->db_results(
				"SELECT `name` FROM `news_categories` AS `categories`"
					." INNER JOIN `news_categories_assign` AS `news_assign` ON `news_assign`.`categoryid` = `categories`.`id`"
					." WHERE `news_assign`.`articleid` = ".$aArticle["id"]
				,"new->article_categories"
				,"col"
			);
		
			$aArticles[$x]["categories"] = implode(", ", $aArticleCategories);
			/*# Categories #*/
		
			/*# Image #*/
			if(file_exists($this->_settings->root_public."upload/news/".$aArticle["id"].".jpg"))
				$aArticles[$x]["image"] = 1;
			/*# Image #*/
		}

		$this->tpl_assign("aCategories", $aCategories);
		$this->tpl_assign("aArticles", $aArticles);
		$this->tpl_assign("aPaging", $oPage->build_array());
		
		$this->tpl_display("news/index.tpl");
	}
	function rss()
	{
		## GET CURRENT PAGE NEWS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$sWhere = " WHERE `news`.`datetime_show` < ".time()." AND (`news`.`use_kill` = 0 OR `news`.`datetime_kill` > ".time().")";
		$sWhere .= " AND `news`.`active` = 1";
		if(!empty($_GET["category"]))
			$sWhere .= " AND `categories`.`id` = ".$this->db_quote($_GET["category"], "integer");
		
		$aArticles = $this->db_results(
			"SELECT `news`.* FROM `news` AS `news`"
				." INNER JOIN `news_categories_assign` AS `news_assign` ON `news`.`id` = `news_assign`.`articleid`"
				." INNER JOIN `news_categories` AS `categories` ON `news_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `news`.`id`"
				." ORDER BY `news`.`sticky` DESC, `news`.`datetime_show` DESC"
				." LIMIT 0,15"
			,"news->current_page"
			,"all"
		);
	
		foreach($aArticles as $x => $aArticle)
		{
			/*# Categories #*/
			$aArticleCategories = $this->db_results(
				"SELECT `name` FROM `news_categories` AS `categories`"
					." INNER JOIN `news_categories_assign` AS `news_assign` ON `news_assign`.`categoryid` = `categories`.`id`"
					." WHERE `news_assign`.`articleid` = ".$aArticle["id"]
				,"new->article_categories"
				,"col"
			);
		
			$aArticles[$x]["categories"] = implode(", ", $aArticleCategories);
			/*# Categories #*/
		
			/*# Image #*/
			if(file_exists($this->_settings->root_public."upload/news/".$aArticle["id"].".jpg"))
				$aArticles[$x]["image"] = 1;
			/*# Image #*/
		}

		$this->tpl_assign("domain", $_SERVER["SERVER_NAME"]);
		$this->tpl_assign("aArticles", $aArticles);
		
		$this->tpl_display("news/rss.tpl");
	}
	function article($aParams)
	{
		$aArticle = $this->db_results(
			"SELECT `news`.* FROM `news` AS `news`"
				." WHERE `news`.`id` = ".$this->db_quote($aParams["id"], "integer")
				." AND `news`.`active` = 1"
				." AND `news`.`datetime_show` < ".time()
				." AND (`news`.`use_kill` = 0 OR `news`.`datetime_kill` > ".time().")"
			,"news->article"
			,"row"
		);
		
		if(empty($aArticle))
			$this->error('404');

		$aCategories = $this->db_results(
			"SELECT `name` FROM `news_categories` AS `category`"
				." INNER JOIN `news_categories_assign` AS `news_assign` ON `news_assign`.`categoryid` = `category`.`id`"
				." WHERE `news_assign`.`articleid` = ".$aArticle["id"]
			,"news->article->categories"
			,"col"
		);

		$aArticle["categories"] = implode(", ", $aCategories);
		
		/*# Image #*/
		if(file_exists($this->_settings->root_public."upload/news/".$aArticle["id"].".jpg"))
			$aArticle["image"] = 1;
		/*# Image #*/

		$this->tpl_assign("aArticle", $aArticle);
		
		$this->tpl_display("news/article.tpl");
	}
}