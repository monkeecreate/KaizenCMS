<?php
class Form_bool extends Form_Field
{
	private $_options;
	private $_setting;
	
	/* __constructor */
	public function Form_bool($aSetting) {
		$this->_setting = $aSetting;
		$this->_options = $this->getOptions($aSetting["type"]);
	}
	
	public function html() {
		$sHTML = '<div class="control-group">';
		$sHTML .= '<label class="control-label" for="form-'.urlencode(stripslashes($this->_setting["title"])).'">'.stripslashes($this->_setting["title"]).'</label>';
		$sHTML .= '<div class="controls"><label class="checkbox">';
		$sHTML .= '<input type="checkbox" name="settings['.$this->_setting["tag"].']" id="form-'.urlencode(stripslashes($this->_setting["title"])).'" value="1"';
		if($this->value() == 1)
			$sHTML .= ' checked="checked"';
		$sHTML .= '>';
		if(!empty($this->_setting["text"]))
			$sHTML .= $this->getText($this->_setting["text"]);
		$sHTML .= '</label></div></div>';
		
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