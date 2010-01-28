<?php
function smarty_function_colorPicker($aParams, &$oSmarty)
{
	$html = "<link rel=\"stylesheet\" media=\"screen\" type=\"text/css\" href=\"/scripts/jquery-colorpicker/css/colorpicker.css\" />\n";
	$html .= "<script type=\"text/javascript\" src=\"/scripts/jquery-colorpicker/colorpicker.js\"></script>\n";
	$html .= "<style type=\"text/css\">\n";
	$html .= ".colorSelector {\n";
	$html .= "	position: relative;\n";
	$html .= "	width: 36px;\n";
	$html .= "	height: 36px;\n";
	$html .= "	background: url('/scripts/jquery-colorpicker/images/select.png');\n";
	$html .= "}\n";
	$html .= ".colorSelector div {\n";
	$html .= "	position: absolute;\n";
	$html .= "	top: 3px;\n";
	$html .= "	left: 3px;\n";
	$html .= "	width: 30px;\n";
	$html .= "	height: 30px;\n";
	$html .= "	background: url('/scripts/jquery-colorpicker/images/select.png') center;\n";
	$html .= "}\n";
	$html .= "</style>\n";
	$html .= "<div class=\"colorSelector\"><div id=\"selector-".$aParams["name"]."\" style=\"background-color:".$aParams["color"].";\"></div></div>\n";
	$html .= "<input id=\"input-".$aParams["name"]."\" type=\"hidden\" name=\"".$aParams["name"]."\" value=\"#FF0000\"><br>\n";
	$html .= "<script type=\"text/javascript\">\n";
	$html .= "$('#selector-".$aParams["name"]."').ColorPicker({\n";
	$html .= "	color: '".$aParams["color"]."',\n";
	$html .= "	onShow: function (colpkr) {\n";
	$html .= "		$(colpkr).fadeIn(500);\n";
	$html .= "		return false;\n";
	$html .= "	},\n";
	$html .= "	onHide: function (colpkr) {\n";
	$html .= "		$(colpkr).fadeOut(500);\n";
	$html .= "		return false;\n";
	$html .= "	},\n";
	$html .= "	onChange: function (hsb, hex, rgb, el) {\n";
	$html .= "		$('#input-".$aParams["name"]."').val(\"#\"+hex);//Updates text\n";
	$html .= "		$('#selector-".$aParams["name"]."').css('backgroundColor', '#' + hex);//Updates div background to show the user\n";
	$html .= "	}\n";
	$html .= "})\n";
	$html .= "</script>\n";

	echo $html;
}