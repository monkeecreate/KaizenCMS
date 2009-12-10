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
		
		$this->tpl_assign("users", $aUsers);
		$this->tpl_display("users/index.tpl");
	}
	function add()
	{
		$this->tpl_assign("user", $_SESSION["admin"]["admin_users"]);
		$this->tpl_display("users/add.tpl");
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
				." (`username`, `password`, `fname`, `lname`, `created_datetime`, `created_by`, `updated_datetime`, `updated_by`)"
				." VALUES"
				." ("
					.$this->db_quote($_POST["username"], "text")
					.", ".$this->db_quote(md5($_POST["password"]), "text")
					.", ".$this->db_quote($_POST["fname"], "text")
					.", ".$this->db_quote($_POST["lname"], "text")
					.", ".$this->db_quote(time(), "integer")
					.", ".$this->db_quote($_SESSION["admin"]["userid"], "integer")
					.", ".$this->db_quote(time(), "integer")
					.", ".$this->db_quote($_SESSION["admin"]["userid"], "integer")
				.")"
			,"admin->users->add"
		);
		
		$_SESSION["admin"]["admin_users"] = null;
		
		$this->forward("/admin/users/?notice=".urlencode("User add successfully!"));
	}
	function edit($aParams)
	{
		if(!empty($_SESSION["admin"]["admin_users"]))
		{
			$aUserRow = $this->db_results(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
				,"admin->users->edit"
				,"row"
			);
			
			$aUser = $_SESSION["admin"]["admin_users"];
			
			$aUser["updated_datetime"] = $aUserRow["updated_datetime"];
			$aUser["updated_by"] = $this->db_results(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aUserRow["updated_by"]
				,"admin->users->edit->updated_by"
				,"row"
			);
			
			$this->tpl_assign("user", $aUser);
		}
		else
		{
			$aUser = $this->db_results(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
					." LIMIT 1"
				,"admin->users->edit"
				,"row"
			);
			
			$aUser["updated_by"] = $this->db_results(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aUser["updated_by"]
				,"admin->users->edit->updated_by"
				,"row"
			);
		
			if(empty($aUser))
				$this->error();
		
			$this->tpl_assign("user", $aUser);
		}
		
		$this->tpl_display("users/edit.tpl");
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
				." `username` = ".$this->db_quote($_POST["username"], "text")
				.", `fname` = ".$this->db_quote($_POST["fname"], "text")
				.", `lname` = ".$this->db_quote($_POST["lname"], "text")
				.", `updated_datetime` = ".$this->db_quote(time(), "integer")
				.", `updated_by` = ".$this->db_quote($_SESSION["admin"]["userid"], "integer")
				." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
			,"admin->users->edit"
		);
		
		if(!empty($_POST["password"]))
		{
			$aRes = $this->db_results(
				"UPDATE `users` SET"
					." `password` = ".$this->db_quote(md5($_POST["password"]), "text")
					." WHERE `id` = ".$this->db_quote($_POST["id"], "integer")
				,"admin->users->edit_password"
			);
		}
		
		$_SESSION["admin"]["admin_users"] = null;
		
		$this->forward("/admin/users/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete($aParams)
	{
		$aRes = $this->db_results(
			"DELETE FROM `users`"
				." WHERE `id` = ".$this->db_quote($aParams["id"], "integer")
			,"admin->users->delete"
		);
		
		$this->forward("/admin/users/?notice=".urlencode("User removed successfully!"));
	}
	##################################
	
	### Functions ####################
	##################################
}