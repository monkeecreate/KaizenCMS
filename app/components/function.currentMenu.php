<?php
function currentMenu($aVar) {
  global $menu;

	if(in_array($menu, $aVar))
		return "current";
}
