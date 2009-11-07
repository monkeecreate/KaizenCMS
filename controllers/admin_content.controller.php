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
		
		$this->_smarty->assign("aPages", $aPages);
		$this->_smarty->assign("domain", $_SERVER["SERVER_NAME"]);
		$this->_smarty->display("content/index.tpl");
	}
	function add()
	{
		$this->_smarty->assign("aPage", $_SESSION["admin"]["admin_content"]);
		$this->_smarty->display("content/add.tpl");
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
					.$this->_db->quote($sTag, "text")
					.", ".$this->_db->quote($_POST["title"], "text")
					.", ".$this->_db->quote($_POST["content"], "text")
				.")"
			,"admin->content->add"
		);
		
		$_SESSION["admin"]["admin_content"] = null;
		
		$this->forward("/admin/content/?notice=".urlencode("Page created successfully!"));
	}
	function edit($aParams)
	{
		if(!empty($_SESSION["admin"]["admin_content"]))
			$this->_smarty->assign("aPage", $_SESSION["admin"]["admin_content"]);
		else
		{
			$aPage = $this->db_results(
				"SELECT * FROM `content`"
					." WHERE `id` = ".$this->_db->quote($aParams["id"], "integer")
				,"admin->content->edit"
				,"row"
			);
		
			$this->_smarty->assign("aPage", $aPage);
		}
		
		$this->_smarty->display("content/edit.tpl");
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
				." `title` = ".$this->_db->quote($_POST["title"], "text")
				.", `content` = ".$this->_db->quote($_POST["content"], "text")
				." WHERE `id` = ".$this->_db->quote($_POST["id"], "integer")
			,"admin->content->edit"
		);
		
		$_SESSION["admin"]["admin_content"] = null;
		
		$this->forward("/admin/content/");
	}
	function delete($aParams)
	{
		$aRes = $this->db_results(
			"DELETE FROM `content`"
				." WHERE `id` = ".$this->_db->quote($aParams["id"], "integer")
			,"admin->content->delete"
		);
		
		$this->forward("/admin/content/");
	}
	##################################
	
	### Functions ####################
	##################################
}