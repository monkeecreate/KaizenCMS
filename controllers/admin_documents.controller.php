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
			$sSQLCategory .= " WHERE `assign`.`categoryid` = ".$this->db_quote($_GET["category"], "integer");
		}
		
		$aDocuments = $this->db_results(
			"SELECT `documents`.* FROM `documents`"
				.$sSQLCategory
				." GROUP BY `documents`.`id`"
				." ORDER BY `documents`.`name` DESC"
			,"admin->documents->index"
			,"all"
		);
		
		$this->tpl_assign("aCategories", $this->get_categories());
		$this->tpl_assign("sCategory", $_GET["category"]);
		$this->tpl_assign("aDocuments", $aDocuments);
		$this->tpl_display("documents/index.tpl");
	}
	function add()
	{
		if(!empty($_SESSION["admin"]["admin_documents"]))
			$this->tpl_assign("aDocument", $_SESSION["admin"]["admin_documents"]);
		
		else
			$this->tpl_assign("aDocument",
				array(
					"active" => 1
					,"categories" => array()
				)
			);
		
		$this->tpl_assign("aCategories", $this->get_categories());
		$this->tpl_display("documents/add.tpl");
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
		
		$sID = $this->db_results(
			"INSERT INTO `documents`"
				." (`name`, `description`, `active`)"
				." VALUES"
				." ("
					.$this->db_quote($_POST["name"], "text")
					.", ".$this->db_quote($_POST["description"], "text")
					.", ".$this->db_quote($active, "integer")
				.")"
			,"admin->documents->add"
			,"insert"
		);
		
		foreach($_POST["categories"] as $sCategory)
		{
			$this->db_results(
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
				$this->db_results(
					"UPDATE `documents` SET"
						." `active` = 0"
						." WHERE `id` = ".$this->db_quote($sID, "integer")
					,"admin->document->failed_document_upload"
				);
				
				$this->forward("/admin/document/?notice=".urlencode("Document file size was too large!"));
			}
			else
			{
				$upload_dir = $this->_settings->root_public."uploads/documents/";
				$file_ext = pathinfo($_FILES["document"]["name"], PATHINFO_EXTENSION);
				$upload_file = $sID.".".strtolower($file_ext);
			
				if(move_uploaded_file($_FILES["document"]["tmp_name"], $upload_dir.$upload_file))
				{
					$this->db_results(
						"UPDATE `documents` SET"
							." `document` = ".$this->db_quote($upload_file, "text")
							." WHERE `id` = ".$this->db_quote($sID, "integer")
						,"admin->documents->add_document_upload"
					);
				}
				else
				{
					$this->db_results(
						"UPDATE `documents` SET"
							." `active` = 0"
							." WHERE `id` = ".$this->db_quote($sID, "integer")
						,"admin->documents->failed_document_upload"
					);
					
					$this->forward("/admin/documents/?notice=".urlencode("Failed to upload file!"));
				}
			}
		}
		
		$_SESSION["admin"]["admin_documents"] = null;
		
		$this->forward("/admin/documents/?notice=".urlencode("Document created successfully!"));
	}
	function edit($aParams)
	{
		if(!empty($_SESSION["admin"]["admin_documents"]))
			$this->tpl_assign("aDocument", $_SESSION["admin"]["admin_documents"]);
		else
		{
			$aDocument = $this->db_results(
				"SELECT * FROM `documents`"
					." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
				,"admin->documents->edit"
				,"row"
			);
			
			$aDocument["categories"] = $this->db_results(
				"SELECT `categories`.`id` FROM `documents_categories` AS `categories`"
					." INNER JOIN `documents_categories_assign` AS `documents_assign` ON `categories`.`id` = `documents_assign`.`categoryid`"
					." WHERE `documents_assign`.`documentid` = ".$aDocument["id"]
					." GROUP BY `categories`.`id`"
					." ORDER BY `categories`.`name`"
				,"admin->documents->edit->categories"
				,"col"
			);
			
			$this->tpl_assign("aDocument", $aDocument);
		}
		
		$this->tpl_assign("aCategories", $this->get_categories());
		$this->tpl_display("documents/edit.tpl");
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
		
		$this->db_results(
			"UPDATE `documents` SET"
				." `name` = ".$this->db_quote($_POST["name"], "text")
				.", `description` = ".$this->db_quote($_POST["description"], "text")
				.", `active` = ".$this->db_quote($active, "integer")
				." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->documents->edit"
		);
		
		$this->db_results(
			"DELETE FROM `documents_categories_assign`"
				." WHERE `documentid` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->documents->edit->remove_categories"
		);
		foreach($_POST["categories"] as $sCategory)
		{
			$this->db_results(
				"INSERT INTO `documents_categories_assign`"
					." (`documentid`, `categoryid`)"
					." VALUES"
					." (".$this->db_quote($_POST["id"], "integer").", ".$sCategory.")"
				,"admin->documents->edit->categories"
			);
		}
		
		if(!empty($_FILES["document"]["name"]))
		{
			if($_FILES["document"]["error"] == 1)
			{
				$this->db_results(
					"UPDATE `documents` SET"
						." `active` = 0"
						." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
					,"admin->documents->failed_document_upload"
				);
				
				$this->forward("/admin/documents/?notice=".urlencode("Document file size was too large!"));
			}
			else
			{
				$upload_dir = $this->_settings->root_public."uploads/documents/";
				$file_ext = pathinfo($_FILES["document"]["name"], PATHINFO_EXTENSION);
				$upload_file = $_POST["id"].".".strtolower($file_ext);
				
				$sDocument = $this->db_results(
					"SELECT `document` FROM `documents`"
						." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
					,"admin->documents->edit"
					,"one"
				);
				@unlink($upload_dir.$sDocument);
			
				if(move_uploaded_file($_FILES["document"]["tmp_name"], $upload_dir.$upload_file))
				{
					$this->db_results(
						"UPDATE `documents` SET"
							." `document` = ".$this->db_quote($upload_file, "text")
							." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
						,"admin->documents->edit_document_upload"
					);
				}
				else
				{
					$this->db_results(
						"UPDATE `documents` SET"
							." `active` = 0"
							." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
						,"admin->documents->edit_failed_document_upload"
					);
					
					$this->forward("/admin/documents/?notice=".urlencode("Failed to upload file!"));
				}
			}
		}
		
		$_SESSION["admin"]["admin_documents"] = null;
		
		$this->forward("/admin/documents/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete($aParams)
	{
		$aDocument = $this->db_results(
			"SELECT * FROM `documents`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->documents->edit"
			,"row"
		);
		@unlink($this->_settings->root_public."uploads/documents/".$aDocument["document"]);
		
		$this->db_results(
			"DELETE FROM `documents`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->documents->delete"
		);
		$this->db_results(
			"DELETE FROM `documents_categories_assign`"
				." WHERE `documentid` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->documents->categories_assign_delete"
		);
		
		$this->forward("/admin/documents/?notice=".urlencode("Document removed successfully!"));
	}
	function categories_index()
	{
		$_SESSION["admin"]["admin_documents_categories"] = null;
		
		$aCategories = $this->db_results(
			"SELECT * FROM `documents_categories`"
				." ORDER BY `name`"
			,"admin->documents->categories"
			,"all"
		);
		
		$this->tpl_assign("aCategories", $aCategories);
		$this->tpl_display("documents/categories.tpl");
	}
	function categories_add_s()
	{
		$this->db_results(
			"INSERT INTO `documents_categories`"
				." (`name`)"
				." VALUES"
				." ("
				.$this->db_quote($_POST["name"], "text")
				.")"
			,"admin->documents->category->add_s"
			,"insert"
		);

		echo "/admin/documents/categories/?notice=".urlencode("Category added successfully!");
	}
	function categories_edit_s()
	{
		$this->db_results(
			"UPDATE `documents_categories` SET"
				." `name` = ".$this->db_quote($_POST["name"], "text")
				." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->documents->categories->edit"
		);

		echo "/admin/documents/categories/?notice=".urlencode("Changes saved successfully!");
	}
	function categories_delete($aParams)
	{
		$this->db_results(
			"DELETE FROM `documents_categories`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->documents->category->delete"
		);
		$this->db_results(
			"DELETE FROM `documents_categories_assign`"
				." WHERE `categoryid` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->documents->category->delete_assign"
		);

		$this->forward("/admin/documents/categories/?notice=".urlencode("Category removed successfully!"));
	}
	##################################
	
	### Functions ####################
	private function get_categories()
	{
		$aCategories = $this->db_results(
			"SELECT * FROM `documents_categories`"
				." ORDER BY `name`"
			,"admin->documents->get_categories->categories"
			,"all"
		);
		
		return $aCategories;
	}
	##################################
}