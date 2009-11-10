<?php
class testimonials extends appController
{
	function index($aParams)
	{
		$aTestimonials = $this->db_results(
			"SELECT `testimonials`.* FROM `testimonials`"
				." INNER JOIN `testimonials_categories_assign` AS `assign` ON `testimonials`.`id` = `assign`.`testimonialid`"
				." INNER JOIN `testimonials_categories` AS `categories` ON `assign`.`categoryid` = `categories`.`id`"
				." WHERE `testimonials`.`active` = 1"
				." ORDER BY `testimonials`.`name`"
			,"testimonials"
			,"all"
		);
		
		if(empty($aParams["id"]))
			$this->_smarty->assign("aCurTestimonial", $aTestimonials[0]);
		else
		{
			$aTestimonial = $this->db_results(
				"SELECT * FROM `testimonials`"
					." WHERE `id` = ".$aParams["id"]
				,"testimonials->testimonial"
				,"row"
			);
			$this->_smarty->assign("aCurTestimonial", $aTestimonial);
		}
		
		$this->_smarty->assign("aTestimonials", $aTestimonials);
		$this->_smarty->display("testimonials.tpl");
	}
}