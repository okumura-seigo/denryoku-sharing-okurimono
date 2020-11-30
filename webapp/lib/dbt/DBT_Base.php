<?php

class DBT_Base {
	public static $lastSql = '';
	public static $lastParam = array();
	public static $connect;

	public static function errorMessage() {
		echo serialize($DBT_Base::$connect);
		return DBT_Base::$connect->getMessage();
	}
}

?>