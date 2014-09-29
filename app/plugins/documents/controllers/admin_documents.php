<?php
class admin_documents extends adminController {
	function __construct() {
		parent::__construct("documents");
		
		$this->menuPermission("documents");
	}
	
	### DISPLAY ######################
	function index() {
		// Clear saved form info
		$_SESSION["admin"]["admin_documents"] = null;
		
		$sMinSort = $this->dbQuery(
			"SELECT MIN(`sort_order`) FROM `{dbPrefix}documents`"
			,"one"
		);
		$sMaxSort = $this->dbQuery(
			"SELECT MAX(`sort_order`) FROM `{dbPrefix}documents`"
			,"one"
		);
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aDocuments", $this->model->getDocuments($_GET["category"], true));
		$this->tplAssign("documentFolder", $this->model->documentFolder);
		$this->tplAssign("minSort", $sMinSort);
		$this->tplAssign("maxSort", $sMaxSort);
		$this->tplAssign("sSort", array_shift(explode("-", $this->model->sort)));
		
		$this->tplDisplay("admin/index.php");
	}
	function add() {
		if(!empty($_SESSION["admin"]["admin_documents"])) {
			$this->tplAssign("aDocument", $_SESSION["admin"]["admin_documents"]);
		} else {
			$this->tplAssign("aDocument",
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
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_documents"] = $_POST;
			$this->forward("/admin/documents/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["name"]))))),0,100);
	
		$aDocuments = $this->dbQuery(
			"SELECT `tag` FROM `{dbPrefix}documents`"
				." ORDER BY `tag`"
			,"all"
		);

		if(in_array(array('tag' => $sTag), $aDocuments)) {
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aDocuments);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
		}
		
		$sOrder = $this->dbQuery(
			"SELECT MAX(`sort_order`) + 1 FROM `{dbPrefix}documents`"
			,"one"
		);
		
		if(empty($sOrder)) {
			$sOrder = 1;
		}
		
		$sID = $this->dbInsert(
			"documents",
			array(
				"name" => $_POST["name"]
				,"tag" => $sTag
				,"description" => $_POST["description"]
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
					"documents_categories_assign",
					array(
						"documentid" => $sID,
						"categoryid" => $sCategory
					)
				);
			}
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
				
				$this->forward("/admin/documents/?info=".urlencode("Document file size was too large!"));
			} else {
				$upload_dir = $this->settings->rootPublic.substr($this->model->documentFolder, 1);
				
				if(!is_dir($upload_dir)) {
					mkdir($upload_dir, 0777);
				}
			
				$file_ext = pathinfo($_FILES["document"]["name"], PATHINFO_EXTENSION);
				$upload_file = $sID.".".strtolower($file_ext);
				
				if(in_array($file_ext, $this->model->allowedExt) || empty($this->model->allowedExt)) {
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
				} else {
					$this->forward("/admin/documents/edit/".$sID."/?error=".urlencode("File type not allowed for upload!"));
				}
			}
		}
		
		$_SESSION["admin"]["admin_documents"] = null;
		
		$this->forward("/admin/documents/?info=".urlencode("Document created successfully!"));
	}
	function edit() {
		if(!empty($_SESSION["admin"]["admin_documents"])) {
			$aDocumentRow = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}documents`"
					." WHERE `id` = ".$this->dbQuote($this->urlVars->dynamic["id"], "integer")
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
			$aDocument = $this->model->getDocument($this->urlVars->dynamic["id"], null, true);
			
			$aDocument["categories"] = $this->dbQuery(
				"SELECT `categories`.`id` FROM `{dbPrefix}documents_categories` AS `categories`"
					." INNER JOIN `{dbPrefix}documents_categories_assign` AS `documents_assign` ON `categories`.`id` = `documents_assign`.`categoryid`"
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
		
		$this->tplAssign("aCategories", $this->model->getCategories());
		$this->tplAssign("sUseCategories", $this->model->useCategories);
		$this->tplAssign("aDocument", $aDocument);
		$this->tplDisplay("admin/edit.php");
	}
	function edit_s() {
		if(empty($_POST["name"])) {
			$_SESSION["admin"]["admin_documents"] = $_POST;
			$this->forward("/admin/documents/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sTag = substr(strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["name"]))))),0,100);
	
		$aDocuments = $this->dbQuery(
			"SELECT `tag` FROM `{dbPrefix}documents`"
				." WHERE `id` != ".$this->dbQuote($_POST["id"], "integer")
				." ORDER BY `tag`"
			,"all"
		);

		if(in_array(array('tag' => $sTag), $aDocuments)) {
			$i = 1;
			do {
				$sTempTag = substr($sTag, 0, 100-(strlen($i)+1)).'-'.$i;
				$i++;
				$checkDuplicate = in_array(array('tag' => $sTempTag), $aDocuments);
			} while ($checkDuplicate);
			$sTag = $sTempTag;
		}
		
		$this->dbUpdate(
			"documents",
			array(
				"name" => $_POST["name"]
				,"description" => $_POST["description"]
				,"active" => $this->boolCheck($_POST["active"])
				,"updated_datetime" => time()
				,"updated_by" =>$_SESSION["admin"]["userid"]
			),
			$_POST["id"]
		);
		
		$this->dbDelete("documents_categories_assign", $_POST["id"], "documentid");
		if(!empty($_POST["categories"])) {
			foreach($_POST["categories"] as $sCategory) {
				$this->dbInsert(
					"documents_categories_assign",
					array(
						"documentid" => $_POST["id"],
						"categoryid" => $sCategory
					)
				);
			}
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
				
				$this->forward("/admin/documents/?info=".urlencode("Document file size was too large!"));
			} else {
				$upload_dir = $this->settings->rootPublic.substr($this->model->documentFolder, 1);
				
				if(!is_dir($upload_dir))
					mkdir($upload_dir, 0777);
					
				$file_ext = pathinfo($_FILES["document"]["name"], PATHINFO_EXTENSION);
				$upload_file = $_POST["id"].".".strtolower($file_ext);
				
				if(in_array($file_ext, $this->model->allowedExt) || empty($this->model->allowedExt)) {
					$sDocument = $this->dbQuery(
						"SELECT `document` FROM `{dbPrefix}documents`"
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
					
						$this->forward("/admin/documents/?info=".urlencode("Failed to upload file!"));
					}
				} else
					$this->forward("/admin/documents/?error=".urlencode("File type not allowed for upload!"));
			}
		}
		
		$_SESSION["admin"]["admin_documents"] = null;
		
		$this->forward("/admin/documents/?info=".urlencode("Changes saved successfully!"));
	}
	function delete() {
		$aDocument = $this->model->getDocument($this->urlVars->dynamic["id"], null, true);
		
		@unlink($this->settings->rootPublic.substr($this->model->documentFolder, 1).$aDocument["document"]);
		
		$this->dbDelete("documents", $this->urlVars->dynamic["id"]);
		$this->dbDelete("documents_categories_assign", $this->urlVars->dynamic["id"], "documentid");
		
		$this->forward("/admin/documents/?info=".urlencode("Document removed successfully!"));
	}
	function sort() {
		$aDocument = $this->model->getDocument($this->urlVars->dynamic["id"], null, true);
		
		if($this->urlVars->dynamic["sort"] == "up") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}documents`"
					." WHERE `sort_order` < ".$aDocument["sort_order"]
					." ORDER BY `sort_order` DESC"
				,"row"
			);
		} elseif($this->urlVars->dynamic["sort"] == "down") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}documents`"
					." WHERE `sort_order` > ".$aDocument["sort_order"]
					." ORDER BY `sort_order` ASC"
				,"row"
			);
		}
			
		$this->dbUpdate(
			"documents",
			array(
				"sort_order" => 0
			),
			$aDocument["id"]
		);
		
		$this->dbUpdate(
			"documents",
			array(
				"sort_order" => $aDocument["sort_order"]
			),
			$aOld["id"]
		);
			
		$this->dbUpdate(
			"documents",
			array(
				"sort_order" => $aOld["sort_order"]
			),
			$aDocument["id"]
		);
		
		$this->forward("/admin/documents/?info=".urlencode("Sort order saved successfully!"));
	}
	function categories_index() {
		$_SESSION["admin"]["admin_documents_categories"] = null;
		
		$sMinSort = $this->dbQuery(
			"SELECT MIN(`sort_order`) FROM `{dbPrefix}documents_categories`"
			,"one"
		);
		$sMaxSort = $this->dbQuery(
			"SELECT MAX(`sort_order`) FROM `{dbPrefix}documents_categories`"
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
			"SELECT MAX(`sort_order`) + 1 FROM `{dbPrefix}documents_categories`"
			,"one"
		);
		
		if(empty($sOrder))
			$sOrder = 1;
		
		$this->dbInsert(
			"documents_categories",
			array(
				"name" => $_POST["name"]
				,"sort_order" => $sOrder
			)
		);

		$this->forward("/admin/documents/categories/?info=".urlencode("Category created successfully!"));
	}
	function categories_edit_s() {
		$this->dbUpdate(
			"documents_categories",
			array(
				"name" => $_POST["name"]
			),
			$_POST["id"]
		);

		$this->forward("/admin/documents/categories/?info=".urlencode("Changes saved successfully!"));
	}
	function categories_delete() {
		$this->dbDelete("documents_categories", $this->urlVars->dynamic["id"]);
		$this->dbDelete("documents_categories_assign", $this->urlVars->dynamic["id"], "categoryid");

		$this->forward("/admin/documents/categories/?info=".urlencode("Category removed successfully!"));
	}
	function categories_sort() {
		$aCategory = $this->model->getCategory($this->urlVars->dynamic["id"], "integer");
		
		if($this->urlVars->dynamic["sort"] == "up") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}documents_categories`"
					." WHERE `sort_order` < ".$aCategory["sort_order"]
					." ORDER BY `sort_order` DESC"
				,"row"
			);
		} elseif($this->urlVars->dynamic["sort"] == "down") {
			$aOld = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}documents_categories`"
					." WHERE `sort_order` > ".$aCategory["sort_order"]
					." ORDER BY `sort_order` ASC"
				,"row"
			);
		}
			
		$this->dbUpdate(
			"documents_categories",
			array(
				"sort_order" => 0
			),
			$aCategory["id"]
		);
		
		$this->dbUpdate(
			"documents_categories",
			array(
				"sort_order" => $aCategory["sort_order"]
			),
			$aOld["id"]
		);
			
		$this->dbUpdate(
			"documents_categories",
			array(
				"sort_order" => $aOld["sort_order"]
			),
			$aCategory["id"]
		);
		
		$this->forward("/admin/documents/categories/?info=".urlencode("Sort order saved successfully!"));
	}
	##################################
}