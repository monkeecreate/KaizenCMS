<?php
function smarty_modifier_clean_html($sText)
{
	return htmlspecialchars(stripslashes($sText));
}