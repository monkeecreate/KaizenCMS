<?php
class admin_settings_social extends appController
{
	function twitter_redirect() {
		require_once($this->settings->root."helpers/twitteroauth.php");
		
		$sConsumerKey = $this->getSetting("twitter_consumer_key");
		$sConsumerSecret = $this->getSetting("twitter_consumer_secret");
		
		$connection = new TwitterOAuth($sConsumerKey, $sConsumerSecret);
		
		$sPrefix = 'http';
		if ($_SERVER["HTTPS"] == "on") {$sPrefix .= "s";}
		$sPrefix .= "://";
		
		/* Get temporary credentials. */
		$request_token = $connection->getRequestToken($sPrefix.$_SERVER["HTTP_HOST"]."/admin/settings/twitter/connect/");
		
		/* Save temporary credentials to session. */
		$_SESSION["oauth_token"] = $token = $request_token["oauth_token"];
		$_SESSION["oauth_token_secret"] = $request_token["oauth_token_secret"];
		
		/* If last connection failed don't display authorization link. */
		switch ($connection->http_code) {
			case 200:
				/* Build authorize URL and redirect user to Twitter. */
				$url = $connection->getAuthorizeURL($token);
				header("Location: ".$url);
				break;
			default:
				header("Location: /admin/settings/?error=".urlencode("Could not connect to Twitter. Please try again later."));
		}
	}
	function twitter_connect() {
		require_once($this->settings->root."helpers/twitteroauth.php");
		
		$sConsumerKey = $this->getSetting("twitter_consumer_key");
		$sConsumerSecret = $this->getSetting("twitter_consumer_secret");
		
		/* If the oauth_token is old redirect to the connect page. */
		if (isset($_REQUEST["oauth_token"]) && $_SESSION["oauth_token"] !== $_REQUEST["oauth_token"]) {
			header("Location: /admin/settings/");
		}
		
		/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
		$connection = new TwitterOAuth($sConsumerKey, $sConsumerSecret, $_SESSION["oauth_token"], $_SESSION["oauth_token_secret"]);
		$connection->decode_json = false;
		
		/* Request access tokens from twitter */
		$access_token = $connection->getAccessToken($_REQUEST["oauth_verifier"]);
		
		/* Remove no longer needed request tokens */
		unset($_SESSION["oauth_token"]);
		unset($_SESSION["oauth_token_secret"]);
		
		/* If HTTP response is 200 continue otherwise send to connect page to retry */
		if (200 == $connection->http_code) {
			/* The user has been verified and the access tokens can be saved for future use */
			$this->dbUpdate(
				"settings",
				array(
					"value" => json_encode($access_token)
				),
				"twitter_connect", "tag", "text"
			);
			header("Location: /admin/settings/?notice=".urlencode("Your Twitter account has now been actived with your website."));
		} else {
			header("Location: /admin/settings/?error=".urlencode("Error"));
		}
	}
	function twitter_unlink() {
		$this->dbUpdate(
			"settings",
			array(
				"value" => ""
			),
			"twitter_connect", "tag", "text"
		);
		header("Location: /admin/settings/?notice=".urlencode("Your Twitter account access has been removed from this site."));
	}
	function facebook_redirect() {
		require_once($this->settings->root."helpers/facebook.php");
		
		$facebook = new Facebook(array(
	    	'appId'  => $this->getSetting("facebook_app_id"),
	      	'secret' => $this->getSetting("facebook_app_secret"),
	      	'cookie' => false, // enable optional cookie support
	    ));
		
		$sPrefix = 'http';
		if ($_SERVER["HTTPS"] == "on") {$sPrefix .= "s";}
		$sPrefix .= "://";
		
		header("Location: ".$facebook->getLoginUrl(array("req_perms" => "user_photos,user_videos,user_status,create_event,rsvp_event,publish_stream,manage_pages,offline_access" , "next" => $sPrefix.$_SERVER["HTTP_HOST"]."/admin/settings/facebook/connect/")));
	}
	function facebook_connect() {		
		$authorizeSession = json_decode(stripslashes($_GET["session"]), true);
				
		$this->dbUpdate(
			"settings",
			array(
				"value" => json_encode(array("user_access_token" => $this->encrypt($authorizeSession["access_token"]), "post_access_token" => $this->encrypt($authorizeSession["access_token"])))
			),
			"facebook_connect", "tag", "text"
		);
		
		header("Location: /admin/settings/");
	}
	function facebook_unlink() {
		$this->dbUpdate(
			"settings",
			array(
				"value" => ""
			),
			"facebook_connect", "tag", "text"
		);
		header("Location: /admin/settings/?notice=".urlencode("Your Facebook account access has been removed from this site."));
	}
}