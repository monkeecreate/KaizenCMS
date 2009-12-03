<?php
class appController
{
	protected $_db;
	protected $_memcache;
	protected $_mail;
	protected $_smarty;
	protected $_firephp;
	public $_settings;
	public $_enc;
	
	function appController()
	{
		global $objDB, $oMemcache, $objMail, $oFirePHP, $oEnc, $oSmarty, $site_public_root, $site_root, $aConfig, $sURL, $aUrl;
		
		$this->_db = $objDB;
		$this->_memcache = $oMemcache;
		$this->_mail = $objMail;
		$this->_firephp = $oFirePHP;
		$this->_enc = $oEnc;
		$this->_smarty = $oSmarty;
		$this->_settings = (object) array(
			"root" => $site_root
			,"root_public" => $site_public_root
			,"admin_info" => $aConfig["admin_info"]
			,"debug" => $aConfig["options"]["debug"]
			,"surl" => $sURL
			,"url" => $aUrl
			,"memcache_salt" => $aConfig["memcache"]["salt"]
		);
	}
	
	### Functions ####################
	function forward($url, $type = "")
	{
		switch($type)
		{
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
	function site_info()
	{
		echo "<pre>";
		print_r($this->_settings);
		print_r($this->_db);
		print_r($this->SMARTY);
		echo "</pre>";
		
		phpinfo();
	}
	function loadModule($sModule)
	{
		if(!class_exists($sModule."_model"))
			require($this->_settings->root."models/".$sModule.".model.php");
			
		$sModule = $sModule."_model";
		
		$oModule = new $sModule;
		// $oModule->loadModel($this);
		
		return $oModule;
	}
	##################################
	
	### Database #####################
	function db_results($sSQL, $section, $return = null)
	{
		$oResult = $this->_db->query($sSQL);
		
		if(PEAR::isError($oResult))
			$this->send_error($section, "dberror", $oResult);
			
		switch($return)
		{
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
	function db_quote($sValue, $sType)
	{
		return $this->_db->quote($sValue, $sType);
	}
	##################################
	
	### Template #####################
	function template_exists($template_file)
	{
		$template_file = $this->_smarty->template_dir."/".$template_file;

		return is_file($template_file);
	}
	function tpl_assign($sVariable, $sValue)
	{
		$this->_smarty->assign($sVariable, $sValue);
	}
	function tpl_display($sTemplate)
	{
		if($this->template_exists($sTemplate))
			$this->_smarty->display($sTemplate);
	}
	function tpl_variable_get($sVariable)
	{
		return $this->_smarty->$sVariable;
	}
	function tpl_variable_set($sVariable, $sValue)
	{
		$this->_smarty->$sVariable = $sValue;
	}
	###################################
	
	### Mail ##########################
	function mail($recipients, $headers, $message)
	{
		$mail = $this->_mail->send($recipients, $headers, $message);
	}
	###################################
	
	### Encryption ####################
	function encrypt($text)
	{
		return $this->_enc->encrypt($text);
	}
	function decrypt($text)
	{
		return $this->_enc->decrypt($text);
	}
	##################################
	
	### Memcache #####################
	function memcache_get($key)
	{
		$value = $this->_memcache->get(md5($this->_settings->memcache_salt.$key));
		
		if($value != false)
			return $this->decrypt($value);
		else
			return false;
	}
	function memcache_set($key, $value, $expire = 0)
	{
		return $this->_memcache->set(md5($this->_settings->memcache_salt.$key), $this->encrypt($value), false, $expire);
	}
	##################################

	### Errors #######################
	function log($log)
	{
		$this->_fireftp->log($log);
	}
	function error($error = "404")
	{
		switch($error)
		{
			case "403":
				header('HTTP/1.1 403 Forbidden');
				$this->_smarty->display("error/403.tpl");
				break;
			case "404":
				header("HTTP/1.1 404 Not Found");
				$this->_smarty->display("error/404.tpl");
				break;
			case "500":
				header("HTTP/1.1 500 Internal Server Error");
				$this->_smarty->display("error/500.tpl");
				break;
		}
		exit;
	}
	protected function send_error($section, $error, $db = null)
	{
		$recipients = $this->_settings->admin_info["email"];
		$headers["To"] = $this->_settings->admin_info["email"];
		$headers["From"] = $this->_settings->admin_info["email"];
		$headers["Subject"] = "Website Error - ".$section;
		
		$body = "Where: ".$section."\n";
		if(!empty($db))
		{
			$aUserInfo = preg_split('/\] \[/',str_replace(array("_doQuery: [", "]\n[", "]\n"),array(null, "] [", null),$db->userinfo));
			$aMessage = preg_split('/: /',$aUserInfo[3]);
			$body .= "Error: ".$db->message."\n";
			$body .= $aMessage[1]."\n";
			$body .= "Query: ".$this->_db->last_query."\n";
			$body .= "User Agent: ".$_SERVER["HTTP_USER_AGENT"]."\n";
			$body .= "Referer: ".$_SERVER["HTTP_REFERER"]."\n";
			$body .= "URL: ".$_SERVER["REQUEST_URI"]."\n";
			$body .= "Time: ".time()."\n";
			
		}
		else
		{
			$body .= "Error: ".$error."\n";
			$body .= "User Agent: ".$_SERVER["HTTP_USER_AGENT"]."\n";
			$body .= "Referer: ".$_SERVER["HTTP_REFERER"]."\n";
			$body .= "URL: ".$_SERVER["REQUEST_URI"]."\n";
			$body .= "Time: ".time()."\n";
		}
		
		if($this->_settings->debug == true)
			die(str_replace("\n","<br />",$body));
		else
			$this->mail($recipients, $headers, $body);
		
		$this->error("500");
	}
	##################################
}