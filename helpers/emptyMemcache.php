<?php
class emptyMemcache
{
	function get() {
		return false;
	}
	function set() {
		return true;
	}
}