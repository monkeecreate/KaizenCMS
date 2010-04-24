<?php
function smarty_function_enc_encrypt($aParams, &$oSmarty) {
	$oApp = $oSmarty->get_registered_object("appController");
	
	return $oApp->encrypt($aParams["value"]);
}