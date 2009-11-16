<?php
class admin_content extends adminController
{
	### DISPLAY ######################
	function index()
	{
		// Clear saved form info
		$_SESSION["admin"]["admin_content"] = null;
		
		$aPages = $this->db_results(
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
		if(empty($_POST["header_title"]) || empty($_POST["title"]))
		{
			$_SESSION["admin"]["admin_content"] = $_POST;
			$this->forward("/admin/content/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$sTag = strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($_POST["title"])))));
		
		$aRes = $this->db_results(
			"INSERT INTO `content`"
				." (`tag`, `title`, `content`)"
				." VALUES"
				." ("
					.$this->db_quote($sTag, "text")
					.", ".$this->db_quote($_POST["title"], "text")
					.", ".$this->db_quote($_POST["content"], "text")
				.")"
			,"admin->content->add"
		);
		
		$_SESSION["admin"]["admin_content"] = null;
		
		$this->forward("/admin/content/?notice=".urlencode("Page created successfully!"));
	}
	function edit($aParams)
	{
		if(!empty($_SESSION["admin"]["admin_content"]))
			$this->tpl_assign("aPage", $_SESSION["admin"]["admin_content"]);
		else
		{
			$aPage = $this->db_results(
				"SELECT * FROM `content`"
					." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
				,"admin->content->edit"
				,"row"
			);
		
			$this->tpl_assign("aPage", $aPage);
		}
		
		$this->tpl_display("content/edit.tpl");
	}
	function edit_s()
	{
		if(empty($_POST["header_title"]) || empty($_POST["title"]))
		{
			$_SESSION["admin"]["admin_content"] = $_POST;
			$this->forward("/admin/content/edit/".$_POST["id"]."/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$aRes = $this->db_results(
			"UPDATE `content` SET"
				." `title` = ".$this->db_quote($_POST["title"], "text")
				.", `content` = ".$this->db_quote($_POST["content"], "text")
				." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->content->edit"
		);
		
		$_SESSION["admin"]["admin_content"] = null;
		
		$this->forward("/admin/content/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete($aParams)
	{
		$aRes = $this->db_results(
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