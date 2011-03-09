<?php
class news_model extends appModel {
	public $useImage;
	public $imageMinWidth;
	public $imageMinHeight;
	public $imageFolder;
	public $useCategories;
	public $perPage;
	public $shortContentCharacters;
	public $sortCategory;
	
	function __construct() {
		parent::__construct();
		
		include(dirname(__file__)."/config.php");
		
		foreach($aPluginInfo["config"] as $sKey => $sValue) {
			$this->$sKey = $sValue;
		}
	}
	
	function getArticles($sCategory = null, $sAll = false) {
		$aWhere = array();
		$sJoin = "";
		
		// Filter those that are only active, unless told otherwise
		if($sAll == false) {
			$aWhere[] = "`news`.`datetime_show` < ".time();
			$aWhere[] = "(`news`.`use_kill` = 0 OR `news`.`datetime_kill` > ".time().")";
			$aWhere[] = "`news`.`active` = 1";
		}
		
		// Filter by category if given
		if(!empty($sCategory)) {
			$aWhere[] = "`categories`.`id` = ".$this->dbQuote($sCategory, "integer");
			$sJoin .= " LEFT JOIN `{dbPrefix}news_categories_assign` AS `news_assign` ON `news`.`id` = `news_assign`.`articleid`";
			$sJoin .= " LEFT JOIN `{dbPrefix}news_categories` AS `categories` ON `news_assign`.`categoryid` = `categories`.`id`";
		}
		
		// Combine filters if atleast one was added
		if(!empty($aWhere)) {
			$sWhere = " WHERE ".implode(" AND ", $aWhere);
		}
		
		$aArticles = $this->dbQuery(
			"SELECT `news`.* FROM `{dbPrefix}news` AS `news`"
				.$sJoin
				.$sWhere
				." GROUP BY `news`.`id`"
				." ORDER BY `news`.`sticky` DESC, `news`.`datetime_show` DESC"
			,"all"
		);
		
		foreach($aArticles as &$aArticle) {
			$this->_getArticleInfo($aArticle);
		}
		
		return $aArticles;
	}
	function getArticle($sId, $sTag = "", $sAll = false) {
		if(!empty($sId))
			$sWhere = " WHERE `news`.`id` = ".$this->dbQuote($sId, "integer");
		else
			$sWhere = " WHERE `news`.`tag` = ".$this->dbQuote($sTag, "text");
			
		if($sAll == false) {
			$sWhere .= " AND `news`.`active` = 1";
			$sWhere .= " AND `news`.`datetime_show` < ".time();
			$sWhere .= " AND (`news`.`use_kill` = 0 OR `news`.`datetime_kill` > ".time().")";
		}
		
		$aArticle = $this->dbQuery(
			"SELECT `news`.* FROM `{dbPrefix}news` AS `news`"
				.$sWhere
			,"row"
		);
		
		$this->_getArticleInfo($aArticle);
		
		return $aArticle;
	}
	private function _getArticleInfo(&$aArticle) {
		if(!empty($aArticle)) {
			if(!empty($aArticle["created_by"]))
				$aArticle["user"] = $this->getUser($aArticle["created_by"]);
		
			$aArticle["title"] = htmlspecialchars(stripslashes($aArticle["title"]));
			if(!empty($aArticle["short_content"]))
				$aArticle["short_content"] = nl2br(htmlspecialchars(stripslashes($aArticle["short_content"])));
			else
				$aArticle["short_content"] = (string)substr(nl2br(htmlspecialchars(stripslashes(strip_tags($aArticle["content"])))), 0, $this->shortContentCharacters);
		
			$aArticle["content"] = stripslashes($aArticle["content"]);
			$aArticle["url"] = "/news/".date("Y", $aArticle["created_datetime"])."/".date("m", $aArticle["created_datetime"])."/".date("d", $aArticle["created_datetime"])."/".$aArticle["tag"]."/";
		
			$aArticle["categories"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}news_categories` AS `categories`"
					." INNER JOIN `{dbPrefix}news_categories_assign` AS `news_assign` ON `news_assign`.`categoryid` = `categories`.`id`"
					." WHERE `news_assign`.`articleid` = ".$aArticle["id"]
				,"all"
			);
		
			foreach($aArticle["categories"] as &$aCategory) {
				$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
			}
		
			if(file_exists($this->settings->rootPublic.substr($this->imageFolder, 1).$aArticle["id"].".jpg")
			 && $aArticle["photo_x2"] > 0
			 && $this->useImage == true)
				$aArticle["image"] = 1;
			else
				$aArticle["image"] = 0;
		}
	}
	function getURL($sID) {
		$aArticle = $this->getArticle($sID);
		
		if(!empty($aArticle)) {
			return $aArticle["url"];
		} else {
			return false;
		}
	}
	function getCategories($sEmpty = true) {
		$sJoin = "";
		
		if($sEmpty == false) {		
			$sJoin .= " INNER JOIN `{dbPrefix}news_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`";
		} else {
			$sJoin .= " LEFT JOIN `{dbPrefix}news_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`";
		}
		
		// Check if sort direction is set, and clean it up for SQL use
		$sSortDirection = array_pop(explode("-", $this->sortCategory));
		if(empty($sSortDirection) || !in_array(strtolower($sSortDirection), array("asc", "desc"))) {
			$sSortDirection = "ASC";
		} else {
			$sSortDirection = strtoupper($sSortDirection);
		}
		
		// Choose sort method based on model setting
		switch(array_shift(explode("-", $this->sortCategory))) {
			case "manual":
				$sOrderBy = " ORDER BY `sort_order` ".$sSortDirection;
				break;
			case "items":
				$sOrderBy = " ORDER BY `items` ".$sSortDirection;
				break;
			case "random":
				$sOrderBy = " ORDER BY RAND()";
				break;
			// Default to sort by name
			default:
				$sOrderBy = " ORDER BY `name` ".$sSortDirection;
		}
		
		$aCategories = $this->dbQuery(
			"SELECT `id`, `name`, `sort_order`, COUNT('categoryid') AS `items` FROM `{dbPrefix}news_categories` AS `categories`"
				.$sJoin
				." GROUP BY `id`"
				.$sOrderBy
			,"all"
		);
	
		foreach($aCategories as &$aCategory) {
			$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
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
			"SELECT `id`, `name`, `sort_order`, COUNT('categoryid') AS `items` FROM `{dbPrefix}news_categories` AS `categories`"
				." LEFT JOIN `{dbPrefix}news_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`"
				.$sWhere
			,"row"
		);
		
		if(!empty($aCategory))
			$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		
		return $aCategory;
	}
	function getImage($sId) {
		$aArticle = $this->getArticle($sId, true);
		
		$sFile = $this->settings->rootPublic.substr($this->imageFolder, 1).$sId.".jpg";
		
		$aImage = array(
			"file" => $sFile
			,"info" => $aArticle
		);
		
		return $aImage;
	}
}