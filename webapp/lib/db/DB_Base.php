<?php

//require_once LIB_DIR.'dbt/DBT_Base.php';

class DB_Base {
	public $con;
	public static $arrConnect = array();
	public $dbEncode = array('SJIS' => 'sjis', 'EUC' => 'ujis', 'UTF-8' => 'utf8');
	public $maxLimitNum = 1000000;
	public $paramTypeLink = array(
		"1" => "int_a",
		"2" => "int_b",
		"15" => "int_c",
		"17" => "int_d",
		"3" => "double_a",
		"4" => "string_a",
		"5" => "string_b",
		"16" => "string_c",
		"18" => "string_d",
		"20" => "string_e",
		"6" => "text_a",
		"7" => "text_b",
		"8" => "text_c",
		"14" => "text_d",
		"9" => "date_a",
		"10" => "datetime_a",
		"11" => "file_a",
		"12" => "file_b",
		"13" => "int_key_a",
		"19" => "int_key_b",
	);
	public $dbTracking;

	public function __construct($dsn = '', $new = false) {
		set_include_path(MODULE_DIR.'PEAR/');
		require_once MODULE_DIR.'PEAR/MDB2.php';

		if ($dsn == '') {
			$dsn = array(
				'phptype'  => DB_TYPE,
				'username' => DB_USERNAME,
				'password' => DB_PASSWORD,
				'protocol' => 'tcp',
				'hostspec' => DB_HOST,
				'port'	 => DB_PORT,
				'database' => DB_NAME,
			);
		} elseif ($dsn == 'SLAVE') {
			$dsn = array(
				'phptype'  => DB_TYPE,
				'username' => SDB_USERNAME,
				'password' => SDB_PASSWORD,
				'protocol' => 'tcp',
				'hostspec' => SDB_HOST,
				'port'	 => SDB_PORT,
				'database' => SDB_NAME,
			);
		}

		// オプション
		$options = array(
			// 持続的接続
			'persistent' => DB_FULL_CONNECT,
			// Debugモード
			'debug' => DEBUG_MODE,
			// バッファリング true にするとメモリが解放されない。
			// 連続クエリ実行時に問題が生じる。
			'result_buffering' => false,
			// 空白文字オプション
			'portability' => MDB2_PORTABILITY_ALL ^ MDB2_PORTABILITY_EMPTY_TO_NULL,
		);

		if ($new) {
			$this->con = MDB2::connect($dsn, $options);
		} else {
			$this->con = MDB2::singleton($dsn, $options);
		}
		if (!MDB2::isError($this->con)) {
			$this->con->setCharset($this->dbEncode[DB_ENC]);
			$this->con->setFetchMode(MDB2_FETCHMODE_ASSOC);
		} else {
			die($this->con->getMessage());
		}
	}

	public function query($sql, $params = array(), $types = null, $result_type = MDB2_PREPARE_RESULT) {
		$this->showDebug("<BR>".$sql."<BR>&gt; ".print_r($params, true)."<BR><BR>");
/*
		DBT_Base::$lastSql = $sql;
		DBT_Base::$lastParam = $params;
		DBT_Base::$connect = $this->con;
*/

		$sth = $this->con->prepare($sql, $types, $result_type);
		if (@PEAR::isError($sth)){
			if (DEBUG_MODE == 0) {
				if (ini_get('display_errors') == '1') {
					echo $sth->getDebugInfo();
				} else {
					cmsErrorMail($sth->getDebugInfo());
					viewErrorPage();
				}
			} else {
				echo $sth->getDebugInfo();
			}
			exit();
		}
		$res = $sth->execute($params);
		if (MDB2::isError($res)) {
			if (DEBUG_MODE == 0) {
				if (ini_get('display_errors') == '1') {
					echo $res->getDebugInfo();
				} else {
					cmsErrorMail($res->getDebugInfo());
					viewErrorPage();
				}
			} else {
				echo $res->getDebugInfo();
			}
			exit();
		}
		if ($result_type === MDB2_PREPARE_RESULT) {
			$result = $res->fetchAll();
			$sth->free();
			return $result;
		}
		$sth->free();

		return $res;
	}

	public static function setConnection(&$objThis, $dsn = '') {
		$key_str = md5($dsn);

		return DB_Base::$arrConnect[$key_str] = $objThis;
	}

	public static function getConnection($dsn = '') {
		$key_str = md5($dsn);
		if (isset(DB_Base::$arrConnect[$key_str])) {
			return DB_Base::$arrConnect[$key_str];
		}
	}

	public static function singleConnect($dsn = '', $new = false) {
		$objThis = DB_Base::getConnection($dsn);
		if (is_null($objThis)) {
			$objThis = DB_Base::setConnection(new DB_Base($dsn, $new), $dsn);
		}

		return clone $objThis;
	}

	public function exception($res, $sql) {
		if (DEBUG_MODE == 0) {
			require_once LIB_DIR."errormail.php";
			sendMail(ERR_MAIL, $DBErrorSub, $DBErrorCon, ERR_MAIL, ERR_MAIL_TITLE);
		} else {
			echo "exceptionDB:".$sql;
		}
	}

	public function showDebug($var) {
		if (DEBUG_MODE == 1 || defined('DEBUG_START')) {
			echo "\n<br>\n".$var."\n<br>\n";
		}
	}

	public function lastInsertId($table) {
		return $this->con->lastInsertId($table, $table.'_id');
	}
}

?>