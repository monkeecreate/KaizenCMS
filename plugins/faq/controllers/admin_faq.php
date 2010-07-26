<?php
class admin_faq extends adminController
{
	function __construct() {
		parent::__construct("faq");
		
		$this->menuPermission("faq");
	}
	
	### DISPLAY ######################
	function index() {
		$oQuestions = $this->loadModel("faq");
		
		// Clear saved form info
		$_SESSION["admin"]["admin_faq"] = null;
		
		$sMinSort = $this->dbQuery(
			"SELECT MIN(`sort_order`) FROM `{dbPrefix}faq`"
			,"one"
		);
		$sMaxSort = $this->dbQuery(
			"SELECT MAX(`sort_order`) FROM `{dbPrefix}faq`"
			,"one"
		);
		
		$this->tplAssign("aCategories", $oQuestions->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aQuestions", $oQuestions->getQuestions($_GET["category"], true));
		$this->tplAssign("minSort", $sMinSort);
		$this->tplAssign("maxSort", $sMaxSort);
		$this->tplDisplay("admin/index.tpl");
	}
	function add() {
		$oQuestions = $this->loadModel("faq");
		
		if(!empty($_SESSION["admin"]["admin_faq"]))
			$this->tplAssign("aQuestion", $_SESSION["admin"]["admin_faq"]);
		else
			$this->tplAssign("aQuestion",
				array(
					"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tplAssign("aCategories", $oQuestions->getCategories());
		$this->tplDisplay("admin/add.tpl");
	}
	function add_s() {
		if(empty($_POST["question"]) || count($_POST["categories"]) == 0) {
			$_SESSION["admin"]["admin_faq"] = $_POST;
			$this->forward("/admin/faq/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sOrder = $this->dbQuery(
			"SELECT MAX(`sort_order`) + 1 FROM `{dbPrefix}faq`"
			,"one"
		);
		
		if(empty($sOrder))
			$sOrder = 1;
		
		$sID = $this->dbInsert(
			"faq",
			array(
				"question" => $_POST["question"]
				,"answer" => $_POST["answer"]
				,"sort_order" => $sOrder
				,"active" => $this->boolCheck($_POST["active"])
				,"created_datetime" => time()
				,"created_by" => $_SESSION["admin"]["userid"]
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			)
		);
		
		foreach($_POST["categories"] as $sCategory) {
			$this->dbInsert(
				"faq_categories_assign",
				array(
					"faqid" => $sID,
					"categoryid" => $sCategory
				)
			);
		}
		
		$_SESSION["admin"]["admin_faq"] = null;
		
		$this->forward("/admin/faq/?notice=".urlencode("Question created successfully!"));
	}
	function sort() {
		$oQuestions = $this->loadModel("faq");
		
		$aQuestion = $oQuestions->getQuestion($this->urlVars->dynamic["id"], "integer");
		
		if($this->urlVars->dynamic["sort"] == "up") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}faq`"
					." WHERE `sort_order` < ".$aQuestion["sort_order"]
					." ORDER BY `sort_order` DESC"
				,"row"
			);
		} elseif($this->urlVars->dynamic["sort"] == "down") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}faq`"
					." WHERE `sort_order` > ".$aQuestion["sort_order"]
					." ORDER BY `sort_order` ASC"
				,"row"
			);
		}
			
		$this->dbUpdate(
			"faq",
			array(
				"sort_order" => $aOld["sort_order"]
			),
			$aQuestion["id"]
		);
		
		$this->dbUpdate(
			"faq",
			array(
				"sort_order" => $aQuestion["sort_order"]
			),
			$aOld["id"]
		);
		
		$this->forward("/admin/faq/?notice=".urlencode("Sort order saved successfully!"));
	}
	function edit() {
		$oQuestions = $this->loadModel("faq");
		
		if(!empty($_SESSION["admin"]["admin_faq"])) {
			$aQuestionRow = $oQuestions->getQuestion($this->urlVars->dynamic["id"]);
			
			$aQuestion = $_SESSION["admin"]["admin_faq"];
			
			$aQuestion["updated_datetime"] = $aQuestionRow["updated_datetime"];
			$aQuestion["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aQuestionRow["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aQuestion", $aQuestion);
		} else {
			$aQuestion = $oQuestions->getQuestion($this->urlVars->dynamic["id"], "integer");
			
			$aQuestion["categories"] = $this->dbQuery(
				"SELECT `categories`.`id` FROM `{dbPrefix}faq_categories` AS `categories`"
					." INNER JOIN `faq_categories_assign` AS `faq_assign` ON `categories`.`id` = `faq_assign`.`categoryid`"
					." WHERE `faq_assign`.`faqid` = ".$aQuestion["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"col"
			);
			
			$aQuestion["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aQuestion["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aQuestion", $aQuestion);
		}
		
		$this->tplAssign("aCategories", $oQuestions->getCategories());
		$this->tplDisplay("admin/edit.tpl");
	}
	function edit_s() {
		if(empty($_POST["question"]) || count($_POST["categories"]) == 0) {
			$_SESSION["admin"]["admin_faq"] = $_POST;
			$this->forward("/admin/faq/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->dbUpdate(
			"faq",
			array(
				"question" => $_POST["question"]
				,"answer" => $_POST["answer"]
				,"active" => $this->boolCheck($_POST["active"])
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			$_POST["id"]
		);
		
		$this->dbDelete("faq_categories_assign", $_POST["id"], "faqid");
		foreach($_POST["categories"] as $sCategory) {
			$this->dbInsert(
				"faq_categories_assign",
				array(
					"faqid" => $_POST["id"],
					"categoryid" => $sCategory
				)
			);
		}
		
		$_SESSION["admin"]["admin_faq"] = null;
		
		$this->forward("/admin/faq/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete() {
		$this->dbDelete("faq", $this->urlVars->dynamic["id"]);
		$this->dbDelete("faq_categories_assign", $this->urlVars->dynamic["id"], "faqid");
		
		$this->forward("/admin/faq/?notice=".urlencode("Question removed successfully!"));
	}
	function categories_index() {
		$oQuestions = $this->loadModel("faq");
		
		$_SESSION["admin"]["admin_faq_categories"] = null;
		
		$this->tplAssign("aCategories", $oQuestions->getCategories());
		$this->tplAssign("aCategoryEdit", $oQuestions->getCategory($_GET["category"]));
		$this->tplDisplay("admin/categories.tpl");
	}
	function categories_add_s() {
		$this->dbInsert(
			"faq_categories",
			array(
				"name" => $_POST["name"]
			)
		);

		$this->forward("/admin/faq/categories/?notice=".urlencode("Category created successfully!"));
	}
	function categories_edit_s() {
		$this->dbUpdate(
			"faq_categories",
			array(
				"name" => $_POST["name"]
			),
			$_POST["id"]
		);

		$this->forward("/admin/faq/categories/?notice=".urlencode("Changes saved successfully!"));
	}
	function categories_delete() {
		$this->dbDelete("faq_categories", $this->urlVars->dynamic["id"]);
		$this->dbDelete("faq_categories_assign", $this->urlVars->dynamic["id"], "categoryid");

		$this->forward("/admin/faq/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
}