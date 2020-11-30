<?php

require_once LIB_DIR.'db/DB_Query.php';

class DBD_Structure {
	private $dsn = '';
	private $new = false;
	private $objQuery = null;
	public static $arrConnect = array();

	function __construct($dsn = '', $new = false) {
		$this->setDsn($dsn);
		$this->setNew($new);
		$this->makeQuery();
	}

	public static function setConnection(&$objThis, $dsn = '') {
		$key_str = md5($dsn);

		return DBD_Structure::$arrConnect[$key_str] = $objThis;
	}

	public static function getConnection($dsn = '') {
		$key_str = md5($dsn);
		if (isset(DBD_Structure::$arrConnect[$key_str])) {
			return DBD_Structure::$arrConnect[$key_str];
		}
	}

	public static function singleQuery($dsn = '', $new = false) {
		$objThis = DBD_Structure::getConnection($dsn);
		if (is_null($objThis)) {
			$objThis = DBD_Structure::setConnection(new DBD_Structure($dsn, $new), $dsn);
		}

		return clone $objThis;
	}

	public function setDsn($dsn) {
		$this->dsn = $dsn;
	}

	public function getDsn() {
		return $this->dsn;
	}

	public function setNew($new) {
		$this->new = $new;
	}

	public function getNew() {
		return $this->new;
	}

	public function setObjQuery($objQuery) {
		$this->objQuery = $objQuery;
	}

	public function getObjQuery() {
		return $this->objQuery;
	}

	public function makeQuery() {
		$this->objQuery = DB_Query::singleConnect($this->getDsn(), $this->getNew());
	}

	public function query($sql, $params = array(), $types = null, $result_type = MDB2_PREPARE_RESULT) {
		return $this->getObjQuery()->query($sql, $params, $types, $result_type);
	}

	public function begin() {
		return $this->getObjQuery()->begin();
	}

	public function commit() {
		return $this->getObjQuery()->commit();
	}

	public function rollback() {
		return $this->getObjQuery()->rollback();
	}

	public function getOrMaps($tableName, $flg = false) {
		if (OR_MAPS == true || $tableName == DB_TABLE_HEADER.'system' || $tableName == DB_TABLE_HEADER.'param') {
			require_once DB_DIR.str_replace(DB_TABLE_HEADER, '', $tableName).".php";
			$resParam = call_user_func("orm_".str_replace(DB_TABLE_HEADER, '', $tableName));
		} else {
			$resSystem = $this->query("
				Select
					system_id
				From
					".DB_TABLE_HEADER."system
				Where
					system_table = ?
			", array($tableName));
			if (!is_numeric($resSystem[0]['system_id'])) exit;
			$systemId = $resSystem[0]['system_id'];

			$resParam = $this->query("
				Select
					*
				From
					".DB_TABLE_HEADER."param
				Where
					param_stop_flg = 0 And param_delete_flg = 0 And system_id = ?
				Order By
					system_id, param_id
			", array($systemId));
		}
		return $resParam;
	}

	public function makeOrMaps($tableName, $head) {
		if (OR_MAPS == true) {
			$objDB = DB_Query::singleConnect();
			$res = $this->query("
				Select
					COLUMN_NAME,
					DATA_TYPE
				From
					information_schema.COLUMNS
				Where
					TABLE_SCHEMA = '".$this->getObjQuery()->quote(DB_NAME)."'
				And
					TABLE_NAME = '".$this->getObjQuery()->quote($tableName)."'
			");

			$setArray = '';
			$setArray.= '<?php'."\n".'function orm_'.str_replace(DB_TABLE_HEADER, '', $tableName).'() {'."\n".'return array('."\n";
			foreach ($res as $key => $val) {
				$res[strtolower($key)] = $val;
			}
			foreach ($res as $key => $val) {
				$setArray.= "\t".'"'.$key.'" => array("param_column" => "'.$val['column_name'].'", "param_type" => "'.$this->dataTypeOrMap($val['data_type']).'"),'."\n";
			}
			$setArray.= ');'."\n".'}'."\n".'';

			$fp = fopen(DB_DIR.str_replace(DB_TABLE_HEADER, '', $tableName).".php", "w");
			flock($fp, LOCK_EX);
			fwrite($fp, $setArray);
			chmod(DB_DIR.str_replace(DB_TABLE_HEADER, '', $tableName).".php", 0644);
			flock($fp, LOCK_UN);
		}
	}

	public function rebaseOrMaps() {
		if (OR_MAPS == true) {
			if ($handle = opendir(WEB_APP."file/db")) {
				$fileList = array();
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != "..") {
						array_push($fileList, $file);
					}
				}
				unset($fileList[array_search("system.php", $fileList)]);
				unset($fileList[array_search("param.php", $fileList)]);
				unset($fileList[array_search("_system_table_list.php", $fileList)]);
				foreach ($fileList as $val) {
					unlink(DB_DIR.$val);
				}

				$objDB = DB_Query::singleConnect();
				$resCMS = $this->query("
					Select
						*
					From
						".DB_TABLE_HEADER."system
					Where
						system_stop_flg = 0 And system_delete_flg = 0
					Order By
						system_sort, system_id
				");
				foreach ($resCMS as $val) {
					$this->makeOrMaps($val['system_table'], DB_COLUMN_HEADER);
					if (in_array($val['system_table'].".php", $fileList)) unset($fileList[array_search($val['system_table'].".php", $fileList)]);
				}

				$setArray = '';
				$setArray.= '<?php'."\n".'function _system_table_list() {'."\n".'return array('."\n";
				foreach ($resCMS as $key => $val) {
					$setArray.= "\t".'"'.$val['system_id'].'" => "'.$val['system_table'].'",'."\n";
				}
				$setArray.= ');'."\n".'}'."\n".'';
				$fp = fopen(DB_DIR."_system_table_list.php", "w");
				flock($fp, LOCK_EX);
				fwrite($fp, $setArray);
				chmod(DB_DIR."_system_table_list.php", 0644);
				flock($fp, LOCK_UN);
			}
		}
	}

	public function getSystemTableList() {
		if (OR_MAPS == true) {
			require_once WEB_APP."logic/_db/data/_system_table_list.php";
			$resSystem = call_user_func("_system_table_list");
		} else {
			$tmpSystem = $this->query("
				Select
					*
				From
					".DB_TABLE_HEADER."system
				Where
					system_stop_flg = 0 And system_delete_flg = 0
				Order By
					system_id
			");
			$resSystem = array();
			foreach ($tmpSystem as $val) $resSystem[$val['system_id']] = $val['system_table'];
		}
		return $resSystem;
	}

	public function dataTypeOrMap($dataType) {
		switch ($dataType) {
			case "date":
				return 9;
			case "time":
				return 4;
			case "datetime":
				return 10;
			case "timestamp":
				return 10;
			case "int":
				return 1;
			case "tinyint":
				return 1;
			case "smallint":
				return 1;
			case "midiumint":
				return 1;
			case "bigint":
				return 1;
			case "double":
				return 1;
			case "char":
				return 4;
			case "varchar":
				return 4;
			case "enum":
				return 4;
			case "text":
				return 6;
			case "smalltext":
				return 6;
			case "midiumtext":
				return 6;
			case "longtext":
				return 6;
			case "blob":
				return 12;
			case "tinyblob":
				return 12;
			case "mediumblob":
				return 12;
			case "longblob":
				return 12;
		}
		return 4;
	}

	public function lastInsertId($tableName, $column = "") {
		if ($column == "") $column = DB_COLUMN_HEADER.'id';
		return $this->getObjQuery()->con->lastInsertId($tableName, $column);
	}

	public function quote($str, $type = null, $side = false) {
		return $this->getObjQuery()->con->quote($str, $type, $side);
	}

	public function escapePattern($str) {
		return $this->getObjQuery()->con->escapePattern($str);
	}
}

?>