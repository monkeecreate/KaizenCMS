<?php
class admin_documents extends adminController
{
	function __construct() {
		parent::__construct("documents");
		
		$this->menuPermission("documents");
	}
	
	### DISPLAY ######################
	function index() {
		$oDocuments = $this->loadModel("documents");
		
		// Clear saved form info
		$_SESSION["admin"]["admin_documents"] = null;
		
		$this->tplAssign("aCategories", $oDocuments->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aDocuments", $oDocuments->getDocuments($_GET["category"], true));
		$this->tplAssign("documentFolder", $oDocuments->documentFolder);
		$this->tplDisplay("admin/index.tpl");
	}
	function add() {
		$oDocuments = $this->loadModel("documents");
		
		if(!empty($_SESSION["admin"]["admin_documents"]))
			$this->tplAssign("aDocument", $_SESSION["admin"]["admin_documents"]);
		
		else
			$this->tplAssign("aDocument",
				array(
					"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tplAssign("aCategories", $oDocuments->getCategories());
		$this->tplDisplay("admin/add.tpl");
	}
	function add_s() {
		$oDocuments = $this->loadModel("documents");
		
		if(empty($_POST["name"]) || count($_POST["categories"]) == 0) {
			$_SESSION["admin"]["admin_documents"] = $_POST;
			$this->forward("/admin/documents/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sID = $this->dbQuery(
			"INSERT INTO `{dbPrefix}documents`"
				." (`name`, `description`, `active`, `created_datetime`, `created_by`, `updated_datetime`, `updated_by`)"
				." VALUES"
				." ("
					.$this->dbQuote($_POST["name"], "text")
					.", ".$this->dbQuote($_POST["description"], "text")
					.", ".$this->boolCheck($_POST["active"])
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				.")"
			,"insert"
		);
		
		foreach($_POST["categories"] as $sCategory) {
			$this->dbQuery(
				"INSERT INTO `{dbPrefix}documents_categories_assign`"
					." (`documentid`, `categoryid`)"
					." VALUES"
					." (".$sID.", ".$sCategory.")"
			);
		}
		
		if(!empty($_FILES["document"]["name"])) {
			if($_FILES["document"]["error"] == 1) {
				$this->dbQuery(
					"UPDATE `{dbPrefix}documents` SET"
						." `active` = 0"
						." WHERE `id` = ".$this->dbQuote($sID, "integer")
				);
				
				$this->forward("/admin/document/?notice=".urlencode("Document file size was too large!"));
			} else {
				$upload_dir = $this->_settings->rootPublic.substr($oDocuments->documentFolder, 1);
				
				if(!is_dir($upload_dir))
					mkdir($upload_dir, 0777);
			
				$file_ext = pathinfo($_FILES["document"]["name"], PATHINFO_EXTENSION);
				$upload_file = $sID.".".strtolower($file_ext);
				
				if(in_array($file_ext, $oDocuments->allowedExt) || empty($oDocuments->allowedExt)) {
					if(move_uploaded_file($_FILES["document"]["tmp_name"], $upload_dir.$upload_file)) {
						$this->dbQuery(
							"UPDATE `{dbPrefix}documents` SET"
								." `document` = ".$this->dbQuote($upload_file, "text")
								." WHERE `id` = ".$this->dbQuote($sID, "integer")
						);
					} else {
						$this->dbQuery(
							"UPDATE `{dbPrefix}documents` SET"
								." `active` = 0"
								." WHERE `id` = ".$this->dbQuote($sID, "integer")
						);
						
						$this->forward("/admin/documents/edit/".$sID."/?error=".urlencode("Failed to upload file!"));
					}
				} else
					$this->forward("/admin/documents/edit/".$sID."/?error=".urlencode("File type not allowed for upload!"));
			}
		}
		
		$_SESSION["admin"]["admin_documents"] = null;
		
		$this->forward("/admin/documents/?notice=".urlencode("Document created successfully!"));
	}
	function edit() {
		$oDocuments = $this->loadModel("documents");
		
		if(!empty($_SESSION["admin"]["admin_documents"])) {
			$aDocumentRow = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}documents`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aDocument = $_SESSION["admin"]["admin_documents"];
			
			$aDocument["updated_datetime"] = $aDocumentRow["updated_datetime"];
			$aDocument["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aDocumentRow["updated_by"]
				,"row"
			);
		} else {
			$aDocument = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}documents`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"row"
			);
			
			$aDocument["categories"] = $this->dbQuery(
				"SELECT `categories`.`id` FROM `{dbPrefix}documents_categories` AS `categories`"
					." INNER JOIN `documents_categories_assign` AS `documents_assign` ON `categories`.`id` = `documents_assign`.`categoryid`"
					." WHERE `documents_assign`.`documentid` = ".$aDocument["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"col"
			);
			
			$aDocument["updated_by"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$aDocument["updated_by"]
				,"row"
			);
		}
		
		$this->tplAssign("aCategories", $oDocuments->getCategories());
		$this->tplAssign("aDocument", $aDocument);
		$this->tplDisplay("admin/edit.tpl");
	}
	function edit_s() {
		$oDocuments = $this->loadModel("documents");
		
		if(empty($_POST["name"]) || count($_POST["categories"]) == 0) {
			$_SESSION["admin"]["admin_documents"] = $_POST;
			$this->forward("/admin/documents/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->dbQuery(
			"UPDATE `{dbPrefix}documents` SET"
				." `name` = ".$this->dbQuote($_POST["name"], "text")
				.", `description` = ".$this->dbQuote($_POST["description"], "text")
				.", `active` = ".$this->boolCheck($_POST["active"], "integer")
				.", `updated_datetime` = ".$this->dbQuote(time(), "integer")
				.", `updated_by` = ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
		);
		
		$this->dbQuery(
			"DELETE FROM `{dbPrefix}documents_categories_assign`"
				." WHERE `documentid` = ".$this->dbQuote($_POST["id"], "integer")
		);
		foreach($_POST["categories"] as $sCategory) {
			$this->dbQuery(
				"INSERT INTO `{dbPrefix}documents_categories_assign`"
					." (`documentid`, `categoryid`)"
					." VALUES"
					." (".$this->dbQuote($_POST["id"], "integer").", ".$sCategory.")"
			);
		}
		
		if(!empty($_FILES["document"]["name"])) {
			if($_FILES["document"]["error"] == 1) {
				$this->dbQuery(
					"UPDATE `{dbPrefix}documents` SET"
						." `active` = 0"
						." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
				);
				
				$this->forward("/admin/documents/?notice=".urlencode("Document file size was too large!"));
			} else {
				$upload_dir = $this->_settings->rootPublic.substr($oDocuments->documentFolder, 1);
				
				if(!is_dir($upload_dir))
					mkdir($upload_dir, 0777);
					
				$file_ext = pathinfo($_FILES["document"]["name"], PATHINFO_EXTENSION);
				$upload_file = $_POST["id"].".".strtolower($file_ext);
				
				if(in_array($file_ext, $oDocuments->allowedExt) || empty($oDocuments->allowedExt)) {
					$sDocument = $this->dbQuery(
						"SELECT `{dbPrefix}document` FROM `documents`"
							." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
						,"one"
					);
					@unlink($upload_dir.$sDocument);
			
					if(move_uploaded_file($_FILES["document"]["tmp_name"], $upload_dir.$upload_file)) {
						$this->dbQuery(
							"UPDATE `{dbPrefix}documents` SET"
								." `document` = ".$this->dbQuote($upload_file, "text")
								." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
						);
					} else {
						$this->dbQuery(
							"UPDATE `{dbPrefix}documents` SET"
								." `active` = 0"
								." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
						);
					
						$this->forward("/admin/documents/?notice=".urlencode("Failed to upload file!"));
					}
				} else
					$this->forward("/admin/documents/?error=".urlencode("File type not allowed for upload!"));
			}
		}
		
		$_SESSION["admin"]["admin_documents"] = null;
		
		$this->forward("/admin/documents/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete() {
		$oDocuments = $this->loadModel("documents");
		
		$aDocument = $oDocuments->getDocument($this->_urlVars->dynamic["id"], "integer");
		
		@unlink($this->_settings->rootPublic.substr($oDocuments->documentFolder, 1).$aDocument["document"]);
		
		$this->dbQuery(
			"DELETE FROM `{dbPrefix}documents`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);
		$this->dbQuery(
			"DELETE FROM `{dbPrefix}documents_categories_assign`"
				." WHERE `documentid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);
		
		$this->forward("/admin/documents/?notice=".urlencode("Document removed successfully!"));
	}
	function categories_index() {
		$oDocuments = $this->loadModel("documents");
		
		$_SESSION["admin"]["admin_documents_categories"] = null;
		
		$aCategories = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}documents_categories`"
				." ORDER BY `name`"
			,"all"
		);
		
		$this->tplAssign("aCategories", $aCategories);
		$this->tplAssign("aCategoryEdit", $oDocuments->getCategory($_GET["category"]));
		$this->tplDisplay("admin/categories.tpl");
	}
	function categories_add_s() {
		$this->dbQuery(
			"INSERT INTO `{dbPrefix}documents_categories`"
				." (`name`)"
				." VALUES"
				." ("
				.$this->dbQuote($_POST["name"], "text")
				.")"
			,"insert"
		);

		$this->forward("/admin/documents/categories/?notice=".urlencode("Category created successfully!"));
	}
	function categories_edit_s() {
		$this->dbQuery(
			"UPDATE `{dbPrefix}documents_categories` SET"
				." `name` = ".$this->dbQuote($_POST["name"], "text")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
		);

		$this->forward("/admin/documents/categories/?notice=".urlencode("Changes saved successfully!"));
	}
	function categories_delete() {
		$this->dbQuery(
			"DELETE FROM `{dbPrefix}documents_categories`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);
		$this->dbQuery(
			"DELETE FROM `{dbPrefix}documents_categories_assign`"
				." WHERE `categoryid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
		);

		$this->forward("/admin/documents/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
}