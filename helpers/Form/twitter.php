<?php
class Form_twitter extends Form_Field
{
	private $_options;
	private $_setting;
	
	public function __construct($aSetting) {
		$this->_setting = $aSetting;
		$this->_options = $this->getOptions($aSetting["type"]);
	}
	
	public function html() {
		$sError = false;
		$aValue = $this->value();
		
		if(!empty($aValue) && !empty($aValue["screen_name"])) {
			global $objDB, $site_root, $aConfig;
			
			$sConsumerKey = $objDB->query("SELECT `value` FROM `".$aConfig["database"]["prefix"]."settings`"
				." WHERE `tag` = ".$objDB->quote("twitter_consumer_key", "text")
			)->fetchOne();
			$sConsumerSecret = $objDB->query("SELECT `value` FROM `".$aConfig["database"]["prefix"]."settings`"
				." WHERE `tag` = ".$objDB->quote("twitter_consumer_secret", "text")
			)->fetchOne();
			
			require_once($site_root."helpers/twitteroauth.php");
			
			$connection = new TwitterOAuth($sConsumerKey, $sConsumerSecret, $aValue["oauth_token"], $aValue["oauth_token_secret"]);
			$connection->decode_json = false;
			$sUser = $connection->get("account/verify_credentials");
			
			if($connection->http_code != 200) {
				$sError = true;
			} else {
				$aUser = json_decode($sUser, true);
			}
		}
		
		if(!empty($sConsumerKey) && !empty($sConsumerSecret)) {
			if(empty($aValue) || empty($aValue["screen_name"])) {		
				$sHTML = "<a href=\"/admin/settings/twitter/redirect/\">";
				$sHTML .= "<img src=\"/images/admin/social/twitter_lighter.png\">";
				$sHTML .= "</a>\n";
			} else {
				$sHTML = '<div class="twitterConnect socialConnect">';
				$sHTML .= '<h4><img src="/images/admin/social/twitter.png" height="20px"> Twitter Connect</h4>';
			
				if(!$sError) {
					$sHTML .= '<figure><img src="'.$aUser["profile_image_url"].'"></figure> <p>Connected as <strong>'.$aUser["screen_name"].'</strong></p>';
				} else {
					$sHTML .= '<p class="small">We were unable to connect to your Twitter account. This could be due to Twitter being down or an invalid connection with your account. If the problem persists remove the connection below and Connect to Twitter again.</p>';
				}
			
				$sHTML .= '<p class="small"><a href="/admin/settings/twitter/unlink/" title="Remove Twitter Connection">Remove Connection to Twitter</a></p></div>';
			}
			
			$sHTML .= "<input type=\"hidden\" name=\"settings[".$this->_setting["tag"]."]\" value='".$this->value(false)."' /><br /><br />\n";
		}		
	
		if(!empty($this->_setting["text"]))
			$sHTML .= $this->getText($this->_setting["text"])."\n";
		
		return $sHTML;
	}
	public function value($sDecode = true) {
		if($sDecode == true)
			return json_decode(stripslashes($this->_setting["value"]), true);
		else
			return $this->_setting["value"];
	}
	public function save($value) {
		return stripslashes($value);
	}
}