<?php
class Form_file extends Form_Field
{
	private $_options;
	private $_setting;
	
	/* __constructor */
	public function Form_file($aSetting) {
		$this->_setting = $aSetting;
		$this->_options = $this->getOptions($aSetting["type"]);
	}
	
	public function html() {
		$sHTML = $this->getLabel($this->_setting["title"])."<br />\n";
		$sHTML .= "<input type=\"file\" name=\"settings[".$this->_setting["tag"]."]\"";
		
		$sHTML .= " /><br />\n";
		
		if(!empty($this->_setting["text"]))
			$sHTML .= $this->getText($this->_setting["text"])."\n";
		
		return $sHTML;
	}
	public function value() {
		return $this->_setting["value"];
	}
	public function save($value) {
		$upload_dir = $this->settings->rootPublic."uploads/settings/";
		$file_ext = pathinfo($_FILES["settings"]["name"][$this->_setting["tag"]], PATHINFO_EXTENSION);
		$upload_file = $this->_setting["tag"].".".strtolower($file_ext);

		if(move_uploaded_file($_FILES["settings"]["tmp_name"][$this->_setting["tag"]], $upload_dir.$upload_file)) {
			return $upload_file;
		}		
	}
}