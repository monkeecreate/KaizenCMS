<?php
class admin_testimonials extends adminController
{
	function __construct(){
		parent::__construct("testimonials");
		
		$this->menuPermission("testimonials");
	}
	
	### DISPLAY ######################
	function index() {
		$oTestimonials = $this->loadModel("testimonials");
		
		// Clear saved form info
		$_SESSION["admin"]["admin_testimonials"] = null;
		
		$this->tplAssign("aCategories", $oTestimonials->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aTestimonials", $oTestimonials->getTestimonials());
		$this->tplDisplay("admin/index.tpl");
	}
	function add() {
		$oTestimonials = $this->loadModel("testimonials");
		
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
		
		$this->tplAssign("aCategories", $oTestimonials->getCategories());
		$this->tplAssign("sUseCategories", $oTestimonials->useCategories);
		$this->tplDisplay("admin/add.tpl");
	}
	function add_s() {
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_testimonials"] = $_POST;
			$this->forward("/admin/testimonials/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sID = $this->dbInsert(
			"testimonials",
			array(
				"name" => $_POST["name"]
				,"sub_name" => $_POST["sub_name"]
				,"text" => $_POST["text"]
				,"active" => $this->boolCheck($_POST["active"])
				,"created_datetime" => time()
				,"created_by" => $_SESSION["admin"]["userid"]
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			)
		);
		
		if(!empty($_POST["categories"])) {
			foreach($_POST["categories"] as $sCategory) {
				$this->dbInsert(
					"testimonials_categories_assign",
					array(
						"testimonialid" => $sID,
						"categoryid" => $sCategory
					)
				);
			}
		}
		
		$_SESSION["admin"]["admin_testimonials"] = null;
		
		$this->forward("/admin/testimonials/?notice=".urlencode("Testimonial created successfully!"));
	}
	function edit() {
		$oTestimonials = $this->loadModel("testimonials");
		
		if(!empty($_SESSION["admin"]["admin_testimonials"])) {
			$aTestimonialRow = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}testimonials`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aTestimonial = $_SESSION["admin"]["admin_news"];
			
			$aTestimonial["updated_datetime"] = $aTestimonialRow["updated_datetime"];
			$aTestimonial["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aTestimonialRow["updated_by"]
				,"row"
			);
		} else {
			$aTestimonial = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}testimonials`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aTestimonial["categories"] = $this->dbQuery(
				"SELECT `categories`.`id` FROM `{dbPrefix}testimonials_categories` AS `categories`"
					." INNER JOIN `testimonials_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`"
					." WHERE `assign`.`testimonialid` = ".$aTestimonial["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"col"
			);
			
			$aTestimonial["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aTestimonial["updated_by"]
				,"row"
			);
		}
		
		$this->tplAssign("aTestimonial", $aTestimonial);
		
		$this->tplAssign("aCategories", $oTestimonials->getCategories());
		$this->tplAssign("sUseCategories", $oTestimonials->useCategories);
		$this->tplDisplay("admin/edit.tpl");
	}
	function edit_s() {
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_testimonials"] = $_POST;
			$this->forward("/admin/testimonials/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->dbUpdate(
			"testimonials",
			array(
				"name" => $_POST["name"]
				,"sub_name" => $_POST["sub_name"]
				,"text" => $_POST["text"]
				,"active" => $this->boolCheck($_POST["active"])
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			$_POST["id"]
		);
		
		$this->dbDelete("testimonials_categories_assign", $_POST["id"], "testimonialid");
		if(!empty($_POST["categories"])) {
			foreach($_POST["categories"] as $sCategory) {
				$this->dbInsert(
					"testimonials_categories_assign",
					array(
						"testimonialid" => $_POST["id"],
						"categoryid" => $sCategory
					)
				);
			}
		}
		
		$_SESSION["admin"]["admin_testimonials"] = null;
		
		$this->forward("/admin/testimonials/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete() {
		$this->dbDelete("testimonials", $this->_urlVars->dynamic["id"]);
		$this->dbDelete("testimonials_categories_assign", $this->_urlVars->dynamic["id"], "testimonialid");
		
		$this->forward("/admin/testimonials/?notice=".urlencode("Testimonial removed successfully!"));
	}
	function categories_index() {
		$oTestimonials = $this->loadModel("testimonials");
		
		$_SESSION["admin"]["admin_testimonials_categories"] = null;
		
		$this->tplAssign("aCategories", $oTestimonials->getCategories());
		$this->tplAssign("aCategoryEdit", $oTestimonials->getCategory($_GET["category"]));
		$this->tplDisplay("admin/categories.tpl");
	}
	function categories_add_s() {
		$this->dbInsert(
			"testimonials_categories",
			array(
				"name" => $_POST["name"]
			)
		);

		$this->forward("/admin/testimonials/categories/?notice=".urlencode("Category created successfully!"));
	}
	function categories_edit_s() {
		$this->dbUpdate(
			"testimonials_categories",
			array(
				"name" => $_POST["name"]
			),
			$_POST["id"]
		);

		$this->forward("/admin/testimonials/categories/?notice=".urlencode("Changes saved successfully!"));
	}
	function categories_delete() {
		$this->dbDelete("testimonials_categories", $this->_urlVars->dynamic["id"]);
		$this->dbDelete("testimonials_categories_assign", $this->_urlVars->dynamic["id"], "categoryid");

		$this->forward("/admin/testimonials/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
}