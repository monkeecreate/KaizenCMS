<?php
abstract class Form_Field
{
	abstract protected function html();
	abstract protected function value();
	abstract protected function save($value);
	
	protected function getLabel($sTitle) {
		$sLabel = "<label>".stripslashes($sTitle)."</label>";
		
		return $sLabel;
	}
	protected function getText($sText) {
		$sText = "<span class=\"input-info\">".stripslashes($sText)."</span>";
		
		return $sText;
	}
	protected function getOptions($sOptions) {
		return json_decode($sOptions, true);
	}
}