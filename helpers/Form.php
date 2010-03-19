<?php
class Form
{
	public function Form($aSetting) {
		$sType = preg_replace("/(\[.*)/", "", $aSetting["type"]);
		
		if(!class_exists("Form_Field"))
			include("Form/Form_Field.php");
			
		if(!is_file("Form/".$sType.".php"))
			$sType = "text";
		
		if(!class_exists("Form_".$sType))
			include("Form/".$sType.".php");
			
		$sType = "Form_".$sType;
		
		$this->setting = new $sType($aSetting);
	}
}