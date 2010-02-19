<?php
class adminController extends appController
{
	function adminController()
	{
		parent::appController();
		
		if(!empty($_GET["error"]))
			$this->tplAssign("page_error", htmlentities(urldecode($_GET["error"])));
			
		if(!empty($_GET["notice"]))
			$this->tplAssign("page_notice", htmlentities(urldecode($_GET["notice"])));
		
		if(!$this->loggedin() && $this->_settings->url[1] != "login" && $this->_settings->surl != "/admin/")
			$this->forward("/admin/", 401);
		elseif($this->loggedin())
		{
			$aUser = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$this->dbQuote($_SESSION["admin"]["userid"], "text")
					." LIMIT 1"
				,"row"
			);
			
			$this->tplAssign("loggedin", 1);
			$this->tplAssign("user_details", $aUser);
		}
	}
	### DISPLAY ######################
	function index()
	{
		if(!$this->loggedin())
			$this->tplDisplay("login.tpl");
		else
			$this->tplDisplay("index.tpl");
	}
	function login()
	{
		if(!empty($_POST["username"]) && !empty($_POST["password"]))
		{
			$sUser = $this->dbResults(
				"SELECT `id` FROM `users`"
					." WHERE `username` = ".$this->dbQuote($_POST["username"], "text")
					." AND `password` = ".$this->dbQuote(md5($_POST["password"]), "text")
					." LIMIT 1"
				,"one"
			);
			
			if(!empty($sUser))
			{
				session_regenerate_id();
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
	function isloggedin()
	{
		// Change this secret key so it matches the one in the imagemanager/filemanager config
		$secretKey = md5($_SERVER["SERVER_NAME"]);

		// Check here if the user is logged in or not
		/*
		if (!isset($_SESSION["some_session"]))
			die("You are not logged in.");
		*/

		// Override any config values here
		$config = array();
		//$config['filesystem.path'] = 'c:/Inetpub/wwwroot/somepath';
		//$config['filesystem.rootpath'] = 'c:/Inetpub/wwwroot/somepath';

		// Generates a unique key of the config values with the secret key
		$key = md5(implode('', array_values($config)) . $secretKey);

		echo "<html>\n";
		echo "<body onload=\"document.forms[0].submit();\">\n";
		echo "<form method=\"post\" action=\"".htmlentities($_GET['return_url'])."\">\n";
		echo "<input type=\"hidden\" name=\"key\" value=\"".htmlentities($key)."\" />\n";
		foreach ($config as $key => $value) {
			echo '<input type="hidden" name="' . htmlentities(str_replace('.', '__', $key)) . '" value="' . htmlentities($value) . '" />';
		}
		echo "</form>\n";
		echo "</body>\n";
		echo "</html>\n";
	}
	##################################
	
	### Functions ####################
	function loggedin()
	{
		if(!empty($_SESSION["admin"]["userid"]))
		{
			$aUser = $this->dbResults(
				"SELECT * FROM `users`"
					." WHERE `id` = ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				,"row"
			);
			
			if(!empty($aUser))
				return true;
			else
				return false;
		}
		else
			return false;
	}
	##################################
}