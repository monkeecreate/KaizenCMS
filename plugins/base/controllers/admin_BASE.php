<?php
class admin_BASE extends adminController {
	function __construct() {
		parent::__construct("BASE");
		
		$this->menuPermission("BASE");
	}
	
	### DISPLAY ######################
	function index() {		
		$this->tplDisplay("admin/index.tpl");
	}
	##################################
}