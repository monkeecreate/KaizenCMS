<?php
class testimonials extends appController
{
	function index($aParams)
	{
		$aTestimonials = $this->dbResults(
			"SELECT `testimonials`.* FROM `testimonials`"
				." INNER JOIN `testimonials_categories_assign` AS `assign` ON `testimonials`.`id` = `assign`.`testimonialid`"
				." INNER JOIN `testimonials_categories` AS `categories` ON `assign`.`categoryid` = `categories`.`id`"
				." WHERE `testimonials`.`active` = 1"
				." ORDER BY `testimonials`.`name`"
			,"testimonials"
			,"all"
		);
		
		if(empty($aParams["id"]))
			$this->tplAssign("aCurTestimonial", $aTestimonials[0]);
		else
		{
			$aTestimonial = $this->dbResults(
				"SELECT * FROM `testimonials`"
					." WHERE `id` = ".$aParams["id"]
				,"testimonials->testimonial"
				,"row"
			);
			$this->tplAssign("aCurTestimonial", $aTestimonial);
		}
		
		$this->tplAssign("aTestimonials", $aTestimonials);
		$this->tplDisplay("testimonials.tpl");
	}
}