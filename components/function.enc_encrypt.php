<?php
function smarty_function_enc_encrypt($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	
	return $oApp->encrypt($aParams["value"]);
}