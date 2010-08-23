<?php
class adminController extends appController
{
	private $_menu;
	public $superAdmin;
	
	function __construct($sModel = null) {
		parent::__construct($sModel);
		
		if(!empty($_GET["error"]))
			$this->tplAssign("page_error", htmlentities(urldecode($_GET["error"])));
			
		if(!empty($_GET["notice"]))
			$this->tplAssign("page_notice", htmlentities(urldecode($_GET["notice"])));
			
		$aAllowedActions = array(
			"login"
			,"passwordReset"
			,"passwordReset_code"
			,"passwordReset_code_s"
		);
		
		if(!$this->loggedin() && !in_array($this->settings->url[1], $aAllowedActions) && $this->settings->surl != "/admin/")
			$this->forward("/admin/", 401);
		elseif($this->loggedin()) {
			$aUser = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$this->dbQuote($_SESSION["admin"]["userid"], "text")
					." LIMIT 1"
				,"row"
			);
			
			$this->tplAssign("loggedin", 1);
			$this->tplAssign("user_details", $aUser);
			
			/*## Security Check ##*/
			if(is_writable("../inc_config.php")) {
				$this->tplAssign("sSecurityError", "Config file is still writable.");
			}
			/*## End ##*/
			
			/*## Super Admin ##*/
			if($aUser["id"] == 1)
				$this->superAdmin = true;
			else
				$this->superAdmin = false;
			
			$this->tplAssign("sSuperAdmin", $this->superAdmin);
			/*## @end ##*/
			
			/*## Menu ##*/
			if($this->settings->url[1] != "logout") {
				$aMenuAdmin = $this->dbQuery(
					"SELECT * FROM `{dbPrefix}menu_admin`"
						." ORDER BY `sort_order`"
					,"all"
				);
				
				if(!$this->superAdmin) {
					foreach($aMenuAdmin as $x => $aMenu) {
						$aMenuItem = $this->dbQuery(
							"SELECT * FROM `{dbPrefix}users_privileges`"
								." WHERE `userid` = ".$aUser["id"]
								." AND `menu` = ".$this->dbQuote($aMenu[tag], "text")
							,"row"
						);
					
						if(empty($aMenuItem))
							unset($aMenuAdmin[$x]);
					}
				}
				
				$this->_menu = array();
				$aMainMenu = array();
				$aSubMenu = array();
				$menuLength = 0;
				foreach($aMenuAdmin as $aMenu) {
					$aInfo = json_decode($aMenu["info"], true);
					
					$menuLength = $menuLength + strlen($aInfo["title"]);
					$this->_menu[$aMenu["tag"]] = $aInfo;
					
					if($menuLength > 60)
						$aSubMenu[$aMenu["tag"]] = $aInfo;
					else
						$aMainMenu[$aMenu["tag"]] = $aInfo;
				}
			
				if(empty($aMenuAdmin))
					$this->forward("/admin/logout/");
				
				$this->tplAssign("aAdminFullMenu", $this->_menu);
				$this->tplAssign("aAdminMainMenu", $aMainMenu);
				$this->tplAssign("aAdminSubMenu", $aSubMenu);
			}
			/*## @end ##*/
			
			$this->tplAssign("randnum", rand(1000,9999));
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
		if(!empty($_POST["username"]) && !empty($_POST["password"])) {
			$sUser = $this->dbQuery(
				"SELECT `id` FROM `{dbPrefix}users`"
					." WHERE `username` = ".$this->dbQuote($_POST["username"], "text")
					." AND `password` = ".$this->dbQuote(sha1($_POST["password"]), "text")
					." LIMIT 1"
				,"one"
			);
			
			if(!empty($sUser)) {
				session_regenerate_id();
				$_SESSION["admin"]["userid"] = $sUser;
				
				$this->dbUpdate(
					"users",
					array(
						"last_login" => time()
					),
					$sUser
				);
				
				$this->forward("/admin/");
			} else
				$this->forward("/admin/?error=".urlencode("Username or password was incorrect!"));
		} else
			$this->forward("/admin/?error=".urlencode("Please provide both a username and password!"));
	}
	function logout() {
		$_SESSION["admin"] = array();
		
		$this->forward("/admin/");
	}
	function passwordReset() {
		if(!empty($_POST["email"])) {
			$aUser = $this->dbQuery("SELECT * FROM `{dbPrefix}users`"
				." WHERE `email_address` = ".$this->dbQuote($_POST["email"], "text")
				,"row"
			);
			
			if(!empty($aUser)) {
				$code = sha1($aUser["email"].time());
				
				$this->dbUpdate(
					"users",
					array(
						"resetCode" => $this->settings->encryptSalt."_".$code
					),
					$aUser["id"]
				);
				
				$aHeaders["To"] = $aUser["email_address"];
				$aHeaders["From"] = $aUser["email_address"];
				$aHeaders["Subject"] = $this->getSetting("title")." - Password Reset";
				
				$sBody = "Someone has requested a password reset from http://".$_SERVER["SERVER_NAME"]."/. If this was not you, ignore this message. If you requested the password reset, follow the link below to continue.\n\n";
				$sBody .= "Username: ".$aUser["username"]."\n\n";
				$sBody .= "http://".$_SERVER["SERVER_NAME"]."/admin/passwordReset/".$code."/";
				
				$this->mail($aHeaders, $sBody);
				
				$this->forward("/admin/?notice=".urlencode("Check your email for details to reset your password."));
			}
			$this->forward("/admin/?error=".urlencode("We could not find an account with that email address."));			
		}
		$this->forward("/admin/?error=".urlencode("Please enter a valid email address."));
	}
	function passwordReset_code() {
		$aUser = $this->dbQuery("SELECT * FROM `{dbPrefix}users`"
			." WHERE `resetCode` = ".$this->dbQuote($this->settings->encryptSalt."_".$this->urlVars->dynamic["code"], "text")
			,"row"
		);
		
		if(empty($aUser))
			$this->forward("/admin/");
		
		$this->tplAssign("sCode", $this->urlVars->dynamic["code"]);
		$this->tplDisplay("passwordReset.tpl");
	}
	function passwordReset_code_s() {
		if(empty($_POST["password"]))
			$this->forward("/admin/passwordReset/".$this->urlVars->dynamic["code"]."/?error=".urlencode("Password can not be empty."));
		
		if($_POST["password"] != $_POST["password2"] || empty($_POST["password"]))
			$this->forward("/admin/passwordReset/".$this->urlVars->dynamic["code"]."/?error=".urlencode("Passwords did not match. Please enter your password twice."));
		
		$this->dbUpdate(
			"users",
			array(
				"password" => sha1($_POST["password"])
			),
			$this->settings->encryptSalt."_".$this->urlVars->dynamic["code"], "resetCode"
		);
		
		$this->forward("/admin/?notice=".urlencode("Password successfully reset."));
	}
	function isloggedin() {
		$secretKey = md5($_SERVER["SERVER_NAME"]);
		$config = array();
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
	function loggedin() {
		if(!empty($_SESSION["admin"]["userid"])) {
			$aUser = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}users`"
					." WHERE `id` = ".$this->dbQuote($_SESSION["admin"]["userid"], "integer")
				,"row"
			);
			
			if(!empty($aUser))
				return true;
			else
				return false;
		} else
			return false;
	}
	function menuPermission($section) {
		if(!array_key_exists($section, $this->_menu))
			$this->error("403");
		else
			return true;
	}
	function boolCheck($value) {
		if($value == 1)
			return 1;
		else
			return 0;
	}
	##################################
}