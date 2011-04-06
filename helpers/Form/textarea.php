<?php
class Form_textarea extends Form_Field
{
	private $_options;
	private $_setting;
	
	/* __constructor */
	public function Form_textarea($aSetting) {
		$this->_setting = $aSetting;
		$this->_options = $this->getOptions($aSetting["type"]);
	}
	
	public function html() {
		$sHTML = $this->getLabel($this->_setting["title"])."<br />\n";
		$sHTML .= "<textarea name=\"settings[".$this->_setting["tag"]."]\"";
		$sHTML .= ">".$this->value(false)."</textarea><br />\n";
		
		if(!empty($this->_setting["text"]))
			$sHTML .= $this->getText($this->_setting["text"])."\n";
		
		return $sHTML;
	}
	public function value($sNewLine = true) {
		$sValue = stripslashes($this->_setting["value"]);
		
		if($sNewLine == true) {
			return nl2br($sValue);
		} else {
			return $sValue;
		}
	}
	public function save($value) {
		return addslashes(trim(stripslashes($value)));
	}
}