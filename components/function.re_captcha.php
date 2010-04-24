<?php
function smarty_function_re_captcha($aParams, &$oSmarty) {
	$oApp = $oSmarty->get_registered_object("appController");
	
	require_once($oApp->_settings->root.'helpers/recaptchalib.php');
	$publickey = "6LfXQwkAAAAAAAlpqjlApE8ZsoW2vu5w4Cm-BlBe"; // you got this from the signup page
	echo recaptcha_get_html($publickey);

}