<?php
function smarty_function_image_crop($aParams, &$oSmarty)
{
	if($aParams["load"] == "cropper")
	{
		$html = "<link href=\"/css/cropper.css\" media=\"screen\" rel=\"stylesheet\" type=\"text/css\" />\n";
		$html = "<script type=\"text/javascript\" src=\"/scripts/prototype.js\"></script>\n";
		$html .= "<script type=\"text/javascript\" src=\"/scripts/scriptaculous.js?load=builder,dragdrop\"></script>\n";
		//$html .= "<script type=\"text/javascript\" src=\"/scripts/cropper.js\"></script>\n";
		$html .= "<script src=\"http://www.defusion.org.uk/demos/060519/lib/cropper.js\" type=\"text/javascript\"></script>";
		if($aParams["preview"] == "true")
		{
			$html .= "<script type=\"text/javascript\">\n";
			$html .= "function onEndCrop( coords, dimensions ) {\n";
			$html .= "\t$( 'x1' ).value = coords.x1;\n";
			$html .= "\t$( 'y1' ).value = coords.y1;\n";
			$html .= "\t$( 'x2' ).value = coords.x2;\n";
			$html .= "\t$( 'y2' ).value = coords.y2;\n";
			$html .= "\t$( 'width' ).value = dimensions.width;\n";
			$html .= "\t$( 'height' ).value = dimensions.height;\n";
			$html .= "}\n";
			$html .= "Event.observe(\n";
			$html .= "\twindow, 'load', function()\n";
			$html .= "\t{\n";
			$html .= "\t\tmycropper = new Cropper.ImgWithPreview(\n";
			$html .= "\t\t\t'".$aParams["img"]."',\n";
			$html .= "\t\t\t{\n";
			$html .= "\t\t\t\tpreviewWrap: 'croppreview',\n";
			$html .= "\t\t\t\tminWidth: ".$aParams["minw"].",\n";
			$html .= "\t\t\t\tminHeight: ".$aParams["minh"].",\n";
			$html .= "\t\t\t\tratioDim:{\n";
			$html .= "\t\t\t\t\tx: ".$aParams["rx"].",\n";
			$html .= "\t\t\t\t\ty: ".$aParams["ry"]."\n";
			$html .= "\t\t\t\t},\n";
			$html .= "\t\t\t\tonEndCrop: onEndCrop\n";
			$html .= "\t\t\t}\n";
			$html .= "\t\t);\n";
			if(!empty($aParams["values"]) && $aParams["values"]["x2"] > 0)
			{
				$html .= "mycropper.setAreaCoords( { x1: ".$aParams["values"]["x1"].", y1: ".$aParams["values"]["y1"].", x2: ".$aParams["values"]["x2"].", y2: ".$aParams["values"]["y2"]." } );\n";
				$html .= "mycropper.drawArea();\n";
			}
			$html .= "\t}\n";
			$html .= ");\n";
			$html .= "</script>\n";
		}
		else
		{
			$html .= "<script type=\"text/javascript\">\n";
			$html .= "function onEndCrop( coords, dimensions ) {\n";
			$html .= "\t$( 'x1' ).value = coords.x1;\n";
			$html .= "\t$( 'y1' ).value = coords.y1;\n";
			$html .= "\t$( 'x2' ).value = coords.x2;\n";
			$html .= "\t$( 'y2' ).value = coords.y2;\n";
			$html .= "\t$( 'width' ).value = dimensions.width;\n";
			$html .= "\t$( 'height' ).value = dimensions.height;\n";
			$html .= "}\n";
			$html .= "Event.observe( window, 'load', function() {\n";
			$html .= "Event.observe( window, 'load', function() {\n";
			$html .= "\tmycropper = new Cropper.Img(\n";
			$html .= "\t\t'".$aParams["img"]."',\n";
			$html .= "\t\t{\n";
			$html .= "\t\t\tminWidth: ".$aParams["minw"].",\n";
			$html .= "\t\t\tminHeight: ".$aParams["minh"].",\n";
			$html .= "\t\t\tratioDim:{\n";
			$html .= "\t\t\t\tx: ".$aParams["rx"].",\n";
			$html .= "\t\t\t\ty: ".$aParams["ry"]."\n";
			$html .= "\t\t\t},\n";
			$html .= "\t\t\tonEndCrop: onEndCrop } );\n";
			if(!empty($aParams["values"]))
			{
				$html .= "mycropper.setAreaCoords( { x1: ".$aParams["values"]["x1"].", y1: ".$aParams["values"]["y1"].", x2: ".$aParams["values"]["x2"].", y2: ".$aParams["values"]["y2"]." } );\n";
				$html .= "mycropper.drawArea();\n";
			}
			$html .= "\t\t}\n";
			$html .= "\t);\n";
			$html .= "</script>\n";
		}
	}
	elseif($aParams["load"] == "form")
	{
		$html = "<input type=\"hidden\" name=\"x1\" id=\"x1\" />\r";
		$html .= "<input type=\"hidden\" name=\"y1\" id=\"y1\" />\r";
		$html .= "<input type=\"hidden\" name=\"x2\" id=\"x2\" />\r";
		$html .= "<input type=\"hidden\" name=\"y2\" id=\"y2\" />\r";
		$html .= "<input type=\"hidden\" name=\"width\" id=\"width\" />\r";
		$html .= "<input type=\"hidden\" name=\"height\" id=\"height\" />\r";
	}
	
	return $html;
}