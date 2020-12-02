<?php

require_once LIB_DIR.'dbd/DBD_Structure.php';

class DBD_Base extends DBD_Structure {
	public static $arrConnect = array();

	function __construct($dsn = '', $new = false) {
		parent::__construct($dsn, $new);
	}

	public static function setConnection(&$objThis, $dsn = '') {
		$key_str = md5($dsn);

		return DBD_Base::$arrConnect[$key_str] = $objThis;
	}

	public static function getConnection($dsn = '') {
		$key_str = md5($dsn);
		if (isset(DBD_Base::$arrConnect[$key_str])) {
			return DBD_Base::$arrConnect[$key_str];
		}
	}

	public static function singleQuery($dsn = '', $new = false) {
		$objThis = DBD_Base::getConnection($dsn);
		if (is_null($objThis)) {
			$objThis = DBD_Base::setConnection(new DBD_Base($dsn, $new), $dsn);
		}

		return clone $objThis;
	}

	public function getTableName($systemId) {
		if (is_numeric($systemId)) {
			$infoSystem = $this->findByIdDataBase("system_", DB_TABLE_HEADER."system", $systemId);
			if ($infoSystem['system_table'] == "") {
				return DB_TABLE_HEADER."data".$systemId;
			} else {
				return $infoSystem['system_table'];
			}
		} else {
			$tableName = $systemId;
		}
		return $tableName;
	}

	public function findDataBase($head, $accessTable, $queryArray) {
		if ($head == '') {
			if (strpos($accessTable, '___') !== false) {
				$expAccessTable = explode('___', $accessTable);
				$head = $expAccessTable[0].'___';
			}
		}

		if (!isset($queryArray['column'])) $queryArray['column'] = array();
		if (!isset($queryArray['join'])) $queryArray['join'] = array();
		if (!isset($queryArray['where'])) $queryArray['where'] = array();
		if (!isset($queryArray['group'])) $queryArray['group'] = array();
		if (!isset($queryArray['having'])) $queryArray['having'] = array();
		if (!isset($queryArray['order'])) $queryArray['order'] = array();
		if (!isset($queryArray['limit']) || $queryArray['limit'] === "") $queryArray['limit'] = $this->getObjQuery()->maxLimitNum;
		if (!isset($queryArray['offset']) || $queryArray['offset'] === "") $queryArray['offset'] = 0;
		if (!isset($queryArray['update'])) $queryArray['update'] = array();
		if (!isset($queryArray['params']) && isset($queryArray['param'])) $queryArray['params'] = $queryArray['param'];
		if (!isset($queryArray['params'])) $queryArray['params'] = array();
		if (!is_array($queryArray['column']) && $queryArray['column']) $queryArray['column'] = array($queryArray['column']);
		if (!is_array($queryArray['join']) && $queryArray['join']) $queryArray['join'] = array($queryArray['join']);
		if (!is_array($queryArray['where']) && $queryArray['where']) $queryArray['where'] = array($queryArray['where']);
		if (!is_array($queryArray['group']) && $queryArray['group']) $queryArray['group'] = array($queryArray['group']);
		if (!is_array($queryArray['having']) && $queryArray['having']) $queryArray['having'] = array($queryArray['having']);
		if (!is_array($queryArray['order']) && $queryArray['order']) $queryArray['order'] = array($queryArray['order']);
		if (!is_array($queryArray['params']) && $queryArray['params']) $queryArray['params'] = array($queryArray['params']);

		$column = implode(", ", $queryArray['column']);
		if ($column === "") $column = "*";
		$join = implode(" ", $queryArray['join']);
		$where = implode(" And ", $queryArray['where']);
		$group = implode(", ", $queryArray['group']);
		$having = implode(" And ", $queryArray['having']);
		$order = implode(", ", $queryArray['order']);
		$update = ($queryArray['update']) ? $queryArray['update'] : "";

		$limoff = "";
		if (is_numeric($queryArray['limit']) && is_numeric($queryArray['offset'])) {
			$this->getObjQuery()->con->setLimit($queryArray['limit'], $queryArray['offset']);
		} else {
			$limoff = " Limit ".$queryArray['limit']." Offset ".$queryArray['offset'];
		}

		if ($where !== "") $where = " Where ".$where;
		if ($group !== "") $group = " Group By ".$group;
		if ($having !== "") $having = " Having ".$having;
		if ($order !== "") $order = " Order By ".$order;

		$resDB = $this->query("
			Select
				$column
			From
				".($accessTable)."
			$join
			$where
			$group
			$having
			$order
			$limoff
			$update
		", $queryArray['params']);

		return $resDB;
	}

	public function findAllDataBase($head, $accessTable, $params = array()) {
		if ($head == '') {
			if (strpos($accessTable, '___') !== false) {
				$expAccessTable = explode('___', $accessTable);
				$head = $expAccessTable[0].'___';
			}
		}

		$resDB = $this->query("
			Select
				*
			From
				".$accessTable."
			Where
				".$head."delete_flg = '0'
			Order By
				".str_replace($head, "", $accessTable)."_id Desc
		", $params);

		return $resDB;
	}

	public function findByIdDataBase($head, $accessTable, $id, $where = array(), $params = array()) {
		$erasureCode = '';
		if (strpos($accessTable, '___') !== false) {
			$expAccessTable = explode('___', $accessTable);
			$erasureCode = $expAccessTable[0].'___';
		}

		if (!is_array($where)) $where = array();
		$whereArray = array_merge(array(str_replace($erasureCode, '', $accessTable).'_id = ?'), $where);

		$resDB = $this->query("
			Select
				*
			From
				".$accessTable."
			Where
				".implode(" And ", $whereArray)."
			",
			array_merge(array($id), $params)
		);

		if (isset($resDB[0])) {
			$result = $resDB[0];
		} else {
			$result = $resDB;
		}

		return $result;
	}

	public function requestByIdDataBase($head, $accessTable, $id, $where = array(), $params = array()) {
		$erasureCode = '';
		if (strpos($accessTable, '___') !== false) {
			$expAccessTable = explode('___', $accessTable);
			$erasureCode = $expAccessTable[0].'___';
		}

		if (!is_array($where)) $where = array();
		$whereArray = array_merge(array(str_replace($erasureCode, '', $accessTable).'_id = ?'), $where);

		$resDB = $this->query("
			Select
				*
			From
				".$accessTable."
			Where
				".implode(" And ", $whereArray)."
			",
			array_merge(array($id), $params)
		);

		$requestData = array();
		$requestData['id'] = $resDB[0][str_replace($erasureCode, '', $accessTable).'_id'];

		$resSystem = $this->query("
			Select
				system_id
			From
				".DB_TABLE_HEADER."system
			Where
				system_table = ?
		", array($accessTable));
		if (!is_numeric($resSystem[0]['system_id'])) exit;
		$systemId = $resSystem[0]['system_id'];

		$resParam = $this->query("
			Select
				*
			From
				".DB_TABLE_HEADER."param
			Where
				param_stop_flg = 0
			And
				param_delete_flg = 0
			And
				system_id = ?
		", array($systemId));
		foreach ($resParam as $infoParam) {
			$val = str_replace($head, '', $infoParam['param_column']);
			if (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "datetime") !== false) {
				$exp1 = explode(" ", $resDB[0][$head.$val]);
				if (isset($exp1[0])) $exp11 = explode("-", $exp1[0]);
				if (isset($exp1[1])) $exp12 = explode(":", $exp1[1]);
				$requestData[$val."_y"] = (isset($exp11[0])) ? $exp11[0] : '';
				$requestData[$val."_m"] = (isset($exp11[1])) ? $exp11[1] : '';
				$requestData[$val."_d"] = (isset($exp11[2])) ? $exp11[2] : '';
				$requestData[$val."_h"] = (isset($exp12[0])) ? $exp12[0] : '';
				$requestData[$val."_i"] = (isset($exp12[1])) ? $exp12[1] : '';
				$requestData[$val."_s"] = (isset($exp12[2])) ? $exp12[2] : '';
				$requestData[$val] = $resDB[0][$head.$val];
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "date") !== false) {
				$exp11 = explode("-", $resDB[0][$head.$val]);
				$requestData[$val."_y"] = (isset($exp11[0])) ? $exp11[0] : '';
				$requestData[$val."_m"] = (isset($exp11[1])) ? $exp11[1] : '';
				$requestData[$val."_d"] = (isset($exp11[2])) ? $exp11[2] : '';
				$requestData[$val] = $resDB[0][$head.$val];
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "text_c") !== false) {
				$requestData[$val] = unserialize($resDB[0][$head.$val]);
			} else {
				$requestData[$val] = $resDB[0][$head.$val];
			}
		}

		return $requestData;
	}

	public function countDataBase($head, $accessTable, $where = "", $params = array()) {
		if (is_array($where)) $where = implode(" And ", $where);
		if ($where != "") $where = " Where ".$where;

		$resDB = $this->query("
			Select
				count(*) as cnt
			From
				".$accessTable."
			$where
		", $params);

		return $resDB[0]['cnt'];
	}

	public function countSZDataBase($head, $accessTable, $column = "", $join = "", $where = "", $group = "", $having = "", $params = array()) {
		$column = ($column == "") ? "*" : implode(", ", $column);
		if (is_array($join)) $join = implode(" ", $join);
		if (is_array($where)) $where = implode(" And ", $where);
		if (is_array($group)) $group = implode(", ", $group);
		if (is_array($having)) $having = implode(" And ", $having);

		if ($where !== "") $where = " Where ".$where;
		if ($group !== "") $group = " Group By ".$group;
		if ($having !== "") $having = " Having ".$having;

		$resDB = $this->query("
			Select
				count(*) as cnt
			From
				".$accessTable."
			$join
			$where
			$group
			$having
		", $params);

		return $resDB[0]['cnt'];
	}

	public function insertDataBase($head, $accessTable, $requestData, $duplicate = null, $params = array(), $types = null) {
		$resParam = $this->getOrMaps($accessTable, $head);

		if (!isset($requestData['stop_flg'])) $requestData['stop_flg'] = 0;
		if (!isset($requestData['delete_flg'])) $requestData['delete_flg'] = 0;
		if (!isset($requestData['insert_datetime'])) $requestData['insert_datetime'] = date("Y-m-d H:i:s");
		if (!isset($requestData['update_datetime'])) $requestData['update_datetime'] = date("Y-m-d H:i:s");
		if (!isset($requestData['system_note'])) $requestData['system_note'] = '';

		foreach ($resParam as $infoParam) {
			if (substr($infoParam['param_column'], 0, strlen($head)) == $head) {
				$val = substr($infoParam['param_column'], strlen($head));
			} else {
				$val = $infoParam['param_column'];
			}

			if ($accessTable == 'param' && $val == 'system_id') {
				$insertArray[$val] = $requestData[$val];
				continue;
			}

			if (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "int") !== false) {
				if (isset($requestData[$val])) {
					if (is_numeric($requestData[$val])) {
						$insertArray[$head.$val] = $requestData[$val];
					} elseif ($requestData[$val] == "") {
						$insertArray[$head.$val] = 0;
					} else {
						return false;
					}
				} else {
					$insertArray[$head.$val] = 0;
				}
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "double") !== false) {
				if (isset($requestData[$val])) {
					if (is_numeric($requestData[$val])) {
						$insertArray[$head.$val] = $requestData[$val];
					} elseif ($requestData[$val] == "") {
						$insertArray[$head.$val] = 0;
					} else {
						return false;
					}
				} else {
					$insertArray[$head.$val] = 0;
				}
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "string") !== false) {
				if (isset($requestData[$val])) {
					$insertArray[$head.$val] = $requestData[$val];
				} else {
					$insertArray[$head.$val] = '';
				}
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "text_c") !== false) {
				if (isset($requestData[$val])) {
					if (is_array($requestData[$val])) {
						$insertArray[$head.$val] = serialize($requestData[$val]);
					} elseif (!is_array($requestData[$val]) && $requestData[$val] == "") {
						$insertArray[$head.$val] = null;
					} else {
						return false;
					}
				} else {
					$insertArray[$head.$val] = '';
				}
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "text") !== false) {
				if (isset($requestData[$val])) {
					$insertArray[$head.$val] = $requestData[$val];
				} else {
					$insertArray[$head.$val] = '';
				}
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "datetime") !== false) {
				if (isset($requestData[$val])) {
					if (checkData($requestData[$val], "timestamp")) {
						$insertArray[$head.$val] = $requestData[$val];
					} elseif ($requestData[$val] == "") {
						$insertArray[$head.$val] = '0000-00-00 00:00:00';
					} else {
						return false;
					}
				} else {
					$insertArray[$head.$val] = '0000-00-00 00:00:00';
				}
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "date") !== false) {
				if (isset($requestData[$val])) {
					if (checkData($requestData[$val], "date")) {
						$insertArray[$head.$val] = $requestData[$val];
					} elseif ($requestData[$val] == "") {
						$insertArray[$head.$val] = '0000-00-00';
					} else {
						return false;
					}
				} else {
					$insertArray[$head.$val] = '0000-00-00';
				}
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "file") !== false) {
				if (isset($requestData[$val])) {
					$insertArray[$head.$val] = $requestData[$val];
				} else {
					$insertArray[$head.$val] = '';
				}
			}
		}
		$res = $this->getObjQuery()->insert($accessTable, $insertArray, $duplicate, $params, $types);

		return $res;
	}

	public function updateDataBase($head, $accessTable, $requestData, $updateId, $params = array(), $types = null) {
		$erasureCode = '';
		if (strpos($accessTable, '___') !== false) {
			$expAccessTable = explode('___', $accessTable);
			$erasureCode = $expAccessTable[0].'___';
		}

		$resParam = $this->getOrMaps($accessTable, $head);
		if (!isset($requestData['update_datetime'])) $requestData['update_datetime'] = date("Y-m-d H:i:s");

		$updateArray = array();
		foreach ($resParam as $infoParam) {
			$val = str_replace($head, '', $infoParam['param_column']);
			if (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "int") !== false) {
				if (isset($requestData[$val])) {
					if (is_numeric($requestData[$val])) {
						$updateArray[$head.$val] = $requestData[$val];
					} elseif ($requestData[$val] == "") {
						$updateArray[$head.$val] = 0;
					} else {
						return false;
					}
				}
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "double") !== false) {
				if (isset($requestData[$val])) {
					if (is_numeric($requestData[$val])) {
						$updateArray[$head.$val] = $requestData[$val];
					} else {
						return false;
					}
				}
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "string") !== false) {
				if (isset($requestData[$val])) $updateArray[$head.$val] = $requestData[$val];
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "text_c") !== false) {
				if (isset($requestData[$val])) {
					if (is_array($requestData[$val])) {
						$updateArray[$head.$val] = serialize($requestData[$val]);
					} elseif (!is_array($requestData[$val]) && $requestData[$val] == "") {
						$updateArray[$head.$val] = "";
					} else {
						return false;
					}
				}
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "text") !== false) {
				if (isset($requestData[$val])) $updateArray[$head.$val] = $requestData[$val];
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "datetime") !== false) {
				if (isset($requestData[$val])) {
					if (checkData($requestData[$val], "timestampE") || $requestData[$val] == "0000-00-00 00:00:00") {
						$updateArray[$head.$val] = $requestData[$val];
					} elseif ($requestData[$val] == "") {
						$updateArray[$head.$val] = "0000-00-00 00:00:00";
					} else {
						return false;
					}
				}
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "date") !== false) {
				if (isset($requestData[$val])) {
					if (checkData($requestData[$val], "date") || $requestData[$val] == "0000-00-00") {
						$updateArray[$head.$val] = $requestData[$val];
					} elseif (str_replace("-", "", $requestData[$val]) == "") {
						$updateArray[$head.$val] = "0000-00-00";
					} else {
						return false;
					}
				}
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "file") !== false) {
				if (isset($requestData[$val])) $updateArray[$head.$val] = $requestData[$val];
			}
		}
		$res = $this->getObjQuery()->update($accessTable, $updateArray, str_replace($erasureCode, '', $accessTable)."_id = ?", array_merge(array($updateId), $params), $types);

		return $res;
	}

	public function updateFlgDataBase($head, $accessTable, $column, $flg, $updateId, $params = array(), $types = null) {
		$erasureCode = '';
		if (strpos($accessTable, '___') !== false) {
			$expAccessTable = explode('___', $accessTable);
			$erasureCode = $expAccessTable[0].'___';
		}

		$resParam = $this->getOrMaps($accessTable, $head);
		$requestData = array(
			$head.$column => (int)$flg,
			$head."update_datetime" => date("Y-m-d H:i:s"),
		);

		foreach ($resParam as $infoParam) {
			$val = str_replace($head, '', $infoParam['param_column']);
			if (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "int") !== false) {
				if (isset($requestData[$val])) {
					if (is_numeric($requestData[$val])) {
						$updateArray[$head.$val] = $requestData[$val];
					} elseif ($requestData[$val] == "") {
						$updateArray[$head.$val] = null;
					} else {
						return false;
					}
				}
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "datetime") !== false) {
				if (isset($requestData[$val])) {
					if (checkData($requestData[$val], "timestamp")) {
						$updateArray[$head.$val] = $requestData[$val];
					} elseif ($requestData[$val] == "") {
						$updateArray[$head.$val] = null;
					} else {
						return false;
					}
				}
			}
		}

		$res = $this->getObjQuery()->update(
			$accessTable,
			$updateArray,
			str_replace($erasureCode, '', $accessTable)."_id = ?",
			array_merge(array($updateId), $params)
		);

		return $res;
	}

	public function deleteDataBase($head, $accessTable, $deleteId, $params = array(), $types = null) {
		$erasureCode = '';
		if (strpos($accessTable, '___') !== false) {
			$expAccessTable = explode('___', $accessTable);
			$erasureCode = $expAccessTable[0].'___';
		}

		$resParam = $this->getOrMaps($accessTable, $head);

		$res = $this->getObjQuery()->delete($accessTable, str_replace($erasureCode, '', $accessTable)."_id = ?", array_merge(array($deleteId), $params), $types);

		return $res;
	}

	public function getParamCodeBase($systemId, $column) {
		if (!is_numeric($systemId)) {
			$systemList = $this->getSystemTableList();
			$systemId = array_search($systemId, $systemList);
		}
		$codeData = $this->query('Select param_info From '.DB_TABLE_HEADER.'param Where system_id = ? And param_column = ?', array($systemId, $column));
		$resData = array();
		foreach (explode("\n", $codeData[0]['param_info']) as $val) {
			$val = trim($val);
			if ($val) {
				if (strpos($val, '::') !== false) {
					$expData = explode('::', $val);
					$resData[$expData[1]] = $expData[0];
				} else {
					$resData[] = $val;
				}
			}
		}

		return $resData;
	}

	public function insertSerialDataBase($head, $accessTable, $requestData, $duplicate = null, $params = array(), $types = null) {
		$resParam = $this->getOrMaps($accessTable, $head);

		if (!isset($requestData['stop_flg'])) $requestData['stop_flg'] = 0;
		if (!isset($requestData['delete_flg'])) $requestData['delete_flg'] = 0;
		if (!isset($requestData['insert_datetime'])) $requestData['insert_datetime'] = date("Y-m-d H:i:s");
		if (!isset($requestData['update_datetime'])) $requestData['update_datetime'] = date("Y-m-d H:i:s");

		foreach ($resParam as $infoParam) {
			$val = str_replace($head, '', $infoParam['param_column']);
			if (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "int") !== false) {
				if (isset($requestData[$val])) {
					if (is_numeric($requestData[$val])) {
						$insertArray[$head.$val] = $requestData[$val];
					} elseif ($requestData[$val] == "") {
						$insertArray[$head.$val] = null;
					} else {
						return false;
					}
				}
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "double") !== false) {
				if (isset($requestData[$val])) {
					if (is_numeric($requestData[$val])) {
						$insertArray[$head.$val] = $requestData[$val];
					} else {
						return false;
					}
				}
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "string") !== false) {
				if (isset($requestData[$val])) $insertArray[$head.$val] = $requestData[$val];
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "text_c") !== false) {
				if (isset($requestData[$val])) {
					if (is_array($requestData[$val])) {
						$insertArray[$head.$val] = serialize($requestData[$val]);
					} elseif (!is_array($requestData[$val]) && $requestData[$val] == "") {
						$insertArray[$head.$val] = null;
					} else {
						return false;
					}
				}
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "text") !== false) {
				if (isset($requestData[$val])) $insertArray[$head.$val] = $requestData[$val];
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "datetime") !== false) {
				if (isset($requestData[$val])) {
					if (checkData($requestData[$val], "timestamp")) {
						$insertArray[$head.$val] = $requestData[$val];
					} elseif ($requestData[$val] == "") {
						$insertArray[$head.$val] = null;
					} else {
						return false;
					}
				}
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "date") !== false) {
				if (isset($requestData[$val])) {
					if (checkData($requestData[$val], "date")) {
						$insertArray[$head.$val] = $requestData[$val];
					} elseif ($requestData[$val] == "") {
						$insertArray[$head.$val] = null;
					} else {
						return false;
					}
				}
			} elseif (strpos($this->getObjQuery()->paramTypeLink[$infoParam['param_type']], "file") !== false) {
				if (isset($requestData[$val])) $insertArray[$head.$val] = $requestData[$val];
			}
		}
		$res = $this->getObjQuery()->insertSerial($accessTable, $insertArray, $duplicate, $params, $types);

		return $res;
	}
}

?>