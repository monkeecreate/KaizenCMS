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
		
		$sWhere = " WHERE `id` > 0";// Allways true
		if($_SESSION["admin"]["userid"] != 1) {
			$sWhere .= " AND `id` != 1";
		}
		
		$aUsers = $this->dbQuery(
			"SELECT * FROM `users`"
				.$sWhere
				." ORDER BY `lname`"
			,"all"
		);
		
		$this->tplAssign("aUsers", $aUsers);
		$this->tplDisplay("users/index.tpl");
	}
	function add() {
		if(!empty($_SESSION["admin"]["admin_users"]))
			$this->tplAssign("aUser", $_SESSION["admin"]["admin_users"]);
		else
			$this->tplAssign("aUser",
				array(
					"privileges" => array()
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
		
		$sID = $this->dbQuery(
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
		
		if(!empty($_POST["privileges"])) {
			foreach($_POST["privileges"] as $sPrivilege) {
				$this->dbQuery(
					"INSERT INTO `users_privileges`"
						." (`userid`, `menu`)"
						." VALUES"
						." (".
						$this->dbQuote($sID, "integer")
						.", ".$this->dbQuote($sPrivilege, "text").")"
				);
			}
		}
		
		$_SESSION["admin"]["admin_users"] = null;
		
		$this->forward("/admin/users/?notice=".urlencode("User add successfully!"));
	}
	function edit() {
		if(!empty($_SESSION["admin"]["admin_users"])) {
			$aUserRow = $this->dbQuery(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"admin->users->edit"
				,"row"
			);
			
			$aUser = $_SESSION["admin"]["admin_users"];
			
			$aUser["updated_datetime"] = $aUserRow["updated_datetime"];
			$aUser["updated_by"] = $this->dbQuery(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$aUserRow["updated_by"]
				,"row"
			);
		} else {
			$aUser = $this->dbQuery(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
					." LIMIT 1"
				,"row"
			);
			
<<<<<<< HEAD
			$aUser["privileges"] = $this->dbResults(
				"SELECT `menu` FROM `users_privileges`"
=======
			$aUser["privlages"] = $this->dbQuery(
				"SELECT `menu` FROM `users_privlages`"
>>>>>>> plugin
					." WHERE `userid` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
				,"col"
			);
			
			$aUser["updated_by"] = $this->dbQuery(
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
		
		$aRes = $this->dbQuery(
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
		
<<<<<<< HEAD
		$this->dbResults(
			"DELETE FROM `users_privileges`"
				." WHERE `userid` = ".$this->dbQuote($_POST["id"], "integer")
		);
		if(!empty($_POST["privileges"])) {
			foreach($_POST["privileges"] as $sPrivilege) {
				$this->dbResults(
					"INSERT INTO `users_privileges`"
=======
		$this->dbQuery(
			"DELETE FROM `users_privlages`"
				." WHERE `userid` = ".$this->dbQuote($_POST["id"], "integer")
		);
		if(!empty($_POST["privlages"])) {
			foreach($_POST["privlages"] as $sPrivlage) {
				$this->dbQuery(
					"INSERT INTO `users_privlages`"
>>>>>>> plugin
						." (`userid`, `menu`)"
						." VALUES"
						." (".
						$this->dbQuote($_POST["id"], "integer")
						.", ".$this->dbQuote($sPrivilege, "text").")"
				);
			}
		}
		
		if(!empty($_POST["password"])) {
			$aRes = $this->dbQuery(
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
<<<<<<< HEAD
		if($_SESSION["admin"]["userid"] == $this->_urlVars->dynamic["id"])
			$this->forward("/admin/users/?error=".urlencode("You are not allowed to delete yourself."));
		
		$aRes = $this->dbResults(
=======
		$aRes = $this->dbQuery(
>>>>>>> plugin
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