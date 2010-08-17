<?php
class admin_directory extends adminController
{
	function __construct() {
		parent::__construct("directory");
		
		$this->menuPermission("directory");
	}
	
	### DISPLAY ######################
	function index() {
		$oDirectory = $this->loadModel("directory");
		
		// Clear saved form info
		$_SESSION["admin"]["admin_directory"] = null;
		
		$this->tplAssign("aCategories", $oDirectory->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aListings", $oDirectory->getListings($_GET["category"], true));
		$this->tplAssign("sUseImage", $oDirectory->useImage);
		
		$this->tplDisplay("admin/index.tpl");
	}
	function add() {
		$oDirectory = $this->loadModel("directory");
		
		if(!empty($_SESSION["admin"]["admin_directory"]))
			$this->tplAssign("aListing", $_SESSION["admin"]["admin_directory"]);
		else
			$this->tplAssign("aListing",
				array(
					"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tplAssign("aCategories", $oDirectory->getCategories());
		$this->tplAssign("sUseCategories", $oDirectory->useCategories);
		$this->tplAssign("sUseImage", $oDirectory->useImage);
		$this->tplAssign("aStates", $oDirectory->aStates);
		
		$this->tplDisplay("admin/add.tpl");
	}
	function add_s() {
		$oDirectory = $this->loadModel("directory");
		
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_directory"] = $_POST;
			$this->forward("/admin/directory/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sID = $this->dbInsert(
			"directory",
			array(
				"name" => $_POST["name"]
				,"address1" => $_POST["address1"]
				,"address2" => $_POST["address2"]
				,"city" => $_POST["city"]
				,"state" => $_POST["state"]
				,"zip" => $_POST["zip"]
				,"phone" => $_POST["phone"]
				,"fax" => $_POST["fax"]
				,"website" => $_POST["website"]
				,"email" => $_POST["email"]
				,"active" => $this->boolCheck($_POST["active"])
				,"created_datetime" => time()
				,"created_by" => $_SESSION["admin"]["userid"]
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			"insert"
		);
		
		if(!empty($_POST["categories"])) {
			foreach($_POST["categories"] as $sCategory) {
				$this->dbInsert(
					"directory_categories_assign",
					array(
						"listingid" => $sID,
						"categoryid" => $sCategory
					)
				);
			}
		}
		
		$_SESSION["admin"]["admin_directory"] = null;
		
		$this->forward("/admin/directory/?notice=".urlencode("Listing created successfully!"));
	}
	function edit() {
		$oDirectory = $this->loadModel("directory");
		
		if(!empty($_SESSION["admin"]["admin_directory"])) {
			$aListingRow = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}directory`"
					." WHERE `id` = ".$this->dbQuote($this->urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aListing = $_SESSION["admin"]["admin_directory"];
			
			$aListing["updated_datetime"] = $aListingRow["updated_datetime"];
			$aListing["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aListingRow["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aListing", $aListing);
		} else {
			$aListing = $oDirectory->getListing($this->urlVars->dynamic["id"], true);
			
			$aListing["categories"] = $this->dbQuery(
				"SELECT `categories`.`id` FROM `{dbPrefix}directory_categories` AS `categories`"
					." INNER JOIN `{dbPrefix}directory_categories_assign` AS `directory_assign` ON `categories`.`id` = `directory_assign`.`categoryid`"
					." WHERE `directory_assign`.`listingid` = ".$aListing["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"col"
			);
			
			$aListing["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aListing["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aListing", $aListing);
		}
		
		$this->tplAssign("aCategories", $oDirectory->getCategories());
		$this->tplAssign("sUseCategories", $oDirectory->useCategories);
		$this->tplAssign("sUseImage", $oDirectory->useImage);
		$this->tplAssign("aStates", $oDirectory->aStates);
		$this->tplDisplay("admin/edit.tpl");
	}
	function edit_s() {
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_directory"] = $_POST;
			$this->forward("/admin/directory/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->dbUpdate(
			"directory",
			array(
				"name" => $_POST["name"]
				,"address1" => $_POST["address1"]
				,"address2" => $_POST["address2"]
				,"city" => $_POST["city"]
				,"state" => $_POST["state"]
				,"zip" => $_POST["zip"]
				,"phone" => $_POST["phone"]
				,"fax" => $_POST["fax"]
				,"website" => $_POST["website"]
				,"email" => $_POST["email"]
				,"active" => $this->boolCheck($_POST["active"])
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			$_POST["id"]
		);
		
		$this->dbDelete("directory_categories_assign", $_POST["id"], "listingid");
		if(!empty($_POST["categories"])) {
			foreach($_POST["categories"] as $sCategory) {
				$this->dbInsert(
					"directory_categories_assign",
					array(
						"listingid" => $_POST["id"],
						"categoryid" => $sCategory
					)
				);
			}
		}
		
		$_SESSION["admin"]["admin_directory"] = null;
		
		$this->forward("/admin/directory/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete() {
		$this->dbDelete("directory", $this->urlVars->dynamic["id"]);
		$this->dbDelete("directory_categories_assign", $this->urlVars->dynamic["id"], "listingid");
		
		$this->forward("/admin/directory/?notice=".urlencode("Listing removed successfully!"));
	}
	function categories_index() {
		$oDirectory = $this->loadModel("directory");
		
		$_SESSION["admin"]["admin_directory_categories"] = null;
		
		$this->tplAssign("aCategories", $oDirectory->getCategories());
		$this->tplAssign("aCategoryEdit", $oDirectory->getCategory($_GET["category"]));
		$this->tplDisplay("admin/categories.tpl");
	}
	function categories_add_s() {
		$this->dbInsert(
			"directory_categories",
			array(
				"name" => $_POST["name"]
			)
		);

		$this->forward("/admin/directory/categories/?notice=".urlencode("Category created successfully!"));
	}
	function categories_edit_s() {
		$this->dbUpdate(
			"directory_categories",
			array(
				"name" => $_POST["name"]
			),
			$_POST["id"]
		);

		$this->forward("/admin/directory/categories/?notice=".urlencode("Changes saved successfully!"));
	}
	function categories_delete() {
		$this->dbDelete("directory_categories", $this->urlVars->dynamic["id"]);
		$this->dbDelete("directory_categories_assign", $this->urlVars->dynamic["id"], "categoryid");

		$this->forward("/admin/directory/categories/?notice=".urlencode("Category removed successfully!"));
	}
}