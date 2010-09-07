<?php
class Form_bool extends Form_Field
{
	private $_options;
	private $_setting;
	
	/* __constructor */
	public function __construct($aSetting) {
		$this->_setting = $aSetting;
		$this->_options = $this->getOptions($aSetting["type"]);
	}
	
	public function html() {
		$sHTML = $this->getLabel($this->_setting["title"])."\n";
		$sHTML .= "<input type=\"checkbox\" name=\"settings[".$this->_setting["tag"]."]\" value=\"1\"";
		
		if(!empty($_options["max"]))
			$sHTML .= " maxlength=\"".$_options["max"]."\"";
		
		if($this->value() == 1)
			$sHTML .= " checked=\"checked\"";
		
		$sHTML .= " /><br />\n";
		
		if(!empty($this->_setting["text"]))
			$sHTML .= $this->getText($this->_setting["text"])."\n";
		
		return $sHTML;
	}
	public function value() {
		return htmlspecialchars(stripslashes($this->_setting["value"]));
	}
	public function save($value) {
		if($value == 1)
			return 1;
		else
			return 0;
	}
}