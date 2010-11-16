<?php
/**
* outputfilter adds content of {footer}-blocks to end of document <body>
* @param string
* @param Smarty
* @return string
*/
function smarty_outputfilter_move_to_footer($tpl_output, &$smarty)
{
	$matches = array();
	preg_match_all('!@@@SMARTY:FOOTER:BEGIN@@@(.*?)@@@SMARTY:FOOTER:END@@@!is', $tpl_output, $matches);
	$tpl_output = preg_replace("!@@@SMARTY:FOOTER:BEGIN@@@(.*?)@@@SMARTY:FOOTER:END@@@!is", '', $tpl_output);
	return str_replace('</body>', implode("\n", array_unique($matches[1]))."\n".'</body>', $tpl_output);
}