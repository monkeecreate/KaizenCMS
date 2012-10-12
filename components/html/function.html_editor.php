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
	$return .= "<script>if(typeof(tinymce) === 'undefined') { document.write('<script src=\"/js/tiny_mce/jquery.tinymce.js\"><\/script><script src=\"/js/tiny_mce/tiny_mce.js\"><\/script>'); }</script>\n";
	$return .= "<script type='text/javascript' src='/js/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php'></script>\n";
	$return .= "<script type='text/javascript'>\n";
	$return .= "tinyMCE.init({\n";

	if($_COOKIE[$aParams["name"]."_editor"] == "html")
		$return .= "\tmode : 'none',\n";
	else
		$return .= "\tmode : 'textareas',\n";
	$return .= "\ttheme : 'advanced',\n";
	$return .= "\tskin : 'default',\n";
	$return .= "\tplugins : 'safari,contextmenu,advlist,embed,imagemanager,filemanager,advimage,advlink,paste,table,preview,fullscreen,searchreplace,spellchecker,autolink,tabfocus,inlinepopups',\n";
	$return .= "\teditor_selector : '".$aParams["name"]."_editor',\n";
	$return .= "\textended_valid_elements : 'object[width|height|classid|codebase]"
		.",param[name|value],embed[src|type|width|height|flashvars|wmode]"
		.",iframe[align<bottom?left?middle?right?top|class|frameborder|height|id|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style|title|width]"
		."',\n";
	$return .= "\trelative_urls: false,\n";
	$return .= "\twidth: '".$width."',\n";
	$return .= "\theight: '".$height."',\n";
	$return .= "\tcontent_css: '/css/editor.css',\n";

	if($aParams["theme"] == "simple") {
		$return .= "\ttheme_advanced_buttons1 : 'pastetext,pasteword,|,bold,italic,underline,strikethrough,|,numlist,bullist,|,link,unlink,|,undo,redo',\n";
		$return .= "\ttheme_advanced_buttons2 : '',\n";
		$return .= "\ttheme_advanced_buttons3 : '',\n";
	} else {
		$return .= "\ttheme_advanced_buttons1 : 'pastetext,pasteword,|,formatselect,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,numlist,bullist',\n";
		$return .= "\ttheme_advanced_buttons2 : 'embed,image,|,link,unlink,|,outdent,indent,blockquote,sub,sup,|,search,replace,|,undo,redo,charmap,code,fullscreen',\n";
		$return .= "\ttheme_advanced_buttons3 : 'tablecontrols',\n";
	}
	$return .= "\ttheme_advanced_toolbar_location : 'top',\n";
	$return .= "\ttheme_advanced_toolbar_align : 'left',\n";
	$return .= "\ttheme_advanced_statusbar_location : 'bottom',\n";
	$return .= "\ttheme_advanced_path : false,\n";
	$return .= "\ttheme_advanced_resizing : true,\n";
	$return .= "\ttheme_advanced_blockformats : 'p,h3,h4,h5,h6',\n";
	$return .= "\tinvalid_elements : 'script',\n";
	$return .= "\ttab_focus : ':prev,:next'\n";
	$return .= "});\n";
	$return .= "function toggleEditorVisual(id) {\n";
	$return .= "\t\ttinyMCE.execCommand('mceAddControl', false, id);\n";
	$return .= "\t\t$('.tinymce-toggle .html_tab').removeClass('active');\n";
	$return .= "\t\t$('.tinymce-toggle .visual_tab').addClass('active');\n";
	$return .= "}\n";
	$return .= "function toggleEditorHTML(id) {\n";
	$return .= "\t\ttinyMCE.execCommand('mceRemoveControl', false, id);\n";
	$return .= "\t\t$('.tinymce-toggle .visual_tab').removeClass('active');\n";
	$return .= "\t\t$('.tinymce-toggle .html_tab').addClass('active');\n";
	$return .= "}\n";
	$return .= "</script>\n";
	$return .= "@@@SMARTY:FOOTER:END@@@\n";
	$return .= "\t<label class=\"control-label pull-left\" for=\"".$aParams["name"]."_editor\">".$aParams["label"]."</label>\n";
	$return .= "\t<ul class=\"nav nav-tabs pull-right tinymce-toggle\" style=\"margin-bottom: 0; border-bottom: 0;\"><li class=\"visual_tab active\"><a href=\"javascript:toggleEditorVisual('".$aParams["name"]."_editor');\">Visual</a></li><li class=\"html_tab\"><a href=\"javascript:toggleEditorHTML('".$aParams["name"]."_editor');\">HTML</a></li></ul>\n";
	$return .= "\t<div class=\"controls\">\n";
	$return .= "<div id=\"tinymce_editor_".$aParams["name"]."\" class=\"tinymce_editor\" style=\"clear: both;\">\n";
	$return .= "\t<textarea id='".$aParams["name"]."_editor' name='".$aParams["name"]."' class='".$aParams["name"]."_editor full ".$aParams["class"]."' style=\"width: 98.5%; height: ".($height - 15)."px;\">".$content."</textarea><br>\n";
	$return .= "</div></div>\n";

	return $return;
}