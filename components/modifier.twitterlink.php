<?php
function smarty_modifier_twitterlink($string, $format = false)
{
	/* HTTP LINKS */
	$string = preg_replace('@(http?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="\0" target="_blank">\0</a>', $string);
	/* HTTP LINKS */
	
	/* @ LINKS */
	$string = preg_replace("/\@([a-z0-9]+)/i", "<a href=\"http://www.twitter.com/$1\" target=\"_blank\">@$1</a>", $string);
	/* @ LINKS */
	
	return $string;
}