<?php
class Form_facebook extends Form_Field {
	private $_options;
	private $_setting;

	public function __construct($aSetting) {
		$this->_setting = $aSetting;
		$this->_options = $this->getOptions($aSetting["type"]);
	}

	public function html() {
		$aValue = $this->value();

		global $objDB, $aConfig, $oEnc;

		$sAppId = $objDB->getOne("SELECT `value` FROM `".$aConfig["database"]["prefix"]."settings`"
			." WHERE `tag` = ".$objDB->escape("facebook_app_id")
		);

		$sAppSecret = $objDB->getOne("SELECT `value` FROM `".$aConfig["database"]["prefix"]."settings`"
			." WHERE `tag` = ".$objDB->escape("facebook_app_secret")
		);

		if(!empty($sAppId) && !empty($sAppSecret)) {
			if(empty($aValue) || empty($aValue["user_access_token"])) {
				$sHTML = '<a href="/admin/settings/facebook/redirect/" title="Facebook Connect">';
				$sHTML .= '<img src="/images/admin/social/facebookConnect.gif" alt="Facebook Connect">';
				$sHTML .= '</a>';
			} else {
				require_once(APP."helpers/facebook.php");

				$facebook = new Facebook(array(
					'appId'  => $sAppId,
				  	'secret' => $sAppSecret,
				  	'cookie' => false,
				));

				try {
					$aFacebookResult = $facebook->api('/me/', 'get', array("access_token" => $oEnc->decrypt($aValue["user_access_token"])));
					$aFacebookAccounts = $facebook->api('/me/accounts', 'get', array("access_token" => $oEnc->decrypt($aValue["user_access_token"])));
				} catch (FacebookApiException $e) {
					//print_r($e);
					error_log($e);
				}

				$sHTML = '<div class="facebookConnect socialConnect">';
				$sHTML .= '<h4><img src="/images/admin/social/facebook_32.png" height="20px"> Facebook Connect</h4>';

				if(!empty($aFacebookResult)) {
					$sHTML .= '<figure><img src="https://graph.facebook.com/'.$aFacebookResult["id"].'/picture"></figure> <p>Connected as <strong>'.$aFacebookResult["name"].'</strong></p>';
					$sHTML .= '<label for="facebook_accounts">Post to </label>';
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
					$sHTML .= '</optgroup></select><br />';
				} else {
					$sHTML .= '<p class="small">We were unable to connect to your Facebook account. This could be due to Facebook being down or an invalid connection with your account. If the problem persists remove the connection below and Connect to Facebook again.</p>';
				}

				$sHTML .= '<p class="small"><a href="/admin/settings/facebook/unlink/" title="Remove Facebook Connection">Remove Connection to Facebook</a></p></div>';
			}

			$sHTML .= "<input type=\"hidden\" name=\"settings[".$this->_setting["tag"]."]\" value=\"".$this->value(false)."\" /><br /><br />\n";
		}

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
		if(!empty($aValue))
			$aValue["post_access_token"] = $_POST["facebook_accounts"];
		return json_encode($aValue);
	}
}
