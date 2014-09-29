<?php
class Form
{
	public function Form($aSetting) {
		$sType = preg_replace("/(\[.*)/", "", $aSetting["type"]);

		if(!class_exists("Form_Field"))
			include(APP."helpers/Form/Form_Field.php");

		if(!is_file(APP."helpers/Form/".$sType.".php"))
			$sType = "text";

		if(!class_exists("Form_".$sType))
			include(APP."helpers/Form/".$sType.".php");

		$sType = "Form_".$sType;

		$this->setting = new $sType($aSetting);
	}
}
