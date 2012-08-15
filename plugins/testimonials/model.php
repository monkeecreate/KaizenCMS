<?php
class testimonials_model extends appModel {
	public $useCategories;
	public $sort;
	public $sortCategory;
	
	function __construct() {
		parent::__construct();
		
		include(dirname(__file__)."/config.php");
		
		foreach($aPluginInfo["config"] as $sKey => $sValue) {
			$this->$sKey = $sValue;
		}
	}
	
	function getTestimonials($sCategory = null, $sRandom = false, $sAll = false) {
		$aWhere = array();
		$sJoin = "";
		
		// Filter those that are only active, unless told otherwise
		if($sAll == false) {
			$aWhere[] = "`active` = 1";
		}
		
		// Filter by category if given
		if(!empty($sCategory)) {
			$aWhere[] = "`categories`.`id` = ".$this->dbQuote($sCategory, "integer");
			$sJoin .= " LEFT JOIN `{dbPrefix}testimonials_categories_assign` AS `testimonials_assign` ON `testimonials`.`id` = `testimonials_assign`.`testimonialid`";
			$sJoin .= " LEFT JOIN `{dbPrefix}testimonials_categories` AS `categories` ON `testimonials_assign`.`categoryid` = `categories`.`id`";
		}
		
		// Combine filters if atleast one was added
		if(!empty($aWhere)) {
			$sWhere = " WHERE ".implode(" AND ", $aWhere);
		}
		
		// Check if sort direction is set, and clean it up for SQL use
		$sSortDirection = array_pop(explode("-", $this->sort));
		if(empty($sSortDirection) || !in_array(strtolower($sSortDirection), array("asc", "desc"))) {
			$sSortDirection = "ASC";
		} else {
			$sSortDirection = strtoupper($sSortDirection);
		}
			
		// Choose sort method based on model setting
		switch(array_shift(explode("-", $this->sort))) {
			case "manual":
				$sOrderBy = " ORDER BY `sort_order` ".$sSortDirection;
				break;
			case "created":
				$sOrderBy = " ORDER BY `created_datetime` ".$sSortDirection;
				break;
			case "updated":
				$sOrderBy = " ORDER BY `updated_datetime` ".$sSortDirection;
				break;
			case "random":
				$sOrderBy = " ORDER BY RAND()";
				break;
			case "subname":
				$sOrderBy = " ORDER BY `sub_name` ".$sSortDirection;
				break;
			// Default to sort by name
			default:
				$sOrderBy = " ORDER BY `name` ".$sSortDirection;
		}

		if($sRandom == true)
			$sOrderBy = " ORDER BY RAND() ";
		
		$aTestimonials = $this->dbQuery(
			"SELECT `testimonials`.* FROM `{dbPrefix}testimonials` as `testimonials`"
				.$sJoin
				.$sWhere
				." GROUP BY `testimonials`.`id`"
				.$sOrderBy
			,"all"
		);
		
		foreach($aTestimonials as &$aTestimonial) {
			$aTestimonial = $this->_getTestimonialInfo($aTestimonial);
		}
		
		return $aTestimonials;
	}
	function getTestimonial($sId, $sTag = null, $sAll = false) {
		if(!empty($sId))
			$sWhere = " WHERE `id` = ".$this->dbQuote($sId, "integer");
		else
			$sWhere = " WHERE `tag` = ".$this->dbQuote($sTag, "text");
		
		if($sAll == false)
			$sWhere .= " AND `active` = 1";
		
		$aTestimonial = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}testimonials`"
				.$sWhere
				." LIMIT 1"
			,"row"
		);
		
		$aTestimonial = $this->_getTestimonialInfo($aTestimonial);
		
		return $aTestimonial;
	}
	private function _getTestimonialInfo($aTestimonial) {
		if(!empty($aTestimonial)) {
			$aTestimonial["name"] = htmlspecialchars(stripslashes($aTestimonial["name"]));
			$aTestimonial["sub_name"] = htmlspecialchars(stripslashes($aTestimonial["sub_name"]));
			$aTestimonial["text"] = strip_tags(stripslashes($aTestimonial["text"]), "<embed><param><object>");
			$aTestimonial["url"] = "/testimonials/".$aTestimonial["tag"]."/";
		}
		
		return $aTestimonial;
	}
	function getURL($sID) {
		$aTestimonial = $this->getTestimonial($sID);
		
		return $aTestimonial["url"];
	}
	function getCategories($sEmpty = true) {
		$sJoin = "";
		
		if($sEmpty == false) {		
			$sJoin .= " INNER JOIN `{dbPrefix}testimonials_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`";
		} else {
			$sJoin .= " LEFT JOIN `{dbPrefix}testimonials_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`";
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
			"SELECT `id`, `name`, `sort_order`, COUNT('categoryid') AS `items` FROM `{dbPrefix}testimonials_categories` AS `categories`"
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
		if(!empty($sId)) {
			$sWhere = " WHERE `id` = ".$this->dbQuote($sId, "integer");
		} elseif(!empty($sName)) {
			$sWhere = " WHERE `name` LIKE ".$this->dbQuote($sName, "text");
		} else {
			return false;
		}
		
		$aCategory = $this->dbQuery(
			"SELECT `id`, `name`, `sort_order`, COUNT('categoryid') AS `items` FROM `{dbPrefix}testimonials_categories` AS `categories`"
				." LEFT JOIN `{dbPrefix}testimonials_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`"
				.$sWhere
			,"row"
		);
		
		if(!empty($aCategory)) {
			$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		}
		
		return $aCategory;
	}
}