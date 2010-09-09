<?php
class Form_facebook extends Form_Field
{
	private $_options;
	private $_setting;
	
	public function __construct($aSetting) {
		$this->_setting = $aSetting;
		$this->_options = $this->getOptions($aSetting["type"]);
	}
	
	public function html() {		
		$aValue = $this->value();
		
		if(empty($aValue) || empty($aValue["user_access_token"])) {		
			$sHTML = '<a href="/admin/settings/facebook/redirect/" title="Facebook Connect">';
			$sHTML .= '<img src="/images/admin/social/facebookConnect.gif" alt="Facebook Connect">';
			$sHTML .= '</a>';
		} else {
			global $objDB, $site_root, $aConfig, $oEnc;
			
			$sAppId = $objDB->query("SELECT `value` FROM `".$aConfig["database"]["prefix"]."settings`"
				." WHERE `tag` = ".$objDB->quote("facebook_app_id", "text")
			)->fetchOne();
			
			$sAppSecret = $objDB->query("SELECT `value` FROM `".$aConfig["database"]["prefix"]."settings`"
				." WHERE `tag` = ".$objDB->quote("facebook_app_secret", "text")
			)->fetchOne();
			
			require_once($site_root."helpers/facebook.php");
			Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = false;
			Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYHOST] = 2;
			
			$facebook = new Facebook(array(
				'appId'  => $sAppId,
			  	'secret' => $sAppSecret,
			  	'cookie' => true,
			));
			
			$oEnc->decrypt($aValue["user_access_token"]);
			
			$aFacebookResult = $facebook->api('/me/', 'get', array("access_token" => $oEnc->decrypt($aValue["user_access_token"])));
			$aFacebookAccounts = $facebook->api('/me/accounts', 'get', array("access_token" => $oEnc->decrypt($aValue["user_access_token"])));
			
			//print_r($aFacebookAccounts);
			$sHTML = '<div class="facebookConnect" style="background:#fff; padding: 15px; border: 1px solid #bbb; overflow: hidden; width: 350px;">';
			$sHTML .= '<h4><img src="/images/admin/social/facebook_32.png" height="20px"> Facebook Connect</h4>';
			
			$sHTML .= '<img src="https://graph.facebook.com/'.$aFacebookResult["id"].'/picture" class="left" style="margin: 0 10px 10px 0;"> <span style="font-size: 1.3em;">Connected as <strong>'.$aFacebookResult["name"].'</strong></span><br /><br />';
			
			$sHTML .= '<span style="font-size: 1.2em;"><strong>Post to</strong>: </span>';
			$sHTML .= '<select name="facebook_accounts">';
				$sHTML .= '<option name="user" value="'.$aValue["user_access_token"].'"';
				if($oEnc->decrypt($aValue["post_access_token"]) == $oEnc->decrypt($aValue["user_access_token"]))
					$sHTML .= ' selected="selected"';
				$sHTML .= '>'.$aFacebookResult["name"].' (user)</option>';
				$sHTML .= '<optgroup label="Facebook Pages">';
				foreach($aFacebookAccounts["data"] as $key => $aFacebookAccount) {
					$sHTML .= '<option name="'.$aFacebookAccount["id"].'" value="'.$oEnc->encrypt($aFacebookAccount["access_token"]).'"';
					if($oEnc->decrypt($aValue["post_access_token"]) == $aFacebookAccount["access_token"])
						$sHTML .= ' selected="selected"';
					$sHTML .= '">'.$aFacebookAccount["name"].'</option>';
				}
			$sHTML .= '</optgroup></select>';
			
			$sHTML .= '<span style="font-size: 1.2em;"><a href="/admin/settings/facebook/unlink/" title="Remove Facebook Connection">Remove Connection to Facebook</a><br /></span></div>';
			
			//$aFacebookResult = $facebook->api('/me/feed/', 'post', array("access_token" => "127471297263601|d036dacb5bc836e5460ec9d8-644594809|6233220339|s-r1692tDVxms7l36yynyPUJT4k." ,"message" => "test from the api, ignore this post"));
		}
		
		$sHTML .= "<input type=\"hidden\" name=\"settings[".$this->_setting["tag"]."]\" value=\"".$this->value(false)."\" /><br /><br />\n";
	
		if(!empty($this->_setting["text"]))
			$sHTML .= $this->getText($this->_setting["text"])."\n";
		
		return $sHTML;
	}
	public function value($sDecode = true) {
		if($sDecode == true)
			return json_decode($this->_setting["value"], true);
		else
			return $this->_setting["value"];
	}
	public function save($value) {
		$aValue = $this->value();
		$aValue["post_access_token"] = $_POST["facebook_accounts"];
		return json_encode($aValue);
	}
}