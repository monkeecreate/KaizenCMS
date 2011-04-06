<?php
class Form_text extends Form_Field
{
	private $_options;
	private $_setting;
	
	/* __constructor */
	public function Form_text($aSetting) {
		$this->_setting = $aSetting;
		$this->_options = $this->getOptions($aSetting["type"]);
	}
	
	public function html() {
		$sHTML = $this->getLabel($this->_setting["title"])."<br />\n";
		$sHTML .= "<input type=\"text\" name=\"settings[".$this->_setting["tag"]."]\" value=\"".htmlspecialchars(stripslashes($this->value()))."\"";
		
		if(!empty($_options["max"]))
			$sHTML .= " maxlength=\"".$_options["max"]."\"";
		
		$sHTML .= " /><br />\n";
		
		if(!empty($this->_setting["text"]))
			$sHTML .= $this->getText($this->_setting["text"])."\n";
		
		return $sHTML;
	}
	public function value() {
		return htmlspecialchars(stripslashes($this->_setting["value"]));
	}
	public function save($value) {
		return addslashes(trim($value));
	}
}