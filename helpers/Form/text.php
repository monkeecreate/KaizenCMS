<?php
class Form_text extends Form_Field
{
	private $_options;
	private $_setting;
	
	/* __constructor */
	public function Form_text($aSetting) {
		$this->_setting = $aSetting;
		$this->_options = $this->getOptions($aSetting["options"]);
	}
	
	public function html() {
		$sHTML = $this->getLabel($this->_setting["title"])."<br />\n";
		$sHTML .= "<input type=\"text\" name=\"settings[".$this->_setting["tag"]."]\" value=\"".$this->value()."\"";
		
		if(!empty($this->_options["max"]))
			$sHTML .= " maxlength=\"".$this->_options["max"]."\"";
		
		$sHTML .= " /><br />\n";
		
		if(!empty($this->_setting["text"]))
			$sHTML .= $this->getText($this->_setting["text"])."\n";
		
		return $sHTML;
	}
	public function value() {
		return htmlspecialchars(stripslashes($this->_setting["value"]));
	}
	public function save($value) {
		if(isset($this->_options["required"]))
			throw new Exception("The field '".$this->_setting["title"]."' is required and should not be empty.");
		
		return addslashes(trim($value));
	}
}