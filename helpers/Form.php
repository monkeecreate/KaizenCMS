<?php
class Form
{
	public function Form($aSetting) {
		$sType = $aSetting["type"];
		$site_root = dirname(__FILE__);
		
		if(!class_exists("Form_Field"))
			include($site_root."/Form/Form_Field.php");
		
		if(!is_file($site_root."/Form/".$sType.".php"))
			$sType = "text";
		
		if(!class_exists("Form_".$sType))
			include($site_root."/Form/".$sType.".php");
			
		$sType = "Form_".$sType;
		
		$this->setting = new $sType($aSetting);
	}
}