<?php
class admin_testimonials extends adminController
{
	function __construct(){
		parent::__construct("testimonials");
		
		$this->menuPermission("testimonials");
	}
	
	### DISPLAY ######################
	function index() {
		// Clear saved form info
		$_SESSION["admin"]["admin_testimonials"] = null;
		
		if(!empty($_GET["category"])) {
			$sSQLCategory = " INNER JOIN `{dbPrefix}testimonials_categories_assign` AS `assign` ON `testimonials`.`id` = `assign`.`testimonialid`";
			$sSQLCategory .= " WHERE `assign`.`categoryid` = ".$this->dbQuote($_GET["category"], "integer");
		}
		
		$aTestimonials = $this->dbResults(
			"SELECT `testimonials`.* FROM `{dbPrefix}testimonials` AS `testimonials`"
				.$sSQLCategory
				." ORDER BY `testimonials`.`name`"
			,"all"
		);
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aTestimonials", $aTestimonials);
		$this->tplDisplay("testimonials/index.tpl");
	}
	function add() {
		if(!empty($_SESSION["admin"]["admin_testimonials"]))
			$this->tplAssign("aTestimonial", $_SESSION["admin"]["admin_testimonials"]);
		else {
			$aTestimonial = array(
				"menu" => array()
				,"categories" => array()
				,"active" => 1
			);
			
			$this->tplAssign("aTestimonial", $aTestimonial);
		}
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplDisplay("testimonials/add.tpl");
	}
	function add_s() {
		if(empty($_POST["name"]) || count($_POST["categories"]) == 0) {
			$_SESSION["admin"]["admin_testimonials"] = $_POST;
			$this->forward("/admin/testimonials/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sID = $this->dbResults(
			"INSERT INTO `{dbPrefix}testimonials`"
				." (`name`, `sub_name`, `text`, `active`, `created_datetime`, `created_by`, `updated_datetime`, `updated_by`)"
				." VALUES"
				." ("
					.$this->dbQuote($_POST["name"], "text")
					.", ".$this->dbQuote($_POST["sub_name"], "text")
					.", ".$this->dbQuote($_POST["text"], "text")
					.", ".$this->boolCheck($_POST["active"])
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				.")"
			,"insert"
		);
		
		foreach($_POST["categories"] as $sCategory) {
			$this->dbResults(
				"INSERT INTO `{dbPrefix}testimonials_categories_assign`"
					." (`testimonialid`, `categoryid`)"
					." VALUES"
					." (".$sID.", ".$sCategory.")"
			);
		}
		
		$_SESSION["admin"]["admin_testimonials"] = null;
		
		$this->forward("/admin/testimonials/?notice=".urlencode("Testimonial created successfully!"));
	}
	function edit() {
		if(!empty($_SESSION["admin"]["admin_testimonials"])) {
			$aTestimonialRow = $this->dbResults(
				"SELECT * FROM `{dbPrefix}testimonials`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aTestimonial = $_SESSION["admin"]["admin_news"];
			
			$aTestimonial["updated_datetime"] = $aTestimonialRow["updated_datetime"];
			$aTestimonial["updated_by"] = $this->dbResults(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aTestimonialRow["updated_by"]
				,"row"
			);
		} else {
			$aTestimonial = $this->dbResults(
				"SELECT * FROM `{dbPrefix}testimonials`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aTestimonial["categories"] = $this->dbResults(
				"SELECT `categories`.`id` FROM `{dbPrefix}testimonials_categories` AS `categories`"
					." INNER JOIN `testimonials_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`"
					." WHERE `assign`.`testimonialid` = ".$aTestimonial["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"col"
			);
			
			$aTestimonial["updated_by"] = $this->dbResults(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aTestimonial["updated_by"]
				,"row"
			);
		}
		
		$this->tplAssign("aTestimonial", $aTestimonial);
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplDisplay("testimonials/edit.tpl");
	}
	function edit_s() {
		if(empty($_POST["name"]) || count($_POST["categories"]) == 0) {
			$_SESSION["admin"]["admin_testimonials"] = $_POST;
			$this->forward("/admin/testimonials/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->dbResults(
			"UPDATE `{dbPrefix}testimonials` SET"
				." `name` = ".$this->dbQuote($_POST["name"], "text")
				.", `sub_name` = ".$this->dbQuote($_POST["sub_name"], "text")
				.", `text` = ".$this->dbQuote($_POST["text"], "text")
				.", `active` = ".$this->boolCheck($_POST["active"])
				.", `updated_datetime` = ".$this->dbQuote(time(), "integer")
				.", `updated_by` = ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
			,"update"
		);
		
		$this->dbResults(
			"DELETE FROM `{dbPrefix}testimonials_categories_assign`"
				." WHERE `testimonialid` = ".$this->dbQuote($_POST["id"], "integer")
			,"update"
		);
		foreach($_POST["categories"] as $sCategory) {
			$this->dbResults(
				"INSERT INTO `{dbPrefix}testimonials_categories_assign`"
					." (`testimonialid`, `categoryid`)"
					." VALUES"
					." (".$this->dbQuote($_POST["id"], "integer").", ".$sCategory.")"
			);
		}
		
		$_SESSION["admin"]["admin_testimonials"] = null;
		
		$this->forward("/admin/testimonials/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete() {
		$this->dbResults(
			"DELETE FROM `{dbPrefix}testimonials`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"delete"
		);
		$this->dbResults(
			"DELETE FROM `{dbPrefix}testimonials_categories_assign`"
				." WHERE `testimonialid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"delete"
		);
		
		$this->forward("/admin/testimonials/?notice=".urlencode("Testimonial removed successfully!"));
	}
	function categories_index() {
		$_SESSION["admin"]["admin_testimonials_categories"] = null;
		
		$aCategories = $this->dbResults(
			"SELECT * FROM `{dbPrefix}testimonials_categories`"
				." ORDER BY `name`"
			,"all"
		);
		
		$this->tplAssign("aCategories", $aCategories);
		$this->tplDisplay("testimonials/categories.tpl");
	}
	function categories_add_s() {
		$this->dbResults(
			"INSERT INTO `{dbPrefix}testimonials_categories`"
				." (`name`)"
				." VALUES"
				." ("
				.$this->dbQuote($_POST["name"], "text")
				.")"
			,"insert"
		);

		echo "/admin/testimonials/categories/?notice=".urlencode("Category added successfully!");
	}
	function categories_edit_s() {
		$this->dbResults(
			"UPDATE `{dbPrefix}testimonials_categories` SET"
				." `name` = ".$this->dbQuote($_POST["name"], "text")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
			,"update"
		);

		echo "/admin/testimonials/categories/?notice=".urlencode("Changes saved successfully!");
	}
	function categories_delete() {
		$this->dbResults(
			"DELETE FROM `{dbPrefix}testimonials_categories`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"delete"
		);
		$this->dbResults(
			"DELETE FROM `{dbPrefix}testimonials_categories_assign`"
				." WHERE `categoryid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"delete"
		);

		$this->forward("/admin/testimonials/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
}