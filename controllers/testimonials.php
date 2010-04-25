<?php
class testimonials extends appController
{
	function __construct() {
		// Load model when creating appController
		parent::__construct("testimonials");
	}
	
	function index() {
		$aTestimonials = $this->model->getTestimonials($_GET["category"]);
		
		$this->tplAssign("aTestimonials", $aTestimonials);
		$this->tplDisplay("testimonials.tpl");
	}
}