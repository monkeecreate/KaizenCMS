<?php
function clean_html($sText) {
	return htmlspecialchars(stripslashes($sText));
}
