<?php
class admin_faq extends adminController
{
	function admin_faq() {
		parent::adminController();
		
		$this->menuPermission("faq");
	}
	
	### DISPLAY ######################
	function index() {
		$oQuestions = $this->loadModel("faq");
		
		// Clear saved form info
		$_SESSION["admin"]["admin_faq"] = null;
		
		$sMinSort = $this->dbResults(
			"SELECT MIN(`sort_order`) FROM `faq`"
			,"one"
		);
		$sMaxSort = $this->dbResults(
			"SELECT MAX(`sort_order`) FROM `faq`"
			,"one"
		);
		
		$this->tplAssign("aCategories", $oQuestions->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aQuestions", $oQuestions->getQuestions($_GET["category"]));
		$this->tplAssign("minSort", $sMinSort);
		$this->tplAssign("maxSort", $sMaxSort);
		$this->tplDisplay("faq/index.tpl");
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
		$this->tplDisplay("faq/add.tpl");
	}
	function add_s() {
		if(empty($_POST["question"]) || count($_POST["categories"]) == 0) {
			$_SESSION["admin"]["admin_faq"] = $_POST;
			$this->forward("/admin/faq/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sOrder = $this->dbResults(
			"SELECT MAX(`sort_order`) + 1 FROM `faq`"
			,"one"
		);
		
		if(empty($sOrder))
			$sOrder = 1;
		
		$sID = $this->dbResults(
			"INSERT INTO `faq`"
				." (`question`, `answer`, `sort_order`, `active`, `created_datetime`, `created_by`, `updated_datetime`, `updated_by`)"
				." VALUES"
				." ("
					.$this->dbQuote($_POST["question"], "text")
					.", ".$this->dbQuote($_POST["answer"], "text")
					.", ".$this->dbQuote($sOrder, "integer")
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
				"INSERT INTO `faq_categories_assign`"
					." (`faqid`, `categoryid`)"
					." VALUES"
					." (".$sID.", ".$sCategory.")"
			);
		}
		
		$_SESSION["admin"]["admin_faq"] = null;
		
		$this->forward("/admin/faq/?notice=".urlencode("Question created successfully!"));
	}
	function sort() {
		$oQuestions = $this->loadModel("faq");
		
		$aQuestion = $oQuestions->getQuestion($this->_urlVars->dynamic["id"], "integer");
		
		if($this->_urlVars->dynamic["sort"] == "up") {
			$aOld = $this->dbResults(
				"SELECT * FROM `faq`"
					." WHERE `sort_order` < ".$aQuestion["sort_order"]
					." ORDER BY `sort_order` DESC"
				,"row"
			);
			
			$this->dbResults(
				"UPDATE `faq` SET"
					." `sort_order` = ".$this->dbQuote($aOld["sort_order"], "text")
					." WHERE `id` = ".$this->dbQuote($aQuestion["id"], "integer")
			);
			
			$this->dbResults(
				"UPDATE `faq` SET"
					." `sort_order` = ".$this->dbQuote($aQuestion["sort_order"], "text")
					." WHERE `id` = ".$this->dbQuote($aOld["id"], "integer")
			);
		} elseif($this->_urlVars->dynamic["sort"] == "down") {
			$aOld = $this->dbResults(
				"SELECT * FROM `faq`"
					." WHERE `sort_order` > ".$aQuestion["sort_order"]
					." ORDER BY `sort_order` ASC"
				,"row"
			);
			
			$this->dbResults(
				"UPDATE `faq` SET"
					." `sort_order` = ".$this->dbQuote($aOld["sort_order"], "text")
					." WHERE `id` = ".$this->dbQuote($aQuestion["id"], "integer")
			);
			
			$this->dbResults(
				"UPDATE `faq` SET"
					." `sort_order` = ".$this->dbQuote($aQuestion["sort_order"], "text")
					." WHERE `id` = ".$this->dbQuote($aOld["id"], "integer")
			);
		}
		
		$this->forward("/admin/faq/?notice=".urlencode("Sort order saved successfully!"));
	}
	function edit() {
		$oQuestions = $this->loadModel("faq");
		
		if(!empty($_SESSION["admin"]["admin_faq"])) {
			$aQuestionRow = $oQuestions->getQuestion($this->_urlVars->dynamic["id"]);
			
			$aQuestion = $_SESSION["admin"]["admin_faq"];
			
			$aQuestion["updated_datetime"] = $aQuestionRow["updated_datetime"];
			$aQuestion["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aQuestionRow["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aQuestion", $aQuestion);
		} else {
			$aQuestion = $oQuestions->getQuestion($this->_urlVars->dynamic["id"], "integer");
			
			$aQuestion["categories"] = $this->dbResults(
				"SELECT `categories`.`id` FROM `faq_categories` AS `categories`"
					." INNER JOIN `faq_categories_assign` AS `faq_assign` ON `categories`.`id` = `faq_assign`.`categoryid`"
					." WHERE `faq_assign`.`faqid` = ".$aQuestion["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"col"
			);
			
			$aQuestion["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aQuestion["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aQuestion", $aQuestion);
		}
		
		$this->tplAssign("aCategories", $oQuestions->getCategories());
		$this->tplDisplay("faq/edit.tpl");
	}
	function edit_s() {
		if(empty($_POST["question"]) || count($_POST["categories"]) == 0) {
			$_SESSION["admin"]["admin_faq"] = $_POST;
			$this->forward("/admin/faq/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->dbResults(
			"UPDATE `faq` SET"
				." `question` = ".$this->dbQuote($_POST["question"], "text")
				.", `answer` = ".$this->dbQuote($_POST["answer"], "text")
				.", `active` = ".$this->boolCheck($_POST["active"])
				.", `updated_datetime` = ".$this->dbQuote(time(), "integer")
				.", `updated_by` = ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
		);
		
		$this->dbResults(
			"DELETE FROM `faq_categories_assign`"
				." WHERE `faqid` = ".$this->dbQuote($_POST["id"], "integer")
		);
		foreach($_POST["categories"] as $sCategory) {
			$this->dbResults(
				"INSERT INTO `faq_categories_assign`"
					." (`faqid`, `categoryid`)"
					." VALUES"
					." (".$this->dbQuote($_POST["id"], "integer").", ".$sCategory.")"
			);
		}
		
		$_SESSION["admin"]["admin_faq"] = null;
		
		$this->forward("/admin/faq/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete() {
		$this->dbResults(
			"DELETE FROM `faq`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);
		$this->dbResults(
			"DELETE FROM `faq_categories_assign`"
				." WHERE `faqid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);
		
		$this->forward("/admin/faq/?notice=".urlencode("Question removed successfully!"));
	}
	function categories_index() {
		$oQuestions = $this->loadModel("faq");
		
		$_SESSION["admin"]["admin_faq_categories"] = null;
		
		$this->tplAssign("aCategories", $oQuestions->getCategories());
		$this->tplDisplay("faq/categories.tpl");
	}
	function categories_add_s() {
		$this->dbResults(
			"INSERT INTO `faq_categories`"
				." (`name`)"
				." VALUES"
				." ("
				.$this->dbQuote($_POST["name"], "text")
				.")"
			,"insert"
		);

		echo "/admin/faq/categories/?notice=".urlencode("Category added successfully!");
	}
	function categories_edit_s() {
		$this->dbResults(
			"UPDATE `faq_categories` SET"
				." `name` = ".$this->dbQuote($_POST["name"], "text")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
		);

		echo "/admin/faq/categories/?notice=".urlencode("Changes saved successfully!");
	}
	function categories_delete() {
		$this->dbResults(
			"DELETE FROM `faq_categories`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);
		$this->dbResults(
			"DELETE FROM `faq_categories_assign`"
				." WHERE `categoryid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);

		$this->forward("/admin/faq/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
}