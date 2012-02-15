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
		$sHTML = '<div class="control-group">';
		$sHTML .= '<label class="control-label" for="form-'.urlencode(stripslashes($this->_setting["title"])).'">'.stripslashes($this->_setting["title"]).'</label>';
		$sHTML .= '<div class="controls">';
		$sHTML .= '<input type="text" name="settings['.$this->_setting["tag"].']" id="form-'.urlencode(stripslashes($this->_setting["title"])).'" value="'.$this->value().'" class="span12';
		if(!empty($this->_setting["validation"])) {
			$sHTML .= ' validate[';
			$i = 0;
			foreach($this->_setting["validation"] as $sValidation) {
				if($sValidation === "required")
					$sHTML .= $sValidation;
				else
					$sHTML .= 'custom['.$sValidation.']';

				if($i+1 != count($this->_setting["validation"]))
					$sHTML .= ',';

				$i++;
			}
			$sHTML .= ']';
		}
		$sHTML .= '">';
		if(!empty($this->_setting["text"]))
			$sHTML .= '<p class="help-block">'.$this->getText($this->_setting["text"]).'</p>';
		$sHTML .= '</div></div>';
		
		return $sHTML;
	}
	public function value($sSpecialChars = true) {
		return htmlspecialchars(stripslashes($this->_setting["value"]));
	}
	public function save($value) {
		return addslashes(trim(stripslashes($value)));
	}
}