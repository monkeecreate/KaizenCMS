<?php
function smarty_modifier_to_currency($string, $format = false) {
	if($format == "true")
		return number_format($string, 2);
	else
		return number_format($string);
}