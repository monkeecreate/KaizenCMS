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
		$sHTML = '<div class="control-group">';
		$sHTML .= '<label class="control-label" for="form-'.urlencode(stripslashes($this->_setting["title"])).'">'.stripslashes($this->_setting["title"]).'</label>';
		$sHTML .= '<div class="controls">';
		$sHTML .= '<textarea name="settings['.$this->_setting["tag"].']" id="form-'.urlencode(stripslashes($this->_setting["title"])).'" class="span12';
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
			$sHTML .= ']"';
		}
		$sHTML .= ' style="height: 115px;">'.$this->value(false).'</textarea>';
		if(!empty($this->_setting["text"]))
			$sHTML .= '<p class="help-block">'.$this->getText($this->_setting["text"]).'</p>';
		$sHTML .= '</div></div>';

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