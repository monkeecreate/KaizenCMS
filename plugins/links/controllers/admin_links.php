<?php
class admin_links extends adminController
{
	function __construct() {
		parent::__construct("links");
		
		$this->menuPermission("links");
	}
	
	### DISPLAY ######################
	function index() {
		$oLinks = $this->loadModel("links");
		
		// Clear saved form info
		$_SESSION["admin"]["admin_links"] = null;
		
		$this->tplAssign("aCategories", $oLinks->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aLinks", $oLinks->getLinks($_GET["category"], true));
		$this->tplDisplay("admin/index.tpl");
	}
	function add() {
		$oLinks = $this->loadModel("links");
		
		if(!empty($_SESSION["admin"]["admin_links"]))
			$this->tplAssign("aLink", $_SESSION["admin"]["admin_links"]);
		
		else
			$this->tplAssign("aLink",
				array(
					"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tplAssign("aCategories", $oLinks->getCategories());
		$this->tplAssign("sUseImage", $oLinks->useImage);
		$this->tplAssign("minWidth", $oLinks->imageMinWidth);
		$this->tplAssign("minHeight", $oLinks->imageMinHeight);
		$this->tplDisplay("admin/add.tpl");
	}
	function add_s() {
		$oLinks = $this->loadModel("links");
		
		if(empty($_POST["name"]) || count($_POST["categories"]) == 0) {
			$_SESSION["admin"]["admin_links"] = $_POST;
			$this->forward("/admin/links/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sID = $this->dbInsert(
			"links",
			array(
				"name" => $_POST["name"]
				,"description" => $_POST["description"]
				,"link" => $_POST["link"]
				,"active" => $this->boolCheck($_POST["active"])
				,"created_datetime" => time()
				,"created_by" => $_SESSION["admin"]["userid"]
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			)
		);
		
		foreach($_POST["categories"] as $sCategory) {
			$this->dbInsert(
				"links_categories_assign",
				array(
					"linkid" => $sID,
					"categoryid" => $sCategory
				)
			);
		}
		
		if(!is_dir($this->settings->rootPublic.substr($oLinks->imageFolder, 1)))
			mkdir($this->settings->rootPublic.substr($oLinks->imageFolder, 1), 0777);
		
		if(!empty($_FILES["image"]["name"])) {			
			if($_FILES["image"]["error"] == 1) {
				$this->dbUpdate(
					"links",
					array(
						"active" => 0
					),
					$sID
				);
				
				$_SESSION["admin"]["admin_links"] = $_POST;
				$this->forward("/admin/links/add/?error=".urlencode("Image file size was too large!"));
			} else {
				$upload_dir = $this->settings->rootPublic."uploads/links/";
				$file_ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
				$upload_file = $sID.".".strtolower($file_ext);
		
				if(move_uploaded_file($_FILES["image"]["tmp_name"], $upload_dir.$upload_file)) {
					if($oLinks->imageMinWidth != 0 && $oLinks->imageMinHeight != 0) {
						$aImageSize = getimagesize($this->settings->rootPublic.substr($oLinks->imageFolder, 1).$upload_file);
						if($aImageSize[0] < $oLinks->imageMinWidth || $aImageSize[1] < $oLinks->imageMinHeight) {
							@unlink($this->settings->rootPublic.substr($oLinks->imageFolder, 1).$upload_file);
							$_SESSION["admin"]["admin_links"] = $_POST;
							$this->forward("/admin/links/add/?error=".urlencode("Image does not meet the minimum width and height requirements."));
						}
					}
					$this->dbUpdate(
						"links",
						array(
							"image" => $upload_file
						),
						$sID
					);
				} else {
					$this->dbUpdate(
						"links",
						array(
							"active" => 0
						),
						$sID
					);

					$this->forward("/admin/links/?notice=".urlencode("Failed to upload image!"));
				}
			}
		}
		
		$_SESSION["admin"]["admin_links"] = null;
		
		$this->forward("/admin/links/?notice=".urlencode("Link created successfully!"));
	}
	function edit() {
		$oLinks = $this->loadModel("links");
		
		if(!empty($_SESSION["admin"]["admin_links"])) {
			$aLinkRow = $oLinks->getLink($this->_urlVars->dynamic["id"]);
			
			$aLink = $_SESSION["admin"]["admin_links"];
			
			$aLink["updated_datetime"] = $aLinkRow["updated_datetime"];
			$aLink["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aLinkRow["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aLink", $aLink);
		} else {
			$aLink = $oLinks->getLink($this->_urlVars->dynamic["id"]);
			
			$aLink["categories"] = $this->dbQuery(
				"SELECT `categories`.`id` FROM `{dbPrefix}links_categories` AS `categories`"
					." INNER JOIN `{dbPrefix}links_categories_assign` AS `links_assign` ON `categories`.`id` = `links_assign`.`categoryid`"
					." WHERE `links_assign`.`linkid` = ".$aLink["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"col"
			);
			
			$aLink["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aLink["updated_by"]
				,"row"
			);
			
			$this->tplAssign("aLink", $aLink);
		}
		
		$this->tplAssign("aCategories", $oLinks->getCategories());
		$this->tplAssign("sUseImage", $oLinks->useImage);
		$this->tplAssign("minWidth", $oLinks->imageMinWidth);
		$this->tplAssign("minHeight", $oLinks->imageMinHeight);
		$this->tplAssign("imageFolder", $oLinks->imageFolder);
		$this->tplDisplay("admin/edit.tpl");
	}
	function edit_s() {
		$oLinks = $this->loadModel("links");
		
		if(empty($_POST["name"]) || count($_POST["categories"]) == 0) {
			$_SESSION["admin"]["admin_links"] = $_POST;
			$this->forward("/admin/links/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->dbUpdate(
			"links",
			array(
				"name" => $_POST["name"]
				,"description" => $_POST["description"]
				,"link" => $_POST["link"]
				,"active" => $this->boolCheck($_POST["active"])
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			$_POST["id"]
		);
		
		$this->dbDelete("links_categories_assign", $_POST["id"], "linkid");
		foreach($_POST["categories"] as $sCategory) {
			$this->dbInsert(
				"links_categories_assign",
				array(
					"linkid" => $_POST["id"],
					"categoryid" => $sCategory
				)
			);
		}
		
		if(!empty($_FILES["image"]["name"])) {
			if($_FILES["image"]["error"] == 1) {
				$this->dbUpdate(
					"links",
					array(
						"active" => 0
					),
					$sID
				);
				
				$this->forward("/admin/links/?notice=".urlencode("Image file size was too large!"));
			} else {
				$upload_dir = $this->settings->rootPublic."uploads/links/";
				$file_ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
				$upload_file = $_POST["id"].".".strtolower($file_ext);
				
				$sImage = $this->dbQuery(
					"SELECT `image` FROM `{dbPrefix}links`"
						." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
					,"one"
				);
				@unlink($upload_dir.$sImage);
			
				if(move_uploaded_file($_FILES["image"]["tmp_name"], $upload_dir.$upload_file)) {
					if($oLinks->imageMinWidth != 0 && $oLinks->imageMinHeight != 0) {
						$aImageSize = getimagesize($this->settings->rootPublic.substr($oLinks->imageFolder, 1).$upload_file);
						if($aImageSize[0] < $oLinks->imageMinWidth || $aImageSize[1] < $oLinks->imageMinHeight) {
							@unlink($this->settings->rootPublic.substr($oLinks->imageFolder, 1).$upload_file);
							$this->forward("/admin/links/edit/".$_POST["id"]."/?error=".urlencode("Image does not meet the minimum width and height requirements."));
						}
					}
					
					$this->dbUpdate(
						"links",
						array(
							"image" => $upload_file
						),
						$sID
					);
				} else {
					$this->dbUpdate(
						"links",
						array(
							"active" => 0
						),
						$sID
					);
					
					$this->forward("/admin/links/?notice=".urlencode("Failed to upload image!"));
				}
			}
		}
		
		$_SESSION["admin"]["admin_links"] = null;
		
		$this->forward("/admin/links/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete() {
		$oLinks = $this->loadModel("links");
		
		$aLink = $oLinks->getLink($this->_urlVars->dynamic["id"]);
		
		$this->dbDelete("links", $this->_urlVars->dynamic["id"]);
		$this->dbDelete("links_categories_assign", $this->_urlVars->dynamic["id"], "linkid");
		
		@unlink($this->settings->rootPublic.substr($oLinks->imageFolder, 1).$aLink["image"]);
		
		$this->forward("/admin/links/?notice=".urlencode("Link removed successfully!"));
	}
	function categories_index() {
		$oLinks = $this->loadModel("links");
		
		$_SESSION["admin"]["admin_links_categories"] = null;
		
		$this->tplAssign("aCategories", $oLinks->getCategories());
		$this->tplAssign("aCategoryEdit", $oLinks->getCategory($_GET["category"]));
		$this->tplDisplay("admin/categories.tpl");
	}
	function categories_add_s() {
		$this->dbInsert(
			"links_categories",
			array(
				"name" => $_POST["name"]
			)
		);

		$this->forward("/admin/links/categories/?notice=".urlencode("Category created successfully!"));
	}
	function categories_edit_s() {
		$this->dbUpdate(
			"links_categories",
			array(
				"name" => $_POST["name"]
			),
			$_POST["id"]
		);

		$this->forward("/admin/links/categories/?notice=".urlencode("Changes saved successfully!"));
	}
	function categories_delete() {
		$this->dbDelete("links_categories", $this->_urlVars->dynamic["id"]);
		$this->dbDelete("links_categories_assign", $this->_urlVars->dynamic["id"], "categoryid");

		$this->forward("/admin/links/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
}