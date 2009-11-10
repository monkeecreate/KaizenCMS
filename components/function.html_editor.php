<?php
function smarty_function_html_editor($aParams, &$smarty)
{
	if(empty($params["width"]))
		$width = "90%";
	else
		$width = $aParams["width"];
		
	if(empty($params["height"]))
		$height = "300";
	else
		$height = $aParams["height"];
	
	$content = stripslashes($aParams["content"]);
	
	$return = "<script type='text/javascript' src='/scripts/tiny_mce/tiny_mce.js'></script>\n";
	$return .= "<script type='text/javascript' src='/scripts/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php'></script>\n";
	$return .= "<script type='text/javascript'>\n";
	$return .= "tinyMCE.init({\n";
	$return .= "\tmode : 'textareas',\n";
	$return .= "\ttheme : 'advanced',\n";
	//$return .= "\tskin : 'o2k7',\n";
	$return .= "\tplugins : 'imagemanager,filemanager,advimage,advlink,paste,table,preview,fullscreen',\n";
	$return .= "\teditor_selector : 'wysiwyg',\n";
	$return .= "\trelative_urls: false,\n";
	$return .= "\twidth: '".$width."',\n";
	$return .= "\theight: '".$height."',\n";
	$return .= "\tcontent_css: '/css/editor.css',\n";
	$return .= "\ttheme_advanced_buttons1 : 'pastetext,pasteword,|,undo,redo,|,formatselect,|,bold,italic,underline,strikethrough,|,numlist,bullist,|,code,fullscreen',\n";
	$return .= "\ttheme_advanced_buttons2 : 'image,link,unlink,|,justifyleft,justifycenter,justifyright,justifyfull,|,outdent,indent,sub,sup,|,charmap',\n";
	$return .= "\ttheme_advanced_buttons3 : '',\n";
	$return .= "\ttheme_advanced_toolbar_location : 'top',\n";
	$return .= "\ttheme_advanced_toolbar_align : 'left',\n";
	$return .= "\ttheme_advanced_statusbar_location : 'bottom',\n";
	$return .= "\ttheme_advanced_resizing : true,\n";
	$return .= "\ttheme_advanced_blockformats : 'p,h2,h3'\n";
	$return .= "});\n";
	$return .= "</script>\n";
	$return .= "<textarea name='".$aParams["name"]."' class='wysiwyg'>".$content."</textarea><br>";
	
	return $return;
}