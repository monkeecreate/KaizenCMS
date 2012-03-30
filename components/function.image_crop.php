<?php
function smarty_function_image_crop($aParams, &$oSmarty) {
	if($aParams["load"] == "cropper") {
		$html = "<script src=\"/js/jcrop/jquery.Jcrop.min.js\"></script>\n";
		$html .= "<link rel=\"stylesheet\" href=\"/js/jcrop/jquery.Jcrop.css\" type=\"text/css\" />\n";
		$html .= "<script language=\"Javascript\">\n";
		$html .= "$(document).ready(function(){\n";
		$html .= "	$('#".$aParams["img"]."').Jcrop({\n";
		$html .= "		onChange: showCoords,\n";
		$html .= "		onSelect: showCoords,\n";
		$html .= "		minSize: [ ".$aParams["rx"].", ".$aParams["ry"]." ],\n";
		$html .= "		aspectRatio: ".$aParams["rx"]."/".$aParams["ry"]."\n";
		$html .= "		,boxWidth: 600\n";
		$html .= "		,setSelect: [".$aParams["values"]["photo_x1"]
			.", ".$aParams["values"]["photo_y1"]
			.", ".$aParams["values"]["photo_x2"]
			.", ".$aParams["values"]["photo_y2"]
			." ]\n";
		$html .= "	});\n";
		$html .= "});\n";
		$html .= "// Our simple event handler, called from onChange and onSelect\n";
		$html .= "// event handlers, as per the Jcrop invocation above\n";
		$html .= "function showCoords(c)\n";
		$html .= "{\n";
		$html .= "	$('#x1').val(c.x);\n";
		$html .= "	$('#y1').val(c.y);\n";
		$html .= "	$('#x2').val(c.x2);\n";
		$html .= "	$('#y2').val(c.y2);\n";
		$html .= "	$('#width').val(c.w);\n";
		$html .= "	$('#height').val(c.h);\n";
		$html .= "	if (parseInt(c.w) > 0)\n";
		$html .= "	{\n";
		$html .= "		var rx = ".$aParams["previewWidth"]." / c.w;\n";
		$html .= "		var ry = ".$aParams["previewHeight"]." / c.h;\n";
		$html .= "		var width = $('#".$aParams["img"]."').width();\n";
		$html .= "		var height = $('#".$aParams["img"]."').height();\n";
		$html .= "		$('#preview').css({\n";
		$html .= "			width: Math.round(rx * width) + 'px',\n";
		$html .= "			height: Math.round(ry * height) + 'px',\n";
		$html .= "			marginLeft: '-' + Math.round(rx * c.x) + 'px',\n";
		$html .= "			marginTop: '-' + Math.round(ry * c.y) + 'px'\n";
		$html .= "		});\n";
		$html .= "	}\n";
		$html .= "};\n";
		$html .= "</script>\n";
	} elseif($aParams["load"] == "form") {
		$html = "<input type=\"hidden\" name=\"x1\" id=\"x1\" />\r";
		$html .= "<input type=\"hidden\" name=\"y1\" id=\"y1\" />\r";
		$html .= "<input type=\"hidden\" name=\"x2\" id=\"x2\" />\r";
		$html .= "<input type=\"hidden\" name=\"y2\" id=\"y2\" />\r";
		$html .= "<input type=\"hidden\" name=\"width\" id=\"width\" />\r";
		$html .= "<input type=\"hidden\" name=\"height\" id=\"height\" />\r";
	}
	
	return $html;
}