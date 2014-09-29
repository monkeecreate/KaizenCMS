<?php
abstract class Form_Field {
	abstract protected function html();
	abstract protected function value();
	abstract protected function save($value);
	
	protected function getLabel($sTitle) {
		$sLabel = "<label>".stripslashes($sTitle)."</label>";
		
		return $sLabel;
	}
	protected function getText($sText) {
		$sText = stripslashes($sText);
		
		return $sText;
	}
	protected function getOptions($sType) {
		preg_match_all("/\[([^=]+)=([^\]]+)\]/", $sType, $aValues, PREG_SET_ORDER);
		
		$aOptions = array();
		foreach($aValues as $aValue)
			$aOptions[$aValue[1]] = $aValue[2];
		
		return $aOptions;
	}
}