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
		
		$sID = $this->dbInsert(
			"users",
			array(
				"username" => $_POST["username"]
				,"password" => md5($_POST["password"])
				,"fname" => $_POST["fname"]
				,"lname" => $_POST["lname"]
				,"email_address" => $_POST["email_address"]
				,"created_datetime" => time()
				,"created_by" => $_SESSION["admin"]["userid"]
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			)
		);
		
		if(!empty($_POST["privileges"])) {
			foreach($_POST["privileges"] as $sPrivilege) {
				$this->dbInsert(
					"users_privileges",
					array(
						"userid" => $sID
						,"menu" => $sPrivilege
					)
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
			
			$aUser["privileges"] = $this->dbQuery(
				"SELECT `menu` FROM `users_privileges`"
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
		
		$aRes = $this->dbUpdate(
			"users",
			array(
				"username" => $_POST["username"]
				,"fname" => $_POST["fname"]
				,"lname" => $_POST["lname"]
				,"email_address" => $_POST["email_address"]
				,"updated_datetime" => time()
				,"updated_by" => $_SESSION["admin"]["userid"]
			),
			$_POST["id"]
		);
		
		$this->dbDelete("users_privileges", $_POST["id"], "userid");
		if(!empty($_POST["privileges"])) {
			foreach($_POST["privileges"] as $sPrivilege) {
				$this->dbInsert(
					"users_privileges",
					array(
						"userid" => $_POST["id"]
						,"menu" => $sPrivilege
					)
				);
			}
		}
		
		if(!empty($_POST["password"])) {
			$this->dbUpdate(
				"users",
				array(
					"password" => md5($_POST["password"])
				),
				$_POST["id"]
			);
		}
		
		$_SESSION["admin"]["admin_users"] = null;
		
		$this->forward("/admin/users/?notice=".urlencode("Changes saved successfully!"));
	}
	function delete() {
		if($_SESSION["admin"]["userid"] == $this->_urlVars->dynamic["id"])
			$this->forward("/admin/users/?error=".urlencode("You are not allowed to delete yourself."));
		
		$this->dbDelete("users", $this->_urlVars->dynamic["id"]);
		$this->dbDelete("users_privileges", $this->_urlVars->dynamic["id"], "userid");
		
		$this->forward("/admin/users/?notice=".urlencode("User removed successfully!"));
	}
	##################################
	
	### Functions ####################
	##################################
}