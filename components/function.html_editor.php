<?php
function smarty_function_html_editor($params, &$smarty)
{
	$content = $params["content"];
	
	if(empty($params["width"]))
		$width = "645";
	else
		$width = $params["width"];
		
	if(empty($params["height"]))
		$height = "450";
	else
		$height = $params["height"];
	
	$content = str_replace("SCRIPT", "SCR'+'IPT", 
		str_replace("script", "scr'+'ipt", 
			str_replace("\t", "\\t", 
				str_replace("\n", "\\n", 
					str_replace("\r", "\\r", 
						str_replace("'", "\'", 
							str_replace("</", "<\/", stripslashes($content)
							)
						)
					)
				)
			)
		)
	);
	
	switch($params["toolbar"])
	{
		case "min":
			$toolbar = "{toolbar1: 'undo redo fontsize bold italic underline forecolor table insertmedia createlink insertorderedlist insertunorderedlist viewsource save'}";
			break;
		case "COPACT":
			$toolbar = "{toolbar: '-COMPACT-'}";
			break;
		case "COMPACT2":
			$toolbar = "{toolbar: '-COMPACT2-'}";
			break;
		case "MINIMAL":
			$toolbar = "{toolbar: '-MINIMAL-'}";
			break;
		case "FULL":
			$toolbar = "{toolbar: '-FULL-'}";
			break;
		case "ALL":
			$toolbar = "{toolbar: '-ALL-'}";
			break;
		default:
			$toolbar = "{toolbar: '-DEFAULT-'}";
	}
	
	if(empty($params["type"]))
		$skin = "classic";
	else
		$skin = $params["type"];
	
	$return = "<script type=\"text/javascript\">WebEditorSkin(".$skin.");</script>\n";
	$return .= "<table style=\"border: 2px solid rgb(210, 210, 210); width: ".$width."px;\" cellpadding=\"0\" cellspacing=\"0\">\n";
	$return .= "\t<tbody>\n";
	$return .= "\t\t<tr>\n";
	$return .= "\t\t\t<td class=\"webeditor_toolbar\">\n";
	$return .= "\t\t\t\t<script type=\"text/javascript\">WebEditorToolbar(".$toolbar.");</script>\n";
	$return .= "\t\t\t</td>\n";
	$return .= "\t\t</tr>\n";
	$return .= "\t\t<tr>\n";
	$return .= "\t\t\t<td id=\"inner\" style=\"height: ".$height."px;\">\n";
	$return .= "\t\t\t\t<script type=\"text/javascript\">\n";
	$return .= "\t\t\t\t\t<!--\n";
	$return .= "\t\t\t\t\tcontent = '".$content."';\n";
	$return .= "\t\t\t\t\tWebEditor('content', content, {stylesheet: '/css/editor.css', manager: 'manager', format: 'xhtml', language: 'php', baseHref: '/', rootpath: '/editor/'});\n";
	$return .= "\t\t\t\t\t-->\n";
	$return .= "\t\t\t\t</script>\n";
	$return .= "\t\t\t</td>\n";
	$return .= "\t\t</tr>\n";
	$return .= "\t\t<tr>\n";
	$return .= "\t\t\t<td style=\"width: 100%;\">\n";
	$return .= "\t\t\t\t<script type=\"text/javascript\">WebEditorDOMInspector();</script>\n";
	$return .= "\t\t\t</td>\n";
	$return .= "\t\t</tr>\n";
	$return .= "\t</tbody>\n";
	$return .= "</table>\n";
	
	return $return;
}