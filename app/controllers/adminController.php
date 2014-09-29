<?php
class adminController extends appController {
	private $_menu;
	public $superAdmin;

	function __construct($sModel = null) {
		parent::__construct($sModel);

		$aPageMessages = array();
		if(is_writable("../inc_config.php"))
			$aPageMessages[] = array("type" => "error", "text" => "Config file is still writable. This poses a security risk.", "close" => false);

		if(!empty($_GET["error"]))
			$aPageMessages[] = array("type" => "error", "text" => htmlentities(urldecode($_GET["error"])), "close" => false);

		if(!empty($_GET["info"]))
			$aPageMessages[] = array("type" => "info", "text" => htmlentities(urldecode($_GET["info"])), "close" => true);

		if(!empty($_GET["warning"]))
			$aPageMessages[] = array("type" => "warning", "text" => htmlentities(urldecode($_GET["warning"])), "close" => true);

		if(!empty($_GET["success"]))
			$aPageMessages[] = array("type" => "success", "text" => htmlentities(urldecode($_GET["success"])), "close" => true);

		$this->tplAssign("aPageMessages", $aPageMessages);

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
			$this->tplAssign("aAccount", $aUser);

			/*## Super Admin ##*/
			if($aUser["super"] == 1)
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
				foreach($aMenuAdmin as $aMenu) {
					$aInfo = json_decode($aMenu["info"], true);
					$aInfo["title"] = htmlspecialchars(stripslashes($aInfo["title"]));
					$this->_menu[$aMenu["tag"]] = $aInfo;
				}

				if(empty($aMenuAdmin))
					$this->forward("/admin/logout/");

				$this->tplAssign("aAdminFullMenu", $this->_menu);
			}
			/*## @end ##*/

			$this->tplAssign("randnum", rand(1000,9999));
		}
	}
	### DISPLAY ######################
	function index() {
		if(!$this->loggedin())
			$this->tplDisplay("login.php");
		else
			$this->tplDisplay("index.php");
	}
	function login() {
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
				$aHeaders["Subject"] = $this->getSetting("site-title")." - Password Reset";

				$sBody = "Someone has requested a password reset from http://".$_SERVER["SERVER_NAME"]."/. If this was not you, ignore this message. If you requested the password reset, follow the link below to continue.\n\n";
				$sBody .= "Username: ".$aUser["username"]."\n\n";
				$sBody .= "http://".$_SERVER["SERVER_NAME"]."/admin/passwordReset/".$code."/";

				$this->mail($aHeaders, $sBody);

				$this->forward("/admin/?info=".urlencode("Check your email for details to reset your password."));
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
		$this->tplDisplay("passwordReset.php");
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
				,"last_password" => time()
			),
			$this->settings->encryptSalt."_".$this->urlVars->dynamic["code"], "resetCode"
		);

		$this->forward("/admin/?success=".urlencode("Password successfully reset."));
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

	/**
	 * Scan content for template files and parse info.
	 * @param  boolean $sRestricted when true return all templates.
	 * @return array                Returns array of template info.
	 */
	function get_templates($sRestricted = false) {
		$all_headers = array(
			"Name" =>  "Name",
			"Description" => "Description",
			"Version" => "Version",
			"Restricted" => "Restricted",
			"Author" => "Author"
		);

		$aData = array();
		$template_dir = $this->settings->root."views/templates/";
		$template_files = scandir($template_dir);
		foreach($template_files as $file) {
			if ($file === "." or $file === "..") continue;

			$fp = fopen($this->settings->root."views/templates/".$file, "r");
			$file_data = fread($fp, 8192);
			fclose($fp);

			foreach($all_headers as $field => $regex) {
				preg_match("/^[ \t\/*#@]*".preg_quote($regex, "/").":(.*)$/mi", $file_data, ${$field});

				if(!empty(${$field}))
					${$field} = trim(preg_replace("/\s*(?:\*\/|\?>).*/", '', ${$field}[1]));
				else
					${$field} = '';
			}

			$aTemplateInfo = compact(array_keys($all_headers));
			$aTemplateInfo["file"] = $file;

			if($aTemplateInfo["Restricted"] === "false" || $sRestricted) {
				$aData[] = $aTemplateInfo;
			}
		}

		return $aData;
	}
	##################################
}
