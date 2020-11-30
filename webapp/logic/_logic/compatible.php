<?php

require_once MODULE_DIR.'hash/blowfish5.3.6.php';

if (!function_exists('password_hash')) {
	function password_hash($str, $cost) {
		return blowfish($str, $cost);
	}

	function password_verify($str, $hash) {
		if (crypt($str, $hash) == $hash) {
			return true;
		} else {
			return false;
		}
	}
}

if (!defined('PASSWORD_DEFAULT')) define('PASSWORD_DEFAULT', 4);

