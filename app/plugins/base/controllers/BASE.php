<?php
class BASE extends appController {
	function __construct() {
		// Load model when creating appController
		parent::__construct("BASE");
	}
	
	function index() {		
		$this->tplDisplay("index.php");
	}
}