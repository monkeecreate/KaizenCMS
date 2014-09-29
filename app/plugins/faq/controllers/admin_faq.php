<?php
class admin_faq extends adminController {
	function __construct() {
		parent::__construct("faq");
		
		$this->menuPermission("faq");
	}
	
	### DISPLAY ######################
	function index() {		
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
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aQuestions", $this->model->getQuestions($_GET["category"], true));
		$this->tplAssign("minSort", $sMinSort);
		$this->tplAssign("maxSort", $sMaxSort);
		$this->tplAssign("sSort", array_shift(explode("-", $this->model->sort)));
		
		$this->tplDisplay("admin/index.php");
	}
	function add() {		
		if(!empty($_SESSION["admin"]["admin_faq"])) {
			$this->tplAssign("aQuestion", $_SESSION["admin"]["admin_faq"]);
		} else {
			$this->tplAssign("aQuestion",
				array(
					"active" => 1
					,"categories" => array()
				)
			);
		}
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("sUseCategories", $this->model->useCategories);
		$this->tplDisplay("admin/add.php");
	}
	function add_s() {
		if(empty($_POST["question"])) {
			$_SESSION["admin"]["admin_faq"] = $_POST;
			$this->forward("/admin/faq/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["question"]))))),0,100);
	
		$aQuestions = $this->dbQuery(
			"SELECT `tag` FROM `{dbPrefix}faq`"
				." ORDER BY `tag`"
			,"all"
		);

		if(in_array(array('tag' => $sTag), $aQuestions)) {
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aQuestions);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
		}
		
		$sOrder = $this->dbQuery(
			"SELECT MAX(`sort_order`) + 1 FROM `{dbPrefix}faq`"
			,"one"
		);
		
		if(empty($sOrder)) {
			$sOrder = 1;
		}
		
		$sID = $this->dbInsert(
			"faq",
			array(
				"question" => (string)substr($_POST["question"], 0, 255)
				,"answer" => $_POST["answer"]
				,"tag" => $sTag
				,"sort_order" => $sOrder
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
					"faq_categories_assign",
					array(
						"faqid" => $sID,
						"categoryid" => $sCategory
					)
				);
			}
		}
		
		$_SESSION["admin"]["admin_faq"] = null;
		
		$this->forward("/admin/faq/?info=".urlencode("Question created successfully!"));
	}
	function edit() {		
		if(!empty($_SESSION["admin"]["admin_faq"])) {
			$aQuestionRow = $this->model->getQuestion($this->urlVars->dynamic["id"], null, true);
			
			$aQuestion = $_SESSION["admin"]["admin_faq"];
			
			$aQuestion["updated_datetime"] = $aQuestionRow["updated_datetime"];
			$aQuestion["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aQuestionRow["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aQuestion", $aQuestion);
		} else {
			$aQuestion = $this->model->getQuestion($this->urlVars->dynamic["id"], null, true);
			
			$aQuestion["categories"] = $this->dbQuery(
				"SELECT `categories`.`id` FROM `{dbPrefix}faq_categories` AS `categories`"
					." INNER JOIN `{dbPrefix}faq_categories_assign` AS `faq_assign` ON `categories`.`id` = `faq_assign`.`categoryid`"
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
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("sUseCategories", $this->model->useCategories);
		$this->tplDisplay("admin/edit.php");
	}
	function edit_s() {
		if(empty($_POST["question"])) {
			$_SESSION["admin"]["admin_faq"] = $_POST;
			$this->forward("/admin/faq/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["question"]))))),0,100);
	
		$aQuestions = $this->dbQuery(
			"SELECT `tag` FROM `{dbPrefix}faq`"
				." WHERE `id` != ".$this->dbQuote($_POST["id"], "integer")
				." ORDER BY `tag`"
			,"all"
		);

		if(in_array(array('tag' => $sTag), $aQuestions)) {
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aQuestions);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
		}
		
		$this->dbUpdate(
			"faq",
			array(
				"question" => (string)substr($_POST["question"], 0, 255)
				,"answer" => $_POST["answer"]
				,"tag" => $sTag
				,"active" => $this->boolCheck($_POST["active"])
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			$_POST["id"]
		);
		
		$this->dbDelete("faq_categories_assign", $_POST["id"], "faqid");
		if(!empty($_POST["categories"])) {
			foreach($_POST["categories"] as $sCategory) {
				$this->dbInsert(
					"faq_categories_assign",
					array(
						"faqid" => $_POST["id"],
						"categoryid" => $sCategory
					)
				);
			}
		}
		
		$_SESSION["admin"]["admin_faq"] = null;
		
		$this->forward("/admin/faq/?info=".urlencode("Changes saved successfully!"));
	}
	function delete() {
		$this->dbDelete("faq", $this->urlVars->dynamic["id"]);
		$this->dbDelete("faq_categories_assign", $this->urlVars->dynamic["id"], "faqid");
		
		$this->forward("/admin/faq/?info=".urlencode("Question removed successfully!"));
	}
	function sort() {		
		$aQuestion = $this->model->getQuestion($this->urlVars->dynamic["id"], null, true);
		
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
				"sort_order" => 0
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
			
		$this->dbUpdate(
			"faq",
			array(
				"sort_order" => $aOld["sort_order"]
			),
			$aQuestion["id"]
		);
		
		$this->forward("/admin/faq/?info=".urlencode("Sort order saved successfully!"));
	}
	function categories_index() {		
		$_SESSION["admin"]["admin_faq_categories"] = null;
		
		$sMinSort = $this->dbQuery(
			"SELECT MIN(`sort_order`) FROM `{dbPrefix}faq_categories`"
			,"one"
		);
		$sMaxSort = $this->dbQuery(
			"SELECT MAX(`sort_order`) FROM `{dbPrefix}faq_categories`"
			,"one"
		);
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("aCategoryEdit", $this->model->getCategory($_GET["category"]));
		$this->tplAssign("minSort", $sMinSort);
		$this->tplAssign("maxSort", $sMaxSort);
		$this->tplAssign("sSort", array_shift(explode("-", $this->model->sortCategory)));
		
		$this->tplDisplay("admin/categories.php");
	}
	function categories_add_s() {
		$sOrder = $this->dbQuery(
			"SELECT MAX(`sort_order`) + 1 FROM `{dbPrefix}faq_categories`"
			,"one"
		);
		
		if(empty($sOrder))
			$sOrder = 1;
		
		$this->dbInsert(
			"faq_categories",
			array(
				"name" => $_POST["name"]
				,"sort_order" => $sOrder
			)
		);

		$this->forward("/admin/faq/categories/?info=".urlencode("Category created successfully!"));
	}
	function categories_edit_s() {
		$this->dbUpdate(
			"faq_categories",
			array(
				"name" => $_POST["name"]
			),
			$_POST["id"]
		);

		$this->forward("/admin/faq/categories/?info=".urlencode("Changes saved successfully!"));
	}
	function categories_delete() {
		$this->dbDelete("faq_categories", $this->urlVars->dynamic["id"]);
		$this->dbDelete("faq_categories_assign", $this->urlVars->dynamic["id"], "categoryid");

		$this->forward("/admin/faq/categories/?info=".urlencode("Category removed successfully!"));
	}
	function categories_sort() {
		$aCategory = $this->model->getCategory($this->urlVars->dynamic["id"], "integer");
		
		if($this->urlVars->dynamic["sort"] == "up") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}faq_categories`"
					." WHERE `sort_order` < ".$aCategory["sort_order"]
					." ORDER BY `sort_order` DESC"
				,"row"
			);
		} elseif($this->urlVars->dynamic["sort"] == "down") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}faq_categories`"
					." WHERE `sort_order` > ".$aCategory["sort_order"]
					." ORDER BY `sort_order` ASC"
				,"row"
			);
		}
			
		$this->dbUpdate(
			"faq_categories",
			array(
				"sort_order" => 0
			),
			$aCategory["id"]
		);
		
		$this->dbUpdate(
			"faq_categories",
			array(
				"sort_order" => $aCategory["sort_order"]
			),
			$aOld["id"]
		);
			
		$this->dbUpdate(
			"faq_categories",
			array(
				"sort_order" => $aOld["sort_order"]
			),
			$aCategory["id"]
		);
		
		$this->forward("/admin/faq/categories/?info=".urlencode("Sort order saved successfully!"));
	}
	##################################
}