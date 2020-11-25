<?php

require_once LIB_DIR.'dbd/DBD_Base.php';

class DBD_Query extends DBD_Base {
	public static $arrConnect = array();

	function __construct($dsn = '', $new = false) {
		parent::__construct($dsn, $new);
	}

	public static function setConnection(&$objThis, $dsn = '') {
		$key_str = md5($dsn);

		return DBD_Query::$arrConnect[$key_str] = $objThis;
	}

	public static function getConnection($dsn = '') {
		$key_str = md5($dsn);
		if (isset(DBD_Query::$arrConnect[$key_str])) {
			return DBD_Query::$arrConnect[$key_str];
		}
	}

	public static function singleQuery($dsn = '', $new = false) {
		$objThis = DBD_Query::getConnection($dsn);
		if (is_null($objThis)) {
			$DBDQuery = new DBD_Query($dsn, $new);
			$objThis = DBD_Query::setConnection($DBDQuery, $dsn);
		}

		return clone $objThis;
	}

	public function findData($systemId, $queryArray) {
		$accessTable = $this->getTableName($systemId);

		return $this->findDataBase(DB_COLUMN_HEADER, $accessTable, $queryArray);
	}

	public function findCountData($systemId, $queryArray) {
		$accessTable = $this->getTableName($systemId);

		$queryArray['column'] = array('count(*) as cnt');
		$queryArray['order'] = array();
		unset($queryArray['limit']);
		unset($queryArray['offset']);
		$res = $this->findDataBase(DB_COLUMN_HEADER, $accessTable, $queryArray);
		return $res[0]['cnt'];
	}

	public function findAllData($systemId, $params = array()) {
		$accessTable = $this->getTableName($systemId);

		return $this->findAllDataBase(DB_COLUMN_HEADER, $accessTable, $params);
	}

	public function findRowData($systemId, $queryArray) {
		$accessTable = $this->getTableName($systemId);

		$queryArray['limit'] = "1";
		$res = $this->findDataBase(DB_COLUMN_HEADER, $accessTable, $queryArray);

		if (count($res) == 0) {
			return array();
		}
		return $res[0];
	}

	public function findByIdData($systemId, $id, $where = array(), $params = array()) {
		$accessTable = $this->getTableName($systemId);

		return $this->findByIdDataBase(DB_COLUMN_HEADER, $accessTable, $id, $where, $params);
	}

	public function requestByIdData($systemId, $id, $where = array(), $params = array()) {
		$accessTable = $this->getTableName($systemId);
		if (!is_array($where)) $where = array();

		return $this->requestByIdDataBase(DB_COLUMN_HEADER, $accessTable, $id, $where, $params);
	}

	public function countData($systemId, $where = "", $params = array()) {
		$accessTable = $this->getTableName($systemId);

		return $this->countDataBase(DB_COLUMN_HEADER, $accessTable, $where, $params);
	}

	public function countSZData($systemId, $column = "", $join = "", $where = "", $group = "", $having = "", $params = array()) {
		$accessTable = $this->getTableName($systemId);

		return $this->countSZDataBase(DB_COLUMN_HEADER, $accessTable, $column, $join, $where, $group, $having, $params);
	}

	public function insertData($systemId, $requestData, $duplicate = null, $params = array(), $types = null) {
		$accessTable = $this->getTableName($systemId);

		return $this->insertDataBase(DB_COLUMN_HEADER, $accessTable, $requestData, $duplicate, $params, $types);
	}

	public function updateData($systemId, $requestData, $updateId, $params = array(), $types = null) {
		$accessTable = $this->getTableName($systemId);

		return $this->updateDataBase(DB_COLUMN_HEADER, $accessTable, $requestData, $updateId, $params, $types);
	}

	public function updateFlgData($systemId, $column, $flg, $updateId, $params = array(), $types = null) {
		$accessTable = $this->getTableName($systemId);

		return $this->updateFlgDataBase(DB_COLUMN_HEADER, $accessTable, $column, $flg, $updateId, $params, $types);
	}

	public function deleteData($systemId, $deleteId, $params = array(), $types = null) {
		$accessTable = $this->getTableName($systemId);

		return $this->deleteDataBase(DB_COLUMN_HEADER, $accessTable, $deleteId, $params, $types);
	}

	public function getParamCode($systemId, $column) {
		return $this->getParamCodeBase($systemId, $column);
	}

	public function insertSerialData($systemId, $requestData, $duplicate = null, $params = array(), $types = null) {
		$accessTable = $this->getTableName($systemId);

		return $this->insertSerialDataBase(DB_COLUMN_HEADER, $accessTable, $requestData, $duplicate, $params, $types);
	}
}

?>