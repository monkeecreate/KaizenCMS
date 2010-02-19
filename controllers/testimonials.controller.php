<?php
class testimonials extends appController
{
	function index()
	{
		$aTestimonials = $this->dbResults(
			"SELECT `testimonials`.* FROM `testimonials`"
				." INNER JOIN `testimonials_categories_assign` AS `assign` ON `testimonials`.`id` = `assign`.`testimonialid`"
				." INNER JOIN `testimonials_categories` AS `categories` ON `assign`.`categoryid` = `categories`.`id`"
				." WHERE `testimonials`.`active` = 1"
				." ORDER BY `testimonials`.`name`"
			,"all"
		);
		
		if(empty($this->_urlVars->dynamic["id"]))
			$this->tplAssign("aCurTestimonial", $aTestimonials[0]);
		else
		{
			$aTestimonial = $this->dbResults(
				"SELECT * FROM `testimonials`"
					." WHERE `id` = ".$this->_urlVars->dynamic["id"]
				,"row"
			);
			$this->tplAssign("aCurTestimonial", $aTestimonial);
		}
		
		$this->tplAssign("aTestimonials", $aTestimonials);
		$this->tplDisplay("testimonials.tpl");
	}
}