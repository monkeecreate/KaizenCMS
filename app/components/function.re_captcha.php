<?php
function re_captcha() {
	global $oApp;

	require_once($oApp->settings->root.'helpers/recaptchalib.php');
	$publickey = "6LfXQwkAAAAAAAlpqjlApE8ZsoW2vu5w4Cm-BlBe"; // you got this from the signup page
	echo recaptcha_get_html($publickey);
}
