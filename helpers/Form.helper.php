<?php
class Form
{
	public function Form($aSetting) {
		$sType = preg_replace("/(\[.*)/", "", $aSetting["type"]);
		
		include("Form/Form_Field.php");
		include("Form/".$sType.".php");
		$sType = "Form_".$sType;
		
		$this->setting = new $sType($aSetting);
	}
}