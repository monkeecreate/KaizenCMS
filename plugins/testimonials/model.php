<?php
class testimonials_model extends appModel
{
	public $useCategories = true;
	
	function getTestimonials($sCategory = null, $sRandom = false) {
		$aWhere = array();
		
		$aWhere[] = "`active` = 1";
		
		if(!empty($sCategory))
			$aWhere[] = "`categories`.`id` = ".$this->dbQuote($sCategory, "integer");
			
		if(!empty($aWhere))
			$sWhere = " WHERE ".implode(" AND ", $aWhere);
		
		if($sRandom == true)
			$sOrder = " ORDER BY RAND()";
		else
			$sOrder = " ORDER BY `testimonials`.`name`, `testimonials`.`sub_name`";
		
		$aTestimonials = $this->dbQuery(
			"SELECT `testimonials`.* FROM `{dbPrefix}testimonials` as `testimonials`"
				." LEFT JOIN `{dbPrefix}testimonials_categories_assign` AS `testimonials_assign` ON `testimonials`.`id` = `testimonials_assign`.`testimonialid`"
				." LEFT JOIN `{dbPrefix}testimonials_categories` AS `categories` ON `testimonials_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `testimonials`.`id`"
				.$sOrder
			,"all"
		);
		
		foreach($aTestimonials as &$aTestimonial)
			$aTestimonial = $this->_getTestimonialInfo($aTestimonial);
		
		return $aTestimonials;
	}
	function getTestimonial($sId) {
		$aTestimonial = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}testimonials`"
				." WHERE `id` = ".$sId
				." AND `active` = 1"
				." LIMIT 1"
			,"row"
		);
		
		if(!empty($aTestimonial))
			$aTestimonial = $this->_getTestimonialInfo($aTestimonial);
		
		return $aTestimonial;
	}
	private function _getTestimonialInfo($aTestimonial) {
		$aTestimonial["name"] = htmlspecialchars(stripslashes($aTestimonial["name"]));
		$aTestimonial["sub_name"] = htmlspecialchars(stripslashes($aTestimonial["sub_name"]));
		$aTestimonial["text"] = strip_tags(stripslashes($aTestimonial["text"]), "<embed><param><object>");
		
		return $aTestimonial;
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
			"SELECT * FROM `{dbPrefix}testimonials_categories`"
				.$sWhere
			,"row"
		);
		
		$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		
		return $aCategory;
	}
}