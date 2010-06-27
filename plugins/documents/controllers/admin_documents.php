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
			"documents",
			array(
				"name" => $_POST["name"]
				,"description" => $_POST["description"]
				,"active" => $this->boolCheck($_POST["active"])
				,"created_datetime" => time()
				,"created_by" => $_SESSION["admin"]["userid"]
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			)
		);
		
		foreach($_POST["categories"] as $sCategory) {
			$this->dbInsert(
				"documents_categories_assign",
				array(
					"documentid" => $sID,
					"categoryid" => $sCategory
				)
			);
		}
		
		if(!empty($_FILES["document"]["name"])) {
			if($_FILES["document"]["error"] == 1) {
				$this->dbUpdate(
					"documents",
					array(
						"active" => 0
					),
					$sID
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
						$this->dbUpdate(
							"documents",
							array(
								"document" => $upload_file
							),
							$sID
						);
					} else {
						$this->dbUpdate(
							"documents",
							array(
								"active" => 0
							),
							$sID
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
		
		$this->dbUpdate(
			"documents",
			array(
				"name" => $_POST["name"]
				,"description" => $_POST["description"]
				,"active" => $this->boolCheck($_POST["active"])
				,"updated_datetime" => time()
				,"updated_by" =>$_SESSION["admin"]["userid"]
			)
			$_POST["id"]
		);
		
		$this->dbDelete("documents_categories_assign", $_POST["id"], "documentid");
		foreach($_POST["categories"] as $sCategory) {
			$this->dbInsert(
				"documents_categories_assign",
				array(
					"documentid" => $_POST["id"],
					"categoryid" => $sCategory
				)
			);
		}
		
		if(!empty($_FILES["document"]["name"])) {
			if($_FILES["document"]["error"] == 1) {
				$this->dbUpdate(
					"documents",
					array(
						"active" => 0
					),
					$_POST["id"]
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
						$this->dbUpdate(
							"documents",
							array(
								"document" => $upload_file
							),
							$_POST["id"]
						);
					} else {
						$this->dbUpdate(
							"documents",
							array(
								"active" => 0
							),
							$_POST["id"]
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
		
		$this->dbDelete("documents", $this->_urlVars->dynamic["id"]);
		$this->dbDelete("documents_categories_assign", $this->_urlVars->dynamic["id"], "documentid");
		
		$this->forward("/admin/documents/?notice=".urlencode("Document removed successfully!"));
	}
	function categories_index() {
		$oDocuments = $this->loadModel("documents");
		
		$_SESSION["admin"]["admin_documents_categories"] = null;
		
		$this->tplAssign("aCategories", $oDocuments->getCategories());
		$this->tplAssign("aCategoryEdit", $oDocuments->getCategory($_GET["category"]));
		$this->tplDisplay("admin/categories.tpl");
	}
	function categories_add_s() {
		$this->dbInsert(
			"documents_categories",
			array(
				"name" => $_POST["name"]
			)
		);

		$this->forward("/admin/documents/categories/?notice=".urlencode("Category created successfully!"));
	}
	function categories_edit_s() {
		$this->dbUpdate(
			"documents_categories",
			array(
				"name" => $_POST["name"]
			),
			$_POST["id"]
		);

		$this->forward("/admin/documents/categories/?notice=".urlencode("Changes saved successfully!"));
	}
	function categories_delete() {
		$this->dbDelete("documents_categories", $this->_urlVars->dynamic["id"]);
		$this->dbDelete("documents_categories_assign", $this->_urlVars->dynamic["id"], "categoryid");

		$this->forward("/admin/documents/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
}