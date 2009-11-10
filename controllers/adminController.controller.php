<?php
class adminController extends appController
{
	function adminController()
	{
		parent::appController();
		
		$this->tpl_variable_set("template_dir", $this->tpl_variable_get("template_dir")."/admin");
		$this->tpl_variable_set("compile_dir", $this->tpl_variable_get("compile_dir")."/admin");
		
		if(!is_dir($this->tpl_variable_get("template_dir")))
		{
			if(!mkdir($this->tpl_variable_get("template_dir"), 0777))
				die("Please create `".$this->tpl_variable_get("template_dir")."`. Unable to create automatically.");
		}
		
		if(!empty($_GET["error"]))
			$this->tpl_assign("page_error", htmlentities(urldecode($_GET["error"])));
			
		if(!empty($_GET["notice"]))
			$this->tpl_assign("page_notice", htmlentities(urldecode($_GET["notice"])));
		
		if(empty($_SESSION["admin"]["userid"]) && $this->_settings->url[1] != "login" && $this->_settings->surl != "/admin/")
			$this->forward("/admin/", 401);
		elseif(!empty($_SESSION["admin"]["userid"]))
		{
			$aUser = $this->db_results(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$this->db_quote($_SESSION["admin"]["userid"], "text")
					." LIMIT 1"
				,"admin->user_detail"
				,"row"
			);
			
			$this->tpl_assign("loggedin", 1);
			$this->tpl_assign("user_details", $aUser);
		}
	}
	### DISPLAY ######################
	function index()
	{
		if(empty($_SESSION["admin"]["userid"]))
			$this->tpl_display("login.tpl");
		else
			$this->tpl_display("index.tpl");
	}
	function login()
	{
		if(!empty($_POST["username"]) && !empty($_POST["password"]))
		{
			$sUser = $this->db_results(
				"SELECT `id` FROM `users`"
					." WHERE `username` = ".$this->db_quote($_POST["username"], "text")
					." AND `password` = ".$this->db_quote(md5($_POST["password"]), "text")
					." LIMIT 1"
				,"admin->login"
				,"one"
			);
			
			if(!empty($sUser))
			{
				$_SESSION["admin"]["userid"] = $sUser;
				
				$this->forward("/admin/");
			}
			else
				$this->forward("/admin/?error=".urlencode("Username or password was incorrect!"));
		}
		else
			$this->forward("/admin/?error=".urlencode("Please provide both a username and password!"));
	}
	function logout()
	{
		$_SESSION["admin"] = array();
		
		$this->forward("/admin/");
	}
	##################################
	
	### Functions ####################
	function get_extension($sFilename)
	{
		
	}
	// Clears out temp upload directory (added items), default time when item expires is 2 hours
	function clear_tmp($sDir, $sTime = 7200)
	{
		
	}
	##################################
}