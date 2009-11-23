<?php
function smarty_function_post_data($aParams, &$oSmarty)
{
	return $_SESSION["post_data"][$aParams["key"]];
}