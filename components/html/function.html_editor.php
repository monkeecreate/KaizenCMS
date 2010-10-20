<?php
function smarty_function_html_editor($aParams, &$smarty) {
	if(empty($params["width"]))
		$width = "100%";
	else
		$width = $aParams["width"];
		
	if(empty($params["height"]))
		$height = "500";
	else
		$height = $aParams["height"];
	
	$content = stripslashes($aParams["content"]);
	
	$return = "@@@SMARTY:FOOTER:BEGIN@@@\n";
	$return .= "<script type='text/javascript' src='/scripts/tiny_mce/tiny_mce.js'></script>\n";
	$return .= "<script type='text/javascript' src='/scripts/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php'></script>\n";
	$return .= "<script type='text/javascript'>\n";
	$return .= "tinyMCE.init({\n";
	$return .= "\tmode : 'textareas',\n";
	$return .= "\ttheme : 'advanced',\n";
	$return .= "\tskin : 'thebigreason',\n";
	$return .= "\tplugins : 'safari,contextmenu,advlist,embed,imagemanager,filemanager,advimage,advlink,paste,table,preview,fullscreen',\n";
	$return .= "\teditor_selector : '".$aParams["name"]."_editor',\n";
	$return .= "\textended_valid_elements : 'object[width|height|classid|codebase]"
		.",param[name|value],embed[src|type|width|height|flashvars|wmode]"
		.",iframe[align<bottom?left?middle?right?top|class|frameborder|height|id|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style|title|width]"
		."',\n";
	$return .= "\trelative_urls: false,\n";
	$return .= "\twidth: '".$width."',\n";
	$return .= "\theight: '".$height."',\n";
	$return .= "\tcontent_css: '/css/editor.css',\n";
	$return .= "\ttheme_advanced_buttons1 : 'pastetext,pasteword,|,undo,redo,|,formatselect,|,bold,italic,underline,strikethrough,|,numlist,bullist,|,fullscreen',\n";
	$return .= "\ttheme_advanced_buttons2 : 'embed,image,link,unlink,|,justifyleft,justifycenter,justifyright,justifyfull,|,outdent,indent,sub,sup,|,charmap',\n";
	$return .= "\ttheme_advanced_buttons3 : '',\n";
	$return .= "\ttheme_advanced_toolbar_location : 'top',\n";
	$return .= "\ttheme_advanced_toolbar_align : 'left',\n";
	$return .= "\ttheme_advanced_statusbar_location : 'bottom',\n";
	$return .= "\ttheme_advanced_resizing : true,\n";
	$return .= "\ttheme_advanced_blockformats : 'p,h3,h4,h5,h6',\n";
	$return .= "\tinvalid_elements : 'script'\n";
	$return .= "});\n";
	$return .= "function toggleEditorVisual(id) {\n";
	$return .= "\t\ttinyMCE.execCommand('mceAddControl', false, id);\n";
	$return .= "}\n";
	$return .= "function toggleEditorHTML(id) {\n";
	$return .= "\ttinyMCE.execCommand('mceRemoveControl', false, id);\n";
	$return .= "}\n";
	$return .= "</script>\n";
	$return .= "@@@SMARTY:FOOTER:END@@@\n";
	$return .= "<div id=\"tinymce_editor\">\n";
	$return .= "\t<a href=\"javascript:toggleEditorVisual('".$aParams["name"]."2');\">Visual</a> - <a href=\"javascript:toggleEditorHTML('".$aParams["name"]."2');\">HTML</a><br>\n";
	$return .= "\t<textarea id='".$aParams["name"]."2' name='".$aParams["name"]."' class='".$aParams["name"]."_editor' style=\"width: ".$width."px;height: ".($height - 15)."px;\">".$content."</textarea><br>\n";
	$return .= "</div>\n";
	
	return $return;
}