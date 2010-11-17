<?php
class testimonials_model extends appModel {
	public $useCategories = true;
	public $sort = "name-asc"; // manual, name, subname, created, updated, random - asc, desc
	
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
	function getTestimonial($sId) {
		$aTestimonial = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}testimonials`"
				." WHERE `id` = ".$sId
				." LIMIT 1"
			,"row"
		);
		
		if(!empty($aTestimonial)) {
			$aTestimonial = $this->_getTestimonialInfo($aTestimonial);
		}
		
		return $aTestimonial;
	}
	private function _getTestimonialInfo($aTestimonial) {
		$aTestimonial["name"] = htmlspecialchars(stripslashes($aTestimonial["name"]));
		$aTestimonial["sub_name"] = htmlspecialchars(stripslashes($aTestimonial["sub_name"]));
		$aTestimonial["text"] = strip_tags(stripslashes($aTestimonial["text"]), "<embed><param><object>");
		
		return $aTestimonial;
	}
	function getURL($sID) {
		$aTestimonial = $this->getTestimonial($sID);
		
		$sURL = "/testimonials/";
		
		return $sURL;
	}
	function getCategories($sEmpty = true) {
		if($sEmpty == true) {		
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}testimonials_categories`"
					." ORDER BY `name`"
				,"all"
			);
		
			foreach($aCategories as &$aCategory) {
				$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
			}
		} else {
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}testimonials_categories_assign`"
					." GROUP BY `categoryid`"
				,"all"
			);
			
			foreach($aCategories as $x => $aCategory) {
				$aCategories[$x] = $this->getCategory($aCategory["categoryid"]);
			}
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
			"SELECT * FROM `{dbPrefix}testimonials_categories`"
				.$sWhere
			,"row"
		);
		
		$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		
		return $aCategory;
	}
}