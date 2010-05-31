<?php
class testimonials_model extends appModel
{
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
		
		$aTestimonials = $this->dbResults(
			"SELECT `testimonials`.* FROM `testimonials`"
				." INNER JOIN `testimonials_categories_assign` AS `testimonials_assign` ON `testimonials`.`id` = `testimonials_assign`.`testimonialid`"
				." INNER JOIN `testimonials_categories` AS `categories` ON `testimonials_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				.$sOrder
			,"all"
		);
		
		foreach($aTestimonials as &$aTestimonial)
			$aTestimonial = $this->_getTestimonialInfo($aTestimonial);
		
		return $aTestimonials;
	}
	function getTestimonial($sId) {
		$aTestimonial = $this->dbResults(
			"SELECT * FROM `testimonials`"
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
			$aCategories = $this->dbResults(
				"SELECT * FROM `testimonials_categories`"
					." ORDER BY `name`"
				,"all"
			);
		} else {
			$aCategories = $this->dbResults(
				"SELECT * FROM `testimonials_categories_assign`"
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
			"SELECT * FROM `testimonials_categories`"
				.$sWhere
			,"row"
		);
		
		return $aCategory;
	}
}