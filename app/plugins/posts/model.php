<?php
class posts_model extends appModel {
	public $useImage, $imageMinWidth, $imageMinHeight, $imageFolder, $useCategories, $perPage, $useComments, $excerptCharacters, $sortCategory;
	
	function __construct() {
		parent::__construct();
		
		include(dirname(__file__)."/config.php");
		
		foreach($aPluginInfo["config"] as $sKey => $sValue) {
			$this->$sKey = $sValue;
		}
	}
	
	/**
	 * Get posts from the database.
	 * @param  integer $sCategory Filter only posts assigned to this category.
	 * @param  boolean $sAll      When true returns all posts no matter conditions.
	 * @param  boolean $sPopular  When true sorts posts by `views` instead of publish date.
	 * @return array              Return array of posts.
	 */
	function getPosts($sCategory = null, $sAll = false, $sPopular = false) {
		$aWhere = array();
		$sJoin = "";
		
		// Filter only posts that are active unless told otherwise.
		if($sAll == false) {
			$aWhere[] = "`posts`.`publish_on` < ".time();
			$aWhere[] = "`posts`.`active` = 1";
		}
		
		// Filter posts in a category, if category provided.
		if(!empty($sCategory)) {
			$aWhere[] = "`categories`.`id` = ".$this->dbQuote($sCategory, "integer");
			$sJoin .= " LEFT JOIN `{dbPrefix}posts_categories_assign` AS `posts_assign` ON `posts`.`id` = `posts_assign`.`postid`";
			$sJoin .= " LEFT JOIN `{dbPrefix}posts_categories` AS `categories` ON `posts_assign`.`categoryid` = `categories`.`id`";
		}
		
		// Combine the above filters for sql.
		if(!empty($aWhere)) {
			$sWhere = " WHERE ".implode(" AND ", $aWhere);
		}

		// Sort posts by `views` instead of publish date.
		if($sPopular) {
			$sOrderBy = " ORDER BY `posts`.`views` DESC";
		} else {
			$sOrderBy = "ORDER BY `posts`.`sticky` DESC, `posts`.`publish_on` DESC";
		}
		
		$aPosts = $this->dbQuery(
			"SELECT `posts`.* FROM `{dbPrefix}posts` AS `posts`"
				.$sJoin
				.$sWhere
				." GROUP BY `posts`.`id`"
				.$sOrderBy
			,"all"
		);
		
		// Clean up each post information and get additional info if needed.
		foreach($aPosts as &$aPost) {
			$this->_getPostInfo($aPost);
		}
		
		// Posts are ready for use.
		return $aPosts;
	}

	/**
	 * Get a single post from the database.
	 * @param  integer $sId  Unique ID for the post or null.
	 * @param  string  $sTag Unique tag for the post or null.
	 * @param  boolean $sAll When true returns result no matter conditions.
	 * @return array         Return the post.
	 */
	function getPost($sId, $sTag = "", $sAll = false) {
		if(!empty($sId))
			$sWhere = " WHERE `posts`.`id` = ".$this->dbQuote($sId, "integer");
		else
			$sWhere = " WHERE `posts`.`tag` = ".$this->dbQuote($sTag, "text");
			
		if($sAll == false) {
			$sWhere .= " AND `posts`.`active` = 1";
			$sWhere .= " AND `posts`.`publish_on` < ".time();
		}
		
		$aPost = $this->dbQuery(
			"SELECT `posts`.* FROM `{dbPrefix}posts` AS `posts`"
				.$sWhere
			,"row"
		);
		
		$this->_getPostInfo($aPost);
		
		return $aPost;
	}

	/**
	 * Clean up post info and get any other data to be returned.
	 * @param  array &$aPost An array of a single post.
	 */
	private function _getPostInfo(&$aPost) {
		if(!empty($aPost)) {		
			$aPost["title"] = htmlspecialchars(stripslashes($aPost["title"]));
			if(!empty($aPost["excerpt"]))
				$aPost["excerpt"] = nl2br(htmlspecialchars(stripslashes($aPost["excerpt"])));
			else
				$aPost["excerpt"] = (string)substr(nl2br(htmlspecialchars(stripslashes(strip_tags($aPost["content"])))), 0, $this->excerptCharacters);
		
			$aPost["content"] = stripslashes($aPost["content"]);
			$aPost["url"] = "/posts/".date("Y", $aPost["created_datetime"])."/".date("m", $aPost["created_datetime"])."/".date("d", $aPost["created_datetime"])."/".$aPost["tag"]."/";
			
			$aPost["author"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$this->dbQuote($aPost["authorid"], "integer")
					." LIMIT 1"
				,"row"
			);

			$aPost["categories"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}posts_categories` AS `categories`"
					." INNER JOIN `{dbPrefix}posts_categories_assign` AS `posts_assign` ON `posts_assign`.`categoryid` = `categories`.`id`"
					." WHERE `posts_assign`.`postid` = ".$aPost["id"]
				,"all"
			);
		
			foreach($aPost["categories"] as &$aCategory) {
				$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
			}
		
			if(file_exists($this->settings->rootPublic.substr($this->imageFolder, 1).$aPost["id"].".jpg")
			 && $aPost["photo_x2"] > 0
			 && $this->useImage == true)
				$aPost["image"] = 1;
			else
				$aPost["image"] = 0;
		}
	}

	/**
	 * Get a posts URL
	 * @param  integer $sID A posts unique ID.
	 * @return array|false  Return post URL or false.
	 */
	function getURL($sID) {
		$aPost = $this->getPost($sID);
		
		if(!empty($aPost)) {
			return $aPost["url"];
		} else {
			return false;
		}
	}

	/**
	 * Get categories from database
	 * @param  boolean $sEmpty When false only returns categories with assigned posts.
	 * @return array           Return array of categories.
	 */
	function getCategories($sEmpty = true) {
		$sJoin = "";
		
		if($sEmpty == false) {		
			$sJoin .= " INNER JOIN `{dbPrefix}posts_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`";
		} else {
			$sJoin .= " LEFT JOIN `{dbPrefix}posts_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`";
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
			"SELECT `categories`.* FROM `{dbPrefix}posts_categories` AS `categories`"
				.$sJoin
				." GROUP BY `id`"
				.$sOrderBy
			,"all"
		);
	
		foreach($aCategories as &$aCategory) {
			$this->_getCategoryInfo($aCategory);
		}

		return $aCategories;
	}
	
	/**
	 * Get a single category from the database.
	 * @param  integer $sId   Unique ID for the category or null.
	 * @param  string  $sName Unique name for the category or null.
	 * @return array          Return the category.
	 */
	function getCategory($sId = null, $sName = null) {
		if(!empty($sId))
			$sWhere = " WHERE `id` = ".$this->dbQuote($sId, "integer");
		elseif(!empty($sName))
			$sWhere = " WHERE `name` LIKE ".$this->dbQuote($sName, "text");
		else
			return false;
		
		$aCategory = $this->dbQuery(
			"SELECT `categories`.* FROM `{dbPrefix}posts_categories` AS `categories`"
				." LEFT JOIN `{dbPrefix}posts_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`"
				.$sWhere
			,"row"
		);
		
		$this->_getCategoryInfo($aCategory);			
		
		return $aCategory;
	}

	/**
	 * Clean up category info and get any other data to be returned.
	 * @param  array &$aPost An array of a single category.
	 */
	private function _getCategoryInfo(&$aCategory) {
		if(!empty($aCategory)) {		
			$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));

			if(!empty($aCategory["parentid"]))
				$aCategory["parent"] = $this->getCategory($aCategory["parentid"]);
		}
	}

	/**
	 * Get a posts image.
	 * @param  integer $sID A posts unique ID.
	 * @return array  		Return the image.
	 */
	function getImage($sId) {
		$aPost = $this->getPost($sId, null, true);
		
		$sFile = $this->settings->rootPublic.substr($this->imageFolder, 1).$sId.".jpg";
		
		$aImage = array(
			"file" => $sFile
			,"info" => $aPost
		);
		
		return $aImage;
	}
}