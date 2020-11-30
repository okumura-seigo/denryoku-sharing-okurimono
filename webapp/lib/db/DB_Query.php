<?php

require_once LIB_DIR.'db/DB_Base.php';

class DB_Query extends DB_Base {

	function __construct($dsn = '', $new = false) {
		parent::__construct($dsn, $new);
	}
	
	public static function setConnection(&$objThis, $dsn = '') {
		$key_str = md5($dsn);

		return DB_Query::$arrConnect[$key_str] = $objThis;
	}

	public static function getConnection($dsn = '') {
		$key_str = md5($dsn);
		if (isset(DB_Query::$arrConnect[$key_str])) {
			return DB_Query::$arrConnect[$key_str];
		}
	}
	
	public static function singleConnect($dsn = '', $new = false) {
		$objThis = DB_Query::getConnection($dsn);
		if (is_null($objThis)) {
			$DBQuery = new DB_Query($dsn, $new);
			$objThis = DB_Query::setConnection($DBQuery, $dsn);
		}

		return clone $objThis;
	}
	
	
	public function begin() {
		$res = $this->con->beginTransaction();
		return $res;
	}
	
	public function commit() {
		$res = $this->con->commit();
		return $res;
	}
	
	public function rollback() {
		$res = $this->con->rollback();
		return $res;
	}
	
	public function escapePattern($str) {
		return $this->con->escapePattern($str);
	}
	
	public function quote($str, $type = null, $side = false) {
		return $this->con->quote($str, $type, $side);
	}
	
	public function find($table, $where = "", $order = "", $limit = "", $offset = "", $params = array(), $types = null) {
		if (!empty($where)) $where = " Where ".$where;
		if (!empty($order)) $order = " Order By ".$order;
		if (is_numeric($limit)) $limit = " Limit ".$limit;
		if (is_numeric($offset)) $offset = " Offset ".$offset;
		
		$res = $this->query("
			Select
				*
			From
				$table
			$where
			$order
			$limit
			$offset
		", $params, $types);
		
		return $res;
	}
	
	public function insert($table, $data, $duplicate = null, $params = array(), $types = null) {
		$s_sql = "Insert Into ".$table." ";
		$f_sql = array();
		$v_sql = array();
		foreach ($data as $key => $val) {
			array_push($f_sql, $key);
			array_push($v_sql, ":".$key);
		}
		$sql = $s_sql."(".implode(", ",$f_sql).") Values (".implode(", ",$v_sql).")";
		if (!is_null($duplicate) && is_array($duplicate)) $sql.= " On Duplicate Key Update ".implode(", ", $duplicate)." ";
		$sql.= ";";
		$res = $this->query($sql, array_merge($data, $params), $types, MDB2_PREPARE_MANIP);
		if ($res === false) { $this->exception($res, $sql); }
		
		return $res;
	}
	
	public function update($table, $data, $where, $params = array(), $types = null) {
		$s_sql = "Update ".$table." Set ";
		$v_sql = array();
		foreach ($data as $key => $val) {
			array_push($v_sql, $key." = :".$key);
		}
		if (is_array($where)) $where = implode(" And ", $where);
		if (strpos($where, "?") !== false) {
			$expWhere = explode("?", $where);
			$tmpWhere = "";
			foreach ($expWhere as $key => $val) {
				if (count($expWhere) - 1 > $key) {
					$tmpWhere.= $val.":dbd".$key;
					$params['dbd'.$key] = $params[$key];
					unset($params[$key]);
				} else {
					$tmpWhere.= $val;
				}
			}
			$where = $tmpWhere;
		}
		$sql = $s_sql.implode(", ",$v_sql)." Where ".$where.";";
		$res = $this->query($sql, array_merge($data, $params), $types, MDB2_PREPARE_MANIP);
		if ($res === false) { $this->exception($res, $sql); }
		
		return $res;
	}
	
	public function delete($table, $where, $params = array(), $types = null) {
		if (is_array($where)) $where = implode(" And ", $where);
		$sql = "Delete From ".$table." Where ".$where.";";
		$res = $this->query($sql, $params, $types, MDB2_PREPARE_MANIP);
		if ($res === false) { $this->exception($res, $sql); }
		
		return $res;
	}

	public function insertSerial($table, $data, $duplicate = null, $params = array(), $types = null) {
		$objDB = DB_Query::singleConnect();
		$idColumn = getIdColumn($table);

		$objDB->begin();
		$res = $this->query("
			Select
				*
			From
				serial
			Where
				name = '".optSql($idColumn)."'
		");
		$data[$idColumn] = $res[0]['number'];

		$s_sql = "Insert Into ".$table." ";
		$f_sql = array();
		$v_sql = array();
		foreach ($data as $key => $val) {
			array_push($f_sql, $key);
			array_push($v_sql, ":".$key);
		}
		$sql = $s_sql."(".implode(", ",$f_sql).") Values (".implode(", ",$v_sql).")";
		if (!is_null($duplicate) && is_array($duplicate)) $sql.= " On Duplicate Key Update ".implode(", ", $duplicate)." ";
		$sql.= ";";

		$res = $this->query($sql, array_merge($data, $params), $types, MDB2_PREPARE_MANIP);
		if ($res === false) { $this->exception($res, $sql); }

		$res = $this->query("Update serial Set number = number + 1 Where name = '".optSql($idColumn)."'");
		if ($res === false) { $this->exception($res, $sql); }
		$objDB->commit();
		
		return $data[$idColumn];
	}
}

?>