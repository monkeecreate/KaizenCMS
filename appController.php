<?php
class appController
{
	private $_db;
	private $_memcache;
	private $_mail;
	private $_smarty;
	private $_firephp;
	private $_enc;
	public $_settings;
	public $_urlVars;
	public $model;
	
	function __construct($sModel = null) {
		global $objDB, $oMemcache, $objMail, $oFirePHP, $oEnc, $oSmarty, $site_public_root, $site_root, $aConfig, $sURL, $aUrl, $aURLVars;
		
		$this->_db = $objDB;
		$this->_memcache = $oMemcache;
		$this->_mail = $objMail;
		$this->_firephp = $oFirePHP;
		$this->_enc = $oEnc;
		$this->_smarty = $oSmarty;
		$this->_settings = (object) array(
			"root" => $site_root
			,"rootPublic" => $site_public_root
			,"adminInfo" => $aConfig["admin_info"]
			,"debug" => $aConfig["options"]["debug"]
			,"surl" => $sURL
			,"url" => $aUrl
			,"memcacheSalt" => $aConfig["memcache"]["salt"]
			,"encryptSalt" => $aConfig["encryption"]["salt"]
		);
		$this->_urlVars = $aURLVars;
		
		if(!empty($sModel)) {
			$this->model = $this->loadModel($sModel);
		}
	}
	
	### Functions ####################
	function forward($url, $type = "") {
		switch($type) {
			case "403":
				header('HTTP/1.1 403 Forbidden');
				break;
			case "404":
				header("HTTP/1.1 404 Not Found");
				break;
			case "500":
				header("HTTP/1.1 500 Internal Server Error");
				break;
		}

		header("Location: ".$url);
		exit;
	}
	function siteInfo() {
		echo "<pre>";
		print_r($this->_settings);
		print_r($this->_db);
		print_r($this->_smarty);
		echo "</pre>";
		
		phpinfo();
	}
	function loadController($sController) {
		if(!class_exists($sController)) {
			$sFile = str_replace("_","/", $sController);
			
			if(substr($sFile, -1) == "/")
				$sFile = substr($sFile, 0, -1);
			
			if(is_file($this->_settings->root."controllers/".$sFile.".php"))
				require($this->_settings->root."controllers/".$sFile.".php");
			else
				return false;
		}
		
		$oController = new $sController;
		
		return $oController;
	}
	function loadModel($sModel) {
		if(!class_exists("appModel"))
			require($this->_settings->root."appModel.php");
		
		if(!class_exists($sModel."_model")) {
			if(is_file($this->_settings->root."models/".$sModel.".php"))
				require($this->_settings->root."models/".$sModel.".php");
			else
				return false;
		}
		$sModel = $sModel."_model";
		
		$sModel = new $sModel;
		
		return $sModel;
	}
	function getSetting($sTag) {
		if(empty($sTag))
			$this->error("getSetting", "Setting tag not passed", null, debug_backtrace());
		
		$aSetting = $this->dbResults(
			"SELECT * FROM `settings`"
				." WHERE `tag` = ".$this->dbQuote($sTag, "text")
			,"row"
		);
		
		if(empty($aSetting))
			$this->error("getSetting", "Could not find setting", null, debug_backtrace());
		
		if(!class_exists("Form"))
			include($this->_settings->root."helpers/Form.php");
		
		$oField = new Form($aSetting);
		
		return $oField->setting->value();
	}
	function getUser($sId) {
		if(empty($sId))
			$this->error("getUser", "User id missing", null, debug_backtrace());
			
		$aUser = $this->dbResults(
			"SELECT * FROM `users`"
				." WHERE `id` = ".$this->dbQuote($sId, "integer")
			,"row"
		);
		
		if(empty($aUser))
			$this->error("getUser", "Could not find user", null, debug_backtrace());
			
		return $aUser;
	}
	##################################
	
	### Database #####################
	function dbResults($sSQL, $return = null) {
		$oResult = $this->_db->query($sSQL);
		
		if(PEAR::isError($oResult))
			$this->sendError("dbResults", "dberror", $oResult, debug_backtrace());
			
		switch($return) {
			case "all":
				$aReturn = $oResult->fetchAll();
				break;
			case "row":
				$aReturn = $oResult->fetchRow();
				break;
			case "one":
				$aReturn = $oResult->fetchOne();
				break;
			case "col":
				$aReturn = $oResult->fetchCol();
				break;
			case "rows":
				$aReturn = $oResult->numRows();
				break;
			case "insert":
				$aReturn = $this->_db->lastInsertID();
				break;
			default:
				$aReturn = true;
		}
		
		$oResult->free();
		
		return $aReturn;
	}
	function dbQuote($sValue, $sType) {
		$sReturn = $this->_db->quote($sValue, $sType);
		
		if(PEAR::isError($sReturn))
			$this->sendError("dbQuote", $sReturn->userinfo, null, debug_backtrace());
		
		return $sReturn;
	}
	##################################
	
	### Template #####################
	function tplExists($template_file) {
		$template_file = $this->_smarty->template_dir."/".$template_file;
		
		return is_file($template_file);
	}
	function tplAssign($sVariable, $sValue) {
		$this->_smarty->assign($sVariable, $sValue);
	}
	function tplDisplay($sTemplate) {
		if($this->tplExists($sTemplate))
			$this->_smarty->display($sTemplate);
		else
			$this->sendError("appController->tplDisplay", "Can't find template - (".$sTemplate.")");
	}
	function tplVariableGet($sVariable) {
		return $this->_smarty->$sVariable;
	}
	function tplVariableSet($sVariable, $sValue) {
		$this->_smarty->$sVariable = $sValue;
	}
	###################################
	
	### Mail ##########################
	function mail($sRecipients, $aHeaders, $bodyText, $bodyHTML = null, $aAttachment = array()) {
		include("Mail/mime.php");
		$oMime = new Mail_mime("\n");
		
		// Set text for message body
		$oMime->setTXTBody($bodyText);
		
		// Set HTML message for body
		if(!empty($bodyHTML))
			$oMime->setHTMLBody($bodyHTML);
		
		// Add attachments to message
		foreach($aAttachment as $aFile)
			$oMime->addAttachment($aFile[0], $aFile[1]);
			
		$sBody = $oMime->get();
		$aHeaders = $oMime->headers($aHeaders);
		
		// Send message
		$oMail = $this->_mail->send($sRecipients, $aHeaders, $sBody);
		
		if(PEAR::iserror($oMail))
			$this->error("Mail - ".$aHeaders["Subject"], $oMail->message);
		else {
			unset($oMime, $sBody, $sHeaders, $oMail);
			return true;
		}
	}
	###################################
	
	### Encryption ####################
	function encrypt($text) {
		return $this->_enc->encrypt($text);
	}
	function decrypt($text) {
		return $this->_enc->decrypt($text);
	}
	##################################
	
	### Memcache #####################
	function memcacheGet($key) {
		$value = $this->_memcache->get(md5($this->_settings->memcacheSalt.$key));
		
		if($value != false)
			return $this->decrypt($value);
		else
			return false;
	}
	function memcacheSet($key, $value, $expire = 0) {
		return $this->_memcache->set(md5($this->_settings->memcacheSalt.$key), $this->encrypt($value), false, $expire);
	}
	##################################

	### Errors #######################
	function log($log) {
		$this->_fireftp->log($log);
	}
	function error($error = "404") {
		switch($error) {
			case "403":
				header('HTTP/1.1 403 Forbidden');
				$this->tplDisplay("error/403.tpl");
				break;
			case "404":
				header("HTTP/1.1 404 Not Found");
				$this->tplDisplay("error/404.tpl");
				break;
			case "500":
				header("HTTP/1.1 500 Internal Server Error");
				$this->tplDisplay("error/500.tpl");
				break;
		}
		exit;
	}
	protected function sendError($section, $error, $db = null, $aTrace = array()) {
		if(empty($aTrace))
			$aTrace = debug_backtrace();
		
		$recipients = $this->_settings->adminInfo["email"];
		$headers["To"] = $this->_settings->adminInfo["email"];
		$headers["From"] = $this->_settings->adminInfo["email"];
		$headers["Subject"] = "Website Error - ".$section;
		
		$body = "Where: ".$section."\n";
		if(!empty($db)) {
			$aUserInfo = preg_split('/\] \[/',str_replace(array("_doQuery: [", "]\n[", "]\n"),array(null, "] [", null),$db->userinfo));
			$aMessage = preg_split('/: /',$aUserInfo[3]);
			$body .= "Error: ".$db->message."\n";
			$body .= $aMessage[1]."\n";
			$body .= "Query: ".$this->_db->last_query."\n";
		} else
			$body .= "Error: ".$error."\n";
		
		$body .= "File: ".$aTrace[0]["file"]."\n";
		$body .= "Line: ".$aTrace[0]["line"]."\n";
		$body .= "User Agent: ".$_SERVER["HTTP_USER_AGENT"]."\n";
		$body .= "Referer: ".$_SERVER["HTTP_REFERER"]."\n";
		$body .= "Domain: ".$_SERVER["HTTP_HOST"]."\n";
		$body .= "URL: ".$_SERVER["REQUEST_URI"]."\n";
		$body .= "Time: ".date("M j,Y - h:i:s a")."\n";
		
		if($this->_settings->debug == true)
			die(str_replace("\n","<br />",$body));
		else
			$this->mail($recipients, $headers, $body);
		
		$this->error("500");
	}
	##################################
}