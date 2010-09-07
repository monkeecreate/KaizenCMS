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
				$objDB->query("UPDATE `".$aConfig["database"]["prefix"]."settings` SET "
					." `value` = ''"
					." WHERE `tag` = ".$objDB->quote("twitter_connect", "text")
				);
				$aValue = "";
			} else {
				$aUser = json_decode($sUser, true);
			}
		}
		
		if(empty($aValue) || empty($aValue["screen_name"])) {		
			$sHTML = "<a href=\"/admin/settings/twitter/redirect/\">";
			$sHTML .= "<img src=\"/images/admin/social/twitter_lighter.png\">";
			$sHTML .= "</a>\n";
		} else {
			$sHTML = $this->getLabel("Signed in as: <a href=\"http://twitter.com/".$aUser["screen_name"]."\"><img src=\"".$aUser["profile_image_url"]."\"> ".$aUser["screen_name"]."</a> <a href=\"/admin/settings/twitter/unlink/\">Unlink</a>");
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
		return $value;
	}
}