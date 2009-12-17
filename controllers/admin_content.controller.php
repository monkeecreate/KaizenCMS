<?php
class admin_content extends adminController
{
	### DISPLAY ######################
	function index()
	{
		// Clear saved form info
		$_SESSION["admin"]["admin_content"] = null;
		
		$aPages = $this->dbResults(
			"SELECT * FROM `content`"
				." ORDER BY `title`"
			,"admin->content->index"
			,"all"
		);
		
		$this->tpl_assign("aPages", $aPages);
		$this->tpl_assign("domain", $_SERVER["SERVER_NAME"]);
		$this->tpl_display("content/index.tpl");
	}
	function add()
	{
		$this->tpl_assign("aPage", $_SESSION["admin"]["admin_content"]);
		$this->tpl_display("content/add.tpl");
	}
	function add_s()
	{
		if(empty($_POST["title"]))
		{
			$_SESSION["admin"]["admin_content"] = $_POST;
			$this->forward("/admin/content/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sTag = strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["title"])))));
		
		$sID = $this->dbResults(
			"INSERT INTO `content`"
				." (`tag`, `title`, `content`, `created_datetime`, `created_by`, `updated_datetime`, `updated_by`)"
				." VALUES"
				." ("
					.$this->db_quote($sTag, "text")
					.", ".$this->db_quote($_POST["title"], "text")
					.", ".$this->db_quote($_POST["content"], "text")
					.", ".$this->db_quote(time(), "integer")
					.", ".$this->db_quote($_SESSION["admin"]["userid"], "integer")
					.", ".$this->db_quote(time(), "integer")
					.", ".$this->db_quote($_SESSION["admin"]["userid"], "integer")
				.")"
			,"admin->content->add"
			,"insert"
		);
		
		$_SESSION["admin"]["admin_content"] = null;
		
		$this->forward("/admin/content/?notice=".urlencode("Page created successfully!"));
	}
	function edit($aParams)
	{
		if(!empty($_SESSION["admin"]["admin_content"]))
		{
			$aPage = $this->dbResults(
				"SELECT * FROM `content`"
					." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
				,"admin->content->edit"
				,"row"
			);
			
			$aPage = $_SESSION["admin"]["admin_content"];
			
			$aPage["updated_datetime"] = $aPageRow["updated_datetime"];
			$aPage["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aPageRow["updated_by"]
				,"admin->content->edit->updated_by"
				,"row"
			);
			
			$this->tpl_assign("aPage", $aPage);
		}
		else
		{
			$aPage = $this->dbResults(
				"SELECT * FROM `content`"
					." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
				,"admin->content->edit"
				,"row"
			);
			
			$aPage["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aPage["updated_by"]
				,"admin->content->edit->updated_by"
				,"row"
			);
		
			$this->tpl_assign("aPage", $aPage);
		}
		
		$this->tpl_display("content/edit.tpl");
	}
	function edit_s()
	{
		if(empty($_POST["title"]))
		{
			$_SESSION["admin"]["admin_content"] = $_POST;
			$this->forward("/admin/content/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$this->dbResults(
			"UPDATE `content` SET"
				." `title` = ".$this->db_quote($_POST["title"], "text")
				.", `content` = ".$this->db_quote($_POST["content"], "text")
				.", `updated_datetime` = ".$this->db_quote(time(), "integer")
				.", `updated_by` = ".$this->db_quote($_SESSION["admin"]["userid"], "integer")
				." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->content->edit"
		);
		
		$_SESSION["admin"]["admin_content"] = null;
		
		$this->forward("/admin/content/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete($aParams)
	{
		$this->dbResults(
			"DELETE FROM `content`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->content->delete"
		);
		
		$this->forward("/admin/content/?notice=".urlencode("Page removed successfully!"));
	}
	##################################
	
	### Functions ####################
	##################################
}