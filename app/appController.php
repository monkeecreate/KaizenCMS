<?php
class appController {
	private $_db;
	private $_mail;
	private $_enc;
	private $_plugin;
	public $settings;
	public $urlVars;
	public $model;
	public $assign = array();
	public $component;
	public $outputfilters = array();

	function __construct($sModel = null) {
		global $objDB, $objMail, $oEnc, $aConfig, $sURL, $aUrl, $aURLVars;

		$this->_db = $objDB;
		$this->_mail = $objMail;
		$this->_enc = $oEnc;
		$this->settings = (object) array(
			"root" => APP
			,"rootPublic" => APP_ROOT
			,"adminInfo" => $aConfig["admin_info"]
			,"debug" => $aConfig["options"]["debug"]
			,"dbPrefix" => $aConfig["database"]["prefix"]
			,"surl" => $sURL
			,"url" => $aUrl
			,'tplDir' => $aConfig['views']['dir']
			,"encryptSalt" => $aConfig["encryption"]["salt"]
			,"formatDate" => $aConfig["options"]["formatDate"]
			,"formatTime" => $aConfig["options"]["formatTime"]
		);
		$this->urlVars = $aURLVars;

		$this->cmsVersion = "1.0";
		$this->tplAssign("cmsVersion", $this->cmsVersion);

		if(!empty($sModel)) {
			$this->_plugin = $sModel;
			$this->model = $this->loadModel($sModel);
		}
	}

	### Functions ####################
	function forward($url, $type = "") {
		switch($type) {
			case "301":
				header('HTTP/1.1 301 Moved Permanently');
				break;
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
		print_r($this->settings);
		print_r($this->_db);
		echo "</pre>";

		phpinfo();
	}
	function loadController($sController, $firstCall = false) {
		if(!class_exists($sController)) {
			if(substr($sController, -1) == "_") {
				$sControllerFile = substr($sController, 0, -1);
			} else {
				$sControllerFile = $sController;
			}

			if(is_file($this->settings->root."controllers/".$sControllerFile.".php")) {
				require($this->settings->root."controllers/".$sControllerFile.".php");
			} else {
				$sPlugin = preg_replace('/(?:admin_)([a-z0-9-.]+)(?:_*)(?:.*)$/i', "$1", $sControllerFile);

				if($firstCall == true) {
					$sPluginInstalled = $this->dbQuery("SELECT `plugin` FROM `{dbPrefix}plugins`"
						." WHERE `plugin` = ".$this->dbQuote($sPlugin, "text")
						." LIMIT 1",
						"one"
					);

					if($sPluginInstalled == $sPlugin) {
						$sLoadController = true;
					} else {
						$sLoadController = false;
					}
				} else {
					$sLoadController = true;
				}

				if(is_file($this->settings->root."plugins/".$sPlugin."/controllers/".$sControllerFile.".php") && $sLoadController == true) {
					require($this->settings->root."plugins/".$sPlugin."/controllers/".$sControllerFile.".php");
				} else {
					return false;
				}
			}
		}

		$oController = new $sController;

		return $oController;
	}
	function loadModel($sModel) {
		if(!class_exists("appModel")) {
			require($this->settings->root."appModel.php");
		}

		if(!class_exists($sModel."_model")) {
			if(is_file($this->settings->root."plugins/".$sModel."/model.php")) {
				require($this->settings->root."plugins/".$sModel."/model.php");
			} else {
				return false;
			}
		}
		$sModel = $sModel."_model";

		$sModel = new $sModel;

		return $sModel;
	}
	function getSetting($sTag) {
		if(empty($sTag)) {
			$this->sendError("getSetting", "Setting tag not passed", null, debug_backtrace());
		}

		$aSetting = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}settings`"
				." WHERE `tag` = ".$this->dbQuote($sTag, "text")
			,"row"
		);

		if(empty($aSetting)) {
			$this->sendError("getSetting", "Could not find setting", null, debug_backtrace());
		}

		if(!class_exists("Form")) {
			include($this->settings->root."helpers/Form.php");
		}

		$oField = new Form($aSetting);

		return $oField->setting->value();
	}
	function getUser($sId) {
		if(empty($sId)) {
			$this->sendError("getUser", "User id missing", null, debug_backtrace());
		}

		$aUser = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}users`"
				." WHERE `id` = ".$this->dbQuote($sId, "integer")
			,"row"
		);

		if(empty($aUser)) {
			//$this->sendError("getUser", "Could not find user", null, debug_backtrace());
			return array();
		}

		return $aUser;
	}
	function deleteDir($sFolder) {
		if(is_dir($sFolder)) {
			$oFolder  = opendir($sFolder);
			while (false !== ($sFile = readdir($oFolder))) {
				if($sFile != "." && $sFile != "..") {
					if(is_dir($sFolder."/".$sFile)) {
						$this->deleteDir($sFolder."/".$sFile);
					} else {
						unlink($sFolder."/".$sFile);
					}
				}
			}
			closedir($oFolder);

			rmdir($sFolder);
		}
	}
	function loadTwitter($sDecode = true) {
		require_once($this->settings->root."helpers/twitteroauth.php");

		$sConsumerKey = $this->getSetting("twitter_consumer_key");
		$sConsumerSecret = $this->getSetting("twitter_consumer_secret");
		$aAccess = $this->getSetting("twitter_connect");

		$oTwitter = new TwitterOAuth($sConsumerKey, $sConsumerSecret, $aAccess["oauth_token"], $aAccess["oauth_token_secret"]);

		if($sDecode == false) {
			$oTwitter->decode_json = false;
		}

		/* Check authentication */
		$aUser = $oTwitter->get("account/verify_credentials");
		if($oTwitter->http_code != 200) {
			return false;
		}

		return $oTwitter;
	}
	function loadFacebook() {
		require_once($this->settings->root."helpers/facebook.php");

		$oFacebook = new Facebook(array(
			'appId'  => $this->getSetting("facebook_app_id"),
		  	'secret' => $this->getSetting("facebook_app_secret"),
		  	'cookie' => false,
		));

		$aFacebookConnect = $this->getSetting("facebook_connect");

		$aFacebook = array("obj" => $oFacebook, "access_token" => $this->decrypt($aFacebookConnect["post_access_token"]));

		return $aFacebook;
	}
	function loadMailChimp() {
		require_once($this->settings->root."helpers/mailchimp.php");

		$oMailChimp = new MCAPI($this->getSetting("mailchimp-api"));

		return $oMailChimp;
	}
	function urlShorten($sUrl) {
		$sUser = $this->getSetting("bitly_user");
		$sKey = $this->getSetting("bitly_key");

		$sUrl = "http://api.bit.ly/v3/shorten?login=".urlencode($sUser)."&apiKey=".$sKey."&longUrl=".urlencode($sUrl)."&format=json";

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $sUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$sResults = curl_exec($ch);
		$aInfo = curl_getinfo($ch);

		curl_close($ch);

		// Throw error
		if($aInfo["http_code"] != 200) {
			return false;
		}

		$aResults = json_decode($sResults, true);

		$sUrl = $aResults["data"]["url"];

		return $sUrl;
	}
	##################################

	### Database #####################
	function dbQuery($sSQL, $return = null) {
		// Prefix
		$sSQL = str_replace("{dbPrefix}", $this->settings->dbPrefix, $sSQL);

		switch($return) {
			case "all":
				$aReturn = $this->_db->getAll($sSQL);
				break;
			case "row":
				$aReturn = $this->_db->getRow($sSQL);
				break;
			case "one":
				$aReturn = $this->_db->getOne($sSQL);
				break;
			case "col":
				$aReturn = $this->_db->getCol($sSQL);
				break;
			case "rows":
				$aReturn = $this->_db->affectedRows($sSQL);
				break;
			case "insert":
				$aReturn = $this->_db->lastInsertID($sSQL);
				break;
			default:
				$aReturn = true;
		}

		$this->_db->free();

		return $aReturn;
	}
	function dbQuote($sValue, $sType) {
		switch($sType) {
			case 'text':
				$sValue = "'".$sValue."'";
		}

		return $sValue;
	}
	function dbInsert($sTable, $aData) {
		$sTable = $this->settings->dbPrefix.$sTable;

		return $this->_db->insert($sTable, $aData);
	}
	function dbUpdate($sTable, $aData, $sId, $sIdField = "id", $sIdType = "integer") {
		$sTable = $this->settings->dbPrefix.$sTable;

		if($sIdType != 'integer') {
			$sId = $this->_db->escape($sId);
		}

		$sWhere = '`'.$sIdField.'` = ?';
		$aData['.Where'] = $sId;

		return $this->_db->update($sTable, $aData, $sWhere);
	}
	function dbDelete($sTable, $sId, $sIdField = "id", $sIdType = "integer") {
		$sTable = $this->settings->dbPrefix.$sTable;

		if($sIdType != 'integer') {
			$sId = $this->_db->escape($sId);
		}

		$sWhere = '`'.$sIdField.'` = ?';
		$aData = array($sId);

		return $this->_db->delete($sTable, $aData, $sWhere);
	}
	##################################

	### Template #####################
	public function loadComponents() {
		$components = dir(APP.'components/');
		while (false !== ($entry = $components->read())) {
			if(!empty($entry) && !in_array($entry, ['.','..'])) {
				include(APP.'components/'.$entry);
			}
		}
	}
	public function tplAssign($variable, $value) {
		$this->assign[$variable] = $value;
	}
	public function tplDisplay($sTemplate, $aAssign = array(), $sReturn = false) {
		if(is_file($this->settings->tplDir.$sTemplate)) {
			extract($aAssign);
			extract($this->assign);

			ob_start();

			include($this->settings->tplDir.$sTemplate);

			if($sReturn == true) {
				$sView = ob_get_contents();
				ob_end_clean();
				return $sView;
			} else {
				ob_end_flush();
				return true;
			}
		} else {
			$aTrace = debug_backtrace();
			echo 'Could not load view \''.$sTemplate.'\'. File not found. ('.$this->settings->tplDir.$sTemplate.')';
			echo "<pre>";
			print_r($aTrace);
			echo "</pre>";
		}
	}
	public function tplExists($sTemplate) {
		return is_file($this->root.'app/views/'.$sTemplate);
	}
	###################################

	### Mail ##########################
	function mail($aHeaders, $bodyText, $bodyHTML = null, $aAttachment = array()) {
		if(!class_exists("Mail_mime")) {
			include("Mail/mime.php");
		}

		$oMime = new Mail_mime("\n");

		//Build Recipients
		$aRecipients = array();
		foreach(array("To", "Cc", "Bcc") as $sHeader) {
			if(isset($aHeaders[$sHeader])) {
				preg_match_all('/\b[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}\b/i', $aHeaders[$sHeader], $aTempRecipients);
				foreach($aTempRecipients[0] as $x => $sRecipient) {
					if(!in_array($sRecipient, $aRecipients)) {
						$aRecipients[] = $sRecipient;
					}
				}
			}
		}

		$sRecipients = implode(", ", $aRecipients);

		// Set text for message body
		$oMime->setTXTBody($bodyText);

		// Set HTML message for body
		if(!empty($bodyHTML)) {
			$oMime->setHTMLBody($bodyHTML);
		}

		// Add attachments to message
		foreach($aAttachment as $aFile) {
			$oMime->addAttachment($aFile[0], $aFile[1]);
		}

		$sBody = $oMime->get();
		$aHeaders = $oMime->headers($aHeaders);

		// Send message
		$oMail = $this->_mail->send($sRecipients, $aHeaders, $sBody);

		if(PEAR::iserror($oMail)) {
			$this->sendError("Mail - ".$aHeaders["Subject"], $oMail->message);
		} else {
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

	### Errors #######################
	function error($error = "404") {
		switch($error) {
			case "403":
				header('HTTP/1.1 403 Forbidden');
				$this->tplDisplay("error/403.php");
				break;
			case "404":
				header("HTTP/1.1 404 Not Found");
				$this->tplDisplay("error/404.php");
				break;
			case "500":
				header("HTTP/1.1 500 Internal Server Error");
				$this->tplDisplay("error/500.php");
				break;
		}
		exit;
	}
	protected function sendError($section, $error, $db = null, $aTrace = array()) {
		if(empty($aTrace)) {
			$aTrace = debug_backtrace();
		}

		$headers["To"] = $this->settings->adminInfo["email"];
		$headers["From"] = "noreply@monkee-create.com";
		$headers["Subject"] = $_SERVER["HTTP_HOST"]." - ".$section." Error";

		$body = "Domain: ".$_SERVER["HTTP_HOST"]."\n";
		$body .= "URL: ".$_SERVER["REQUEST_URI"]."\n";
		$body .= "Time: ".date("M j,Y - h:i:s a")."\n";
		$body .= "Where: ".$section."\n";
		if(!empty($db)) {
			$aUserInfo = preg_split('/\] \[/',str_replace(array("_doQuery: [", "]\n[", "]\n"),array(null, "] [", null),$db->userinfo));
			$aMessage = preg_split('/: /',$aUserInfo[3]);
			$body .= "Error: ".$db->message."\n";
			$body .= $aMessage[1]."\n";
			$body .= "Query: ".$this->_db->last_query."\n";
		} else {
			$body .= "Error: ".$error."\n";
		}
		$body .= "File: ".$aTrace[0]["file"]."\n";
		$body .= "Line: ".$aTrace[0]["line"]."\n";
		$body .= "User Agent: ".$_SERVER["HTTP_USER_AGENT"]."\n";
		$body .= "Referer: ".$_SERVER["HTTP_REFERER"]."\n";

		if($this->settings->debug == true) {
			die(str_replace("\n","<br />",$body));
		} else {
			$this->mail($headers, $body);
		}

		$this->error("500");
	}
	##################################
}
