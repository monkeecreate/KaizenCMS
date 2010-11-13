<?php
class news_model extends appModel {
	public $useImage = true;
	public $imageMinWidth = 140;
	public $imageMinHeight = 87;
	public $imageFolder = "/uploads/news/";
	public $useCategories = true;
	public $perPage = 5;
	public $shortContentCharacters = 250; // max characters for short content
	
	function getArticles($sCategory = null, $sAll = false) {	
		// Start the WHERE
		$sWhere = " WHERE `news`.`id` > 0";// Allways true
		
		if($sAll == false) {
			$sWhere .= " AND `news`.`datetime_show` < ".time()." AND (`news`.`use_kill` = 0 OR `news`.`datetime_kill` > ".time().")";
			$sWhere .= " AND `news`.`active` = 1";
		}
		
		if(!empty($sCategory))
			$sWhere .= " AND `categories`.`id` = ".$this->dbQuote($sCategory, "integer");
		
		$aArticles = $this->dbQuery(
			"SELECT `news`.* FROM `{dbPrefix}news` AS `news`"
				." LEFT JOIN `{dbPrefix}news_categories_assign` AS `news_assign` ON `news`.`id` = `news_assign`.`articleid`"
				." LEFT JOIN `{dbPrefix}news_categories` AS `categories` ON `news_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `news`.`id`"
				." ORDER BY `news`.`sticky` DESC, `news`.`datetime_show` DESC"
			,"all"
		);
		
		foreach($aArticles as $x => $aArticle)
			$aArticles[$x] = $this->_getArticleInfo($aArticle);
		
		return $aArticles;
	}
	function getArticle($sId, $sTag, $sAll = false) {
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
		
		if(!empty($aArticle))
			$aArticle = $this->_getArticleInfo($aArticle);
		
		return $aArticle;
	}
	private function _getArticleInfo($aArticle) {
		if(!empty($aArticle["created_by"]))
			$aArticle["user"] = $this->getUser($aArticle["created_by"]);
		
		$aArticle["title"] = htmlspecialchars(stripslashes($aArticle["title"]));
		$aArticle["short_content"] = nl2br(htmlspecialchars(stripslashes($aArticle["short_content"])));
		$aArticle["content"] = stripslashes($aArticle["content"]);
		
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
		
		return $aArticle;
	}
	function getURL($sID) {
		$aArticle = $this->getArticle($sID);
		
		$sTitle = strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($aArticle["title"])))));
		
		if(strlen($sURL) > 50)
			$sTitle = substr($sTitle, 0, 50)."...";
		
		$sURL = "/news/".$aArticle["id"]."/".$sTitle."/";
		
		return $sURL;
	}
	function getCategories($sEmpty = true) {
		if($sEmpty == true) {		
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}news_categories`"
					." ORDER BY `name`"
				,"all"
			);
		
			foreach($aCategories as &$aCategory) {
				$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
			}
		} else {
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}news_categories_assign`"
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
		
		$aCategory = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}news_categories`"
				.$sWhere
			,"row"
		);
		
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