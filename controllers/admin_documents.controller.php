<?php
class admin_documents extends adminController
{
	### DISPLAY ######################
	function index()
	{
		// Clear saved form info
		$_SESSION["admin"]["admin_documents"] = null;
		
		if(!empty($_GET["category"]))
		{
			$sSQLCategory = " INNER JOIN `documents_categories_assign` AS `assign` ON `documents`.`id` = `assign`.`documentid`";
			$sSQLCategory .= " WHERE `assign`.`categoryid` = ".$this->dbQuote($_GET["category"], "integer");
		}
		
		$aDocuments = $this->dbResults(
			"SELECT `documents`.* FROM `documents`"
				.$sSQLCategory
				." GROUP BY `documents`.`id`"
				." ORDER BY `documents`.`name` DESC"
			,"admin->documents->index"
			,"all"
		);
		
		$this->tplAssign("aCategories", $this->get_categories());
		$this->tplAssign("sCategory", $_GET["category"]);
		$this->tplAssign("aDocuments", $aDocuments);
		$this->tplDisplay("documents/index.tpl");
	}
	function add()
	{
		if(!empty($_SESSION["admin"]["admin_documents"]))
			$this->tplAssign("aDocument", $_SESSION["admin"]["admin_documents"]);
		
		else
			$this->tplAssign("aDocument",
				array(
					"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tplAssign("aCategories", $this->get_categories());
		$this->tplDisplay("documents/add.tpl");
	}
	function add_s()
	{
		if(empty($_POST["name"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_documents"] = $_POST;
			$this->forward("/admin/documents/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		if(!empty($_POST["active"]))
			$active = 1;
		else
			$active = 0;
		
		$sID = $this->dbResults(
			"INSERT INTO `documents`"
				." (`name`, `description`, `active`, `created_datetime`, `created_by`, `updated_datetime`, `updated_by`)"
				." VALUES"
				." ("
					.$this->dbQuote($_POST["name"], "text")
					.", ".$this->dbQuote($_POST["description"], "text")
					.", ".$this->dbQuote($active, "integer")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				.")"
			,"admin->documents->add"
			,"insert"
		);
		
		foreach($_POST["categories"] as $sCategory)
		{
			$this->dbResults(
				"INSERT INTO `documents_categories_assign`"
					." (`documentid`, `categoryid`)"
					." VALUES"
					." (".$sID.", ".$sCategory.")"
				,"admin->documents->add->categories"
			);
		}
		
		if(!empty($_FILES["document"]["name"]))
		{
			if($_FILES["document"]["error"] == 1)
			{
				$this->dbResults(
					"UPDATE `documents` SET"
						." `active` = 0"
						." WHERE `id` = ".$this->dbQuote($sID, "integer")
					,"admin->document->failed_document_upload"
				);
				
				$this->forward("/admin/document/?notice=".urlencode("Document file size was too large!"));
			}
			else
			{
				$upload_dir = $this->_settings->rootPublic."uploads/documents/";
				$file_ext = pathinfo($_FILES["document"]["name"], PATHINFO_EXTENSION);
				$upload_file = $sID.".".strtolower($file_ext);
			
				if(move_uploaded_file($_FILES["document"]["tmp_name"], $upload_dir.$upload_file))
				{
					$this->dbResults(
						"UPDATE `documents` SET"
							." `document` = ".$this->dbQuote($upload_file, "text")
							." WHERE `id` = ".$this->dbQuote($sID, "integer")
						,"admin->documents->add_document_upload"
					);
				}
				else
				{
					$this->dbResults(
						"UPDATE `documents` SET"
							." `active` = 0"
							." WHERE `id` = ".$this->dbQuote($sID, "integer")
						,"admin->documents->failed_document_upload"
					);
					
					$this->forward("/admin/documents/?notice=".urlencode("Failed to upload file!"));
				}
			}
		}
		
		$_SESSION["admin"]["admin_documents"] = null;
		
		$this->forward("/admin/documents/?notice=".urlencode("Document created successfully!"));
	}
	function edit()
	{
		if(!empty($_SESSION["admin"]["admin_documents"]))
		{
			$aDocumentRow = $this->dbResults(
				"SELECT * FROM `documents`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"admin->documents->edit"
				,"row"
			);
			
			$aDocument = $_SESSION["admin"]["admin_documents"];
			
			$aDocument["updated_datetime"] = $aDocumentRow["updated_datetime"];
			$aDocument["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aDocumentRow["updated_by"]
				,"admin->documents->edit->updated_by"
				,"row"
			);
			
			$this->tplAssign("aDocument", $aDocument);
		}
		else
		{
			$aDocument = $this->dbResults(
				"SELECT * FROM `documents`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"admin->documents->edit"
				,"row"
			);
			
			$aDocument["categories"] = $this->dbResults(
				"SELECT `categories`.`id` FROM `documents_categories` AS `categories`"
					." INNER JOIN `documents_categories_assign` AS `documents_assign` ON `categories`.`id` = `documents_assign`.`categoryid`"
					." WHERE `documents_assign`.`documentid` = ".$aDocument["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"admin->documents->edit->categories"
				,"col"
			);
			
			$aDocument["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aDocument["updated_by"]
				,"admin->documents->edit->updated_by"
				,"row"
			);
			
			$this->tplAssign("aDocument", $aDocument);
		}
		
		$this->tplAssign("aCategories", $this->get_categories());
		$this->tplDisplay("documents/edit.tpl");
	}
	function edit_s()
	{
		if(empty($_POST["name"]) || count($_POST["categories"]) == 0)
		{
			$_SESSION["admin"]["admin_documents"] = $_POST;
			$this->forward("/admin/documents/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		if(!empty($_POST["active"]))
			$active = 1;
		else
			$active = 0;
		
		$this->dbResults(
			"UPDATE `documents` SET"
				." `name` = ".$this->dbQuote($_POST["name"], "text")
				.", `description` = ".$this->dbQuote($_POST["description"], "text")
				.", `active` = ".$this->dbQuote($active, "integer")
				.", `updated_datetime` = ".$this->dbQuote(time(), "integer")
				.", `updated_by` = ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
			,"admin->documents->edit"
		);
		
		$this->dbResults(
			"DELETE FROM `documents_categories_assign`"
				." WHERE `documentid` = ".$this->dbQuote($_POST["id"], "integer")
			,"admin->documents->edit->remove_categories"
		);
		foreach($_POST["categories"] as $sCategory)
		{
			$this->dbResults(
				"INSERT INTO `documents_categories_assign`"
					." (`documentid`, `categoryid`)"
					." VALUES"
					." (".$this->dbQuote($_POST["id"], "integer").", ".$sCategory.")"
				,"admin->documents->edit->categories"
			);
		}
		
		if(!empty($_FILES["document"]["name"]))
		{
			if($_FILES["document"]["error"] == 1)
			{
				$this->dbResults(
					"UPDATE `documents` SET"
						." `active` = 0"
						." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
					,"admin->documents->failed_document_upload"
				);
				
				$this->forward("/admin/documents/?notice=".urlencode("Document file size was too large!"));
			}
			else
			{
				$upload_dir = $this->_settings->rootPublic."uploads/documents/";
				$file_ext = pathinfo($_FILES["document"]["name"], PATHINFO_EXTENSION);
				$upload_file = $_POST["id"].".".strtolower($file_ext);
				
				$sDocument = $this->dbResults(
					"SELECT `document` FROM `documents`"
						." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
					,"admin->documents->edit"
					,"one"
				);
				@unlink($upload_dir.$sDocument);
			
				if(move_uploaded_file($_FILES["document"]["tmp_name"], $upload_dir.$upload_file))
				{
					$this->dbResults(
						"UPDATE `documents` SET"
							." `document` = ".$this->dbQuote($upload_file, "text")
							." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
						,"admin->documents->edit_document_upload"
					);
				}
				else
				{
					$this->dbResults(
						"UPDATE `documents` SET"
							." `active` = 0"
							." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
						,"admin->documents->edit_failed_document_upload"
					);
					
					$this->forward("/admin/documents/?notice=".urlencode("Failed to upload file!"));
				}
			}
		}
		
		$_SESSION["admin"]["admin_documents"] = null;
		
		$this->forward("/admin/documents/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete()
	{
		$aDocument = $this->dbResults(
			"SELECT * FROM `documents`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"admin->documents->edit"
			,"row"
		);
		@unlink($this->_settings->rootPublic."uploads/documents/".$aDocument["document"]);
		
		$this->dbResults(
			"DELETE FROM `documents`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"admin->documents->delete"
		);
		$this->dbResults(
			"DELETE FROM `documents_categories_assign`"
				." WHERE `documentid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"admin->documents->categories_assign_delete"
		);
		
		$this->forward("/admin/documents/?notice=".urlencode("Document removed successfully!"));
	}
	function categories_index()
	{
		$_SESSION["admin"]["admin_documents_categories"] = null;
		
		$aCategories = $this->dbResults(
			"SELECT * FROM `documents_categories`"
				." ORDER BY `name`"
			,"admin->documents->categories"
			,"all"
		);
		
		$this->tplAssign("aCategories", $aCategories);
		$this->tplDisplay("documents/categories.tpl");
	}
	function categories_add_s()
	{
		$this->dbResults(
			"INSERT INTO `documents_categories`"
				." (`name`)"
				." VALUES"
				." ("
				.$this->dbQuote($_POST["name"], "text")
				.")"
			,"admin->documents->category->add_s"
			,"insert"
		);

		echo "/admin/documents/categories/?notice=".urlencode("Category added successfully!");
	}
	function categories_edit_s()
	{
		$this->dbResults(
			"UPDATE `documents_categories` SET"
				." `name` = ".$this->dbQuote($_POST["name"], "text")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
			,"admin->documents->categories->edit"
		);

		echo "/admin/documents/categories/?notice=".urlencode("Changes saved successfully!");
	}
	function categories_delete()
	{
		$this->dbResults(
			"DELETE FROM `documents_categories`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"admin->documents->category->delete"
		);
		$this->dbResults(
			"DELETE FROM `documents_categories_assign`"
				." WHERE `categoryid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"admin->documents->category->delete_assign"
		);

		$this->forward("/admin/documents/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
	
	### Functions ####################
	private function get_categories()
	{
		$aCategories = $this->dbResults(
			"SELECT * FROM `documents_categories`"
				." ORDER BY `name`"
			,"admin->documents->get_categories->categories"
			,"all"
		);
		
		return $aCategories;
	}
	##################################
}