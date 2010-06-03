<?php
class admin_users extends adminController
{
	function __construct() {
		parent::__construct();
		
		$this->menuPermission("users");
	}
	
	### DISPLAY ######################
	function index() {
		// Clear saved form info
		$_SESSION["admin"]["admin_users"] = null;
		
		if($_SESSION["admin"]["userid"] != 1) {
			$sWhere = " WHERE `id` != ".$_SESSION["admin"]["userid"];
			$sWhere .= " AND `id` != 1";
		}
		
		$aUsers = $this->dbResults(
			"SELECT * FROM `users`"
				.$sWhere
				." ORDER BY `lname`"
			,"all"
		);
		
		$this->tplAssign("users", $aUsers);
		$this->tplDisplay("users/index.tpl");
	}
	function add() {
		if(!empty($_SESSION["admin"]["admin_users"]))
			$this->tplAssign("aUser", $_SESSION["admin"]["admin_users"]);
		else
			$this->tplAssign("aUser",
				array(
					"privlages" => array()
				)
			);
		
		$this->tplDisplay("users/add.tpl");
	}
	function add_s() {
		if(empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["email_address"])) {
			$_SESSION["admin"]["admin_users"] = $_POST;
			$this->forward("/admin/users/add/?error=".urlencode("Please fill in all required fields!"));
		}
		
		if(!filter_var($_POST["email_address"], FILTER_VALIDATE_EMAIL)) {
			$_SESSION["admin"]["admin_users"] = $_POST;
			$this->forward("/admin/users/add/?error=".urlencode("Please enter a valid email address."));
		}
		
		$sID = $this->dbResults(
			"INSERT INTO `users`"
				." (`username`, `password`, `fname`, `lname`, `email_address`, `created_datetime`, `created_by`, `updated_datetime`, `updated_by`)"
				." VALUES"
				." ("
					.$this->dbQuote($_POST["username"], "text")
					.", ".$this->dbQuote(md5($_POST["password"]), "text")
					.", ".$this->dbQuote($_POST["fname"], "text")
					.", ".$this->dbQuote($_POST["lname"], "text")
					.", ".$this->dbQuote($_POST["email_address"], "text")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
					.", ".$this->dbQuote(time(), "integer")
					.", ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				.")"
			,"insert"
		);
		
		if(!empty($_POST["privlages"])) {
			foreach($_POST["privlages"] as $sPrivlage) {
				$this->dbResults(
					"INSERT INTO `users_privlages`"
						." (`userid`, `menu`)"
						." VALUES"
						." (".
						$this->dbQuote($sID, "integer")
						.", ".$this->dbQuote($sPrivlage, "text").")"
				);
			}
		}
		
		$_SESSION["admin"]["admin_users"] = null;
		
		$this->forward("/admin/users/?notice=".urlencode("User add successfully!"));
	}
	function edit() {
		if(!empty($_SESSION["admin"]["admin_users"])) {
			$aUserRow = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"admin->users->edit"
				,"row"
			);
			
			$aUser = $_SESSION["admin"]["admin_users"];
			
			$aUser["updated_datetime"] = $aUserRow["updated_datetime"];
			$aUser["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aUserRow["updated_by"]
				,"row"
			);
		} else {
			$aUser = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
					." LIMIT 1"
				,"row"
			);
			
			$aUser["privlages"] = $this->dbResults(
				"SELECT `menu` FROM `users_privlages`"
					." WHERE `userid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"col"
			);
			
			$aUser["updated_by"] = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aUser["updated_by"]
				,"row"
			);
		
			if(empty($aUser))
				$this->error();
		}
		
		$this->tplAssign("aUser", $aUser);
		$this->tplDisplay("users/edit.tpl");
	}
	function edit_s() {
		if(empty($_POST["username"]) || empty($_POST["email_address"])) {
			$_SESSION["admin"]["admin_users"] = $_POST;
			$this->forward("/admin/users/edit/?error=".urlencode("Please fill in all required fields!"));
		}
		
		if(!filter_var($_POST["email_address"], FILTER_VALIDATE_EMAIL)) {
			$_SESSION["admin"]["admin_users"] = $_POST;
			$this->forward("/admin/users/edit/?error=".urlencode("Please enter a valid email address."));
		}
		
		$aRes = $this->dbResults(
			"UPDATE `users` SET"
				." `username` = ".$this->dbQuote($_POST["username"], "text")
				.", `fname` = ".$this->dbQuote($_POST["fname"], "text")
				.", `lname` = ".$this->dbQuote($_POST["lname"], "text")
				.", `email_address` = ".$this->dbQuote($_POST["email_address"], "text")
				.", `updated_datetime` = ".$this->dbQuote(time(), "integer")
				.", `updated_by` = ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
			,"update"
		);
		
		$this->dbResults(
			"DELETE FROM `users_privlages`"
				." WHERE `userid` = ".$this->dbQuote($_POST["id"], "integer")
		);
		if(!empty($_POST["privlages"])) {
			foreach($_POST["privlages"] as $sPrivlage) {
				$this->dbResults(
					"INSERT INTO `users_privlages`"
						." (`userid`, `menu`)"
						." VALUES"
						." (".
						$this->dbQuote($_POST["id"], "integer")
						.", ".$this->dbQuote($sPrivlage, "text").")"
				);
			}
		}
		
		if(!empty($_POST["password"])) {
			$aRes = $this->dbResults(
				"UPDATE `users` SET"
					." `password` = ".$this->dbQuote(md5($_POST["password"]), "text")
					." WHERE `id` = ".$this->dbQuote($_POST["id"], "integer")
				,"update"
			);
		}
		
		$_SESSION["admin"]["admin_users"] = null;
		
		$this->forward("/admin/users/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete() {
		$aRes = $this->dbResults(
			"DELETE FROM `users`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"delete"
		);
		
		$this->forward("/admin/users/?notice=".urlencode("User removed successfully!"));
	}
	##################################
	
	### Functions ####################
	##################################
}