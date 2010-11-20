<?php
class testimonials extends appController {
	function __construct() {
		// Load model when creating appController
		parent::__construct("testimonials");
	}
	
	function index() {
		$aTestimonials = $this->model->getTestimonials($_GET["category"]);
		
		$this->tplAssign("aTestimonials", $aTestimonials);
		$this->tplAssign("aCategory", $this->model->getCategory($_GET["category"]));
		$this->tplDisplay("testimonials.tpl");
	}
	function testimonial() {
		$this->tplAssign("aTestimonial", $this->model->getTestimonial(null, $this->urlVars->dynamic["tag"]));
		$this->tplDisplay("testimonial.tpl");
	}
}