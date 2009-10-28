<?php
class admin_users extends adminController
{
	### DISPLAY ######################
	function index()
	{
		// Clear saved form info
		$_SESSION["admin"]["admin_users"] = null;
		
		$aUsers = $this->db_results(
			"SELECT * FROM `users`"
				." ORDER BY `lname`"
			,"admin->users->index"
			,"all"
		);
		
		$this->_smarty->assign("users", $aUsers);
		$this->_smarty->display("users/index.tpl");
	}
	function add()
	{
		$this->_smarty->assign("user", $_SESSION["admin"]["admin_users"]);
		$this->_smarty->display("users/add.tpl");
	}
	function add_s()
	{
		if(empty($_POST["username"]) || empty($_POST["password"]))
		{
			$_SESSION["admin"]["admin_users"] = $_POST;
			$this->forward("/admin/users/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$aRes = $this->db_results(
			"INSERT INTO `users`"
				." (`username`, `password`, `fname`, `lname`)"
				." VALUES"
				." ("
					.$this->_db->quote($_POST["username"], "text")
					.", ".$this->_db->quote(md5($_POST["password"]), "text")
					.", ".$this->_db->quote($_POST["fname"], "text")
					.", ".$this->_db->quote($_POST["lname"], "text")
				.")"
			,"admin->users->add"
		);
		
		$_SESSION["admin"]["admin_users"] = null;
		
		$this->forward("/admin/users/?notice=".urlencode("User add successfully!"));
	}
	function edit($aParams)
	{
		if(!empty($_SESSION["admin"]["admin_users"]))
			$this->_smarty->assign("user", $_SESSION["admin"]["admin_users"]);
		else
		{
			$aUser = $this->db_results(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$this->_db->quote($aParams["id"], "integer")
					." LIMIT 1"
				,"admin->users->edit"
				,"row"
			);
		
			if(empty($aUser))
				$this->error();
		
			$this->_smarty->assign("user", $aUser);
		}
		
		$this->_smarty->display("users/edit.tpl");
	}
	function edit_s()
	{
		if(empty($_POST["username"]))
		{
			$_SESSION["admin"]["admin_users"] = $_POST;
			$this->forward("/admin/users/edit/?error=".urlencode("Please fill in all required fields!"));
		}
		
		$aRes = $this->db_results(
			"UPDATE `users` SET"
				." `username` = ".$this->_db->quote($_POST["username"], "text")
				.", `fname` = ".$this->_db->quote($_POST["fname"], "text")
				.", `lname` = ".$this->_db->quote($_POST["lname"], "text")
				." WHERE `id` = ".$this->_db->quote($_POST["id"], "integer")
			,"admin->users->edit"
		);
		
		if(!empty($_POST["password"]))
		{
			$aRes = $this->db_results(
				"UPDATE `users` SET"
					." `password` = ".$this->_db->quote(md5($_POST["password"]), "text")
					." WHERE `id` = ".$this->_db->quote($_POST["id"], "integer")
				,"admin->users->edit_password"
			);
		}
		
		$_SESSION["admin"]["admin_users"] = null;
		
		$this->forward("/admin/users/");
	}
	function delete($aParams)
	{
		$aRes = $this->db_results(
			"DELETE FROM `users`"
				." WHERE `id` = ".$this->_db->quote($aParams["id"], "integer")
			,"admin->users->delete"
		);
		
		$this->forward("/admin/users/");
	}
	##################################
	
	### Functions ####################
	##################################
}