<?php
class appController
{
	protected $_db;
	protected $_memcache;
	protected $_mail;
	protected $_smarty;
	protected $_firephp;
	protected $_settings;
	protected $_enc;
	
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
	protected function forward($url, $type = "")
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
	protected function site_info()
	{
		echo "<pre>";
		print_r($this->_settings);
		print_r($this->_db);
		print_r($this->SMARTY);
		echo "</pre>";
		
		phpinfo();
	}
	protected function db_results($sSQL, $section, $return)
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
	protected function template_exists($template_file)
	{
		$template_file = $this->_smarty->template_dir."/".$template_file;

		return is_file($template_file);
	}
	protected function mail($recipients, $headers, $message)
	{
		$mail = $this->_mail->send($recipients, $headers, $message);
	}
	protected function encrypt($text)
	{
		return $this->_enc->encrypt($text);
	}
	protected function decrypt($text)
	{
		return $this->_enc->decrypt($text);
	}
	protected function memcache_get($key)
	{
		$value = $this->_memcache->get(md5($this->_settings->memcache_salt.$key));
		
		if($value != false)
			return $this->decrypt($value);
		else
			return false;
	}
	protected function memcache_set($key, $value, $expire = 0)
	{
		return $this->_memcache->set(md5($this->_settings->memcache_salt.$key), $this->encrypt($value), false, $expire);
	}
	##################################

	### Errors #######################
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
			$body .= "URL: ".$_SERVER["REQUEST_URI"]."\n";
			$body .= "Time: ".time()."\n";
			
		}
		else
		{
			$body .= "Error: ".$error."\n";
			$body .= "User Agent: ".$_SERVER["HTTP_USER_AGENT"]."\n";
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