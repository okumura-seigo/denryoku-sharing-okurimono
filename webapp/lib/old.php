<?php

function pagerStr($maxRows, $listNum, $lim, $array = "") {
	if ($array == "") $array = array();

	$args = array();
	foreach ($array as $key => $val) {
		if (!is_array($val)) {
			if ($key != "lim") {
				$args[] = $key."=".urlencode($val);
			}
		} else {
			foreach ($val as $key2 => $val2) {
				$args[] = $key."[".$key2."]=".urlencode($val2);
			}
		}
	}
	$tagArgs = "&".implode('&', $args);

	$tagPgr = mb_convert_encoding('全'.$maxRows.'件中 '.($lim + 1).'～'.($lim + $listNum).'件表示 ', APP_ENC, "UTF-8");

	if ($lim != 0) {
		$tagPgr.= '&nbsp;<a href="'.$_SERVER['PHP_SELF'].'?lim=0'.$tagArgs.'">&lt;&lt;&nbsp;'.mb_convert_encoding("最初", APP_ENC, "UTF-8").'</a>&nbsp;';
	} else {
		$tagPgr.= '&nbsp;&lt;&lt;&nbsp;'.mb_convert_encoding("最初", APP_ENC, "UTF-8").'&nbsp;';
	}

	if ($lim != 0) {
		$tagPgr.= '&nbsp;<a href="'.$_SERVER['PHP_SELF'].'?lim='.($lim - $listNum).$tagArgs.'">&lt;&nbsp;'.mb_convert_encoding("前へ", APP_ENC, "UTF-8").'</a>&nbsp;';
	} else {
		$tagPgr.= '&nbsp;&lt&nbsp;'.mb_convert_encoding("前へ", APP_ENC, "UTF-8").'&nbsp;';
	}

	$startNum = (floor($lim / $listNum) - 5) * $listNum;
	if ($startNum < 0) $startNum = 0;

	$endNum = (floor($lim / $listNum) + 5) * $listNum;
	if ($endNum > $maxRows) $endNum = floor($maxRows / $listNum) * $listNum + 1;

	$tagNum = array();
	for ($i = $startNum;$i < $maxRows && $i < $endNum;$i+=$listNum) {
		$tagNum[$i] = '';
		if ($lim != $i) $tagNum[$i].= '<a href="'.htmlspecialchars($_SERVER['PHP_SELF'].'?lim='.($i)).$tagArgs.'">';
		$tagNum[$i].= ($i / $listNum + 1);
		if ($lim != $i) $tagNum[$i].= '</a>';
	}

	$tagPgr.= "&nbsp;".implode(" ", $tagNum)."&nbsp;";

	if (($lim + $listNum) < $maxRows) {
		$tagPgr.= '&nbsp;<a href="'.htmlspecialchars($_SERVER['PHP_SELF'].'?lim='.($lim + $listNum)).$tagArgs.'">'.mb_convert_encoding("次へ", APP_ENC, "UTF-8").'&nbsp;&gt;</a>&nbsp;';
	} else {
		$tagPgr.= '&nbsp;'.mb_convert_encoding("次へ", APP_ENC, "UTF-8").'&nbsp;&gt;&nbsp;';
	}

	if (($lim + $listNum) < $maxRows) {
		$tagPgr.= '&nbsp;<a href="'.htmlspecialchars($_SERVER['PHP_SELF'].'?lim='.(floor($maxRows / $listNum) * $listNum)).$tagArgs.'">'.mb_convert_encoding("最後", APP_ENC, "UTF-8").'&nbsp;&gt;&gt;</a>&nbsp;';
	} else {
		$tagPgr.= '&nbsp;'.mb_convert_encoding("最後", APP_ENC, "UTF-8").'&nbsp;&gt;&gt;&nbsp;';
	}

	return $tagPgr;
}

function deleteData($systemId, $deleteId) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->deleteData($systemId, $deleteId);
	}
}

function deleteParam($deleteId) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->deleteDataBase("param_", DB_TABLE_HEADER."param", $deleteId);
	}
}

function deleteSystem($deleteId) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->deleteDataBase("system_", DB_TABLE_HEADER."system", $deleteId);
	}
}

function findData($systemId, $queryArray) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->findData($systemId, $queryArray);
	}
}

function findRecord($systemId, $queryArray) {
	return findData($systemId, $queryArray);
}

function findAllData($systemId) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->findAllData($systemId);
	}
}

function findByIdData($systemId, $id, $flg = 0) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->findByIdData($systemId, $id, $flg);
	}
}

function findEZData($systemId, $where = "", $order = "", $limit = "", $offset = "") {
	global $objDB;
	if (is_object($objDB)) {
		$queryArray = array();
		if ($where != "") $queryArray['where'] = $where;
		if ($order != "") $queryArray['order'] = $order;
		if ($limit != "") $queryArray['limit'] = $limit;
		if ($offset != "") $queryArray['offset'] = $offset;
		return $objDB->findData($systemId, $queryArray);
	}
}

function findSZData($systemId, $column = "", $join = "", $where = "", $group = "", $having = "", $order = "", $limit = "", $offset = "") {
	global $objDB;
	if (is_object($objDB)) {
		$queryArray = array();
		if ($column != "") $queryArray['column'] = $column;
		if ($join != "") $queryArray['join'] = $join;
		if ($where != "") $queryArray['where'] = $where;
		if ($group != "") $queryArray['group'] = $group;
		if ($having != "") $queryArray['having'] = $having;
		if ($order != "") $queryArray['order'] = $order;
		if ($limit != "") $queryArray['limit'] = $limit;
		if ($offset != "") $queryArray['offset'] = $offset;
		return $objDB->findData($systemId, $queryArray);
	}
}

function requestByIdData($systemId, $id) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->requestByIdData($systemId, $id);
	}
}

function countData($systemId, $where = "") {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->countData($systemId, $where);
	}
}

function countSZData($systemId, $column = "", $join = "", $where = "", $group = "", $having = "") {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->countSZData($systemId, $column, $join, $where, $group, $having);
	}
}

function findAllParam() {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->findAllData(DB_TABLE_HEADER."param");
	}
}

function findByIdParam($id, $flg = 0) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->findByIdDataBase("param_", DB_TABLE_HEADER."param", $id, $flg);
	}
}

function findEZParam($where = "", $order = "", $limit = "", $offset = "") {
	global $objDB;
	if (is_object($objDB)) {
		$queryArray = array();
		if ($where != "") $queryArray['where'] = $where;
		if ($order != "") $queryArray['order'] = $order;
		if ($limit != "") $queryArray['limit'] = $limit;
		if ($offset != "") $queryArray['offset'] = $offset;
		return $objDB->findDataBase("param_", DB_TABLE_HEADER."param", $queryArray);
	}
}

function findSZParam($column = "", $join = "", $where = "", $group = "", $having = "", $order = "", $limit = "", $offset = "") {
	global $objDB;
	if (is_object($objDB)) {
		$queryArray = array();
		if ($column != "") $queryArray['column'] = $column;
		if ($join != "") $queryArray['join'] = $join;
		if ($where != "") $queryArray['where'] = $where;
		if ($group != "") $queryArray['group'] = $group;
		if ($having != "") $queryArray['having'] = $having;
		if ($order != "") $queryArray['order'] = $order;
		if ($limit != "") $queryArray['limit'] = $limit;
		if ($offset != "") $queryArray['offset'] = $offset;
		return $objDB->findDataBase("param_", DB_TABLE_HEADER."param", $queryArray);
	}
}

function requestByIdParam($id) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->requestByIdDataBase("param_", DB_TABLE_HEADER."param", $id);
	}
}

function countParam($where = "param_delete_flg = '0'") {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->countDataBase("param_", DB_TABLE_HEADER."param", $where);
	}
}

function countSZParam($column = "", $join = "", $where = "", $group = "", $having = "") {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->countSZDataBase("param_", DB_TABLE_HEADER."param", $column, $join, $where, $group, $having);
	}
}

function findAllSystem() {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->findAllData(DB_TABLE_HEADER."system");
	}
}

function findByIdSystem($id, $flg = 0) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->findByIdDataBase("system_", DB_TABLE_HEADER."system", $id, $flg);
	}
}

function findEZSystem($where = "", $order = "", $limit = "", $offset = "") {
	global $objDB;
	if (is_object($objDB)) {
		$queryArray = array();
		if ($where != "") $queryArray['where'] = $where;
		if ($order != "") $queryArray['order'] = $order;
		if ($limit != "") $queryArray['limit'] = $limit;
		if ($offset != "") $queryArray['offset'] = $offset;
		return $objDB->findDataBase("system_", DB_TABLE_HEADER."system", $queryArray);
	}
}

function findSZSystem($column = "", $join = "", $where = "", $group = "", $having = "", $order = "", $limit = "", $offset = "") {
	global $objDB;
	if (is_object($objDB)) {
		$queryArray = array();
		if ($column != "") $queryArray['column'] = $column;
		if ($join != "") $queryArray['join'] = $join;
		if ($where != "") $queryArray['where'] = $where;
		if ($group != "") $queryArray['group'] = $group;
		if ($having != "") $queryArray['having'] = $having;
		if ($order != "") $queryArray['order'] = $order;
		if ($limit != "") $queryArray['limit'] = $limit;
		if ($offset != "") $queryArray['offset'] = $offset;
		return $objDB->findDataBase("system_", DB_TABLE_HEADER."system", $queryArray);
	}
}

function requestByIdSystem($id) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->requestByIdDataBase("system_", DB_TABLE_HEADER."system", $id);
	}
}

function countSystem($where = "system_delete_flg = '0'") {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->countDataBase("system_", DB_TABLE_HEADER."system", $where);
	}
}

function countSZSystem($column = "", $join = "", $where = "", $group = "", $having = "") {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->countSZDataBase("system_", DB_TABLE_HEADER."system", $column, $join, $where, $group, $having);
	}
}

function updateData($systemId, $requestData, $updateId) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->updateData($systemId, $requestData, $updateId);
	}
}

function updateDf2tData($systemId, $updateId) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->updateFlgData($systemId, 'delete_flg', true, $updateId);
	}
}

function updateDf2fData($systemId, $updateId) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->updateFlgData($systemId, 'delete_flg', false, $updateId);
	}
}

function updateSf2tData($systemId, $updateId) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->updateFlgData($systemId, 'stop_flg', true, $updateId);
	}
}

function updateSf2fData($systemId, $updateId) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->updateFlgData($systemId, 'stop_flg', false, $updateId);
	}
}

function updateParam($requestData, $updateId) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->updateDataBase("param_", DB_TABLE_HEADER."param", $requestData, $updateId);
	}
}

function updateDf2tParam($updateId) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->updateFlgDataBase("param_", DB_TABLE_HEADER."param", 'delete_flg', true, $updateId);
	}
}

function updateDf2fParam($updateId) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->updateFlgDataBase("param_", DB_TABLE_HEADER."param", 'delete_flg', false, $updateId);
	}
}

function updateSf2tParam($updateId) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->updateFlgDataBase("param_", DB_TABLE_HEADER."param", 'stop_flg', true, $updateId);
	}
}

function updateSf2fParam($updateId) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->updateFlgDataBase("param_", DB_TABLE_HEADER."param", 'stop_flg', false, $updateId);
	}
}

function updateSystem($requestData, $updateId) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->updateDataBase("system_", DB_TABLE_HEADER."system", $requestData, $updateId);
	}
}

function updateDf2tSystem($updateId) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->updateFlgDataBase("system_", DB_TABLE_HEADER."system", 'delete_flg', true, $updateId);
	}
}

function updateDf2fSystem($updateId) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->updateFlgDataBase("system_", DB_TABLE_HEADER."system", 'delete_flg', false, $updateId);
	}
}

function updateSf2tSystem($updateId) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->updateFlgDataBase("system_", DB_TABLE_HEADER."system", 'stop_flg', true, $updateId);
	}
}

function updateSf2fSystem($updateId) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->updateFlgDataBase("system_", DB_TABLE_HEADER."system", 'stop_flg', false, $updateId);
	}
}

function insertData($systemId, $requestData) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->insertData($systemId, $requestData);
	}
}

function insertParam($requestData) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->insertDataBase("param_", DB_TABLE_HEADER."param", $requestData);
	}
}

function insertSystem($requestData) {
	global $objDB;
	if (is_object($objDB)) {
		return $objDB->insertDataBase("system_", DB_TABLE_HEADER."system", $requestData);
	}
}

function dbObject() {
	$objDB = DBD_Query::singleQuery();
	return $objDB;
}

function optSql($str, $flg = 0) {
	if (DB_ENC == "UTF-8") {
		$dbEnc = DB_ENC;
	} elseif (DB_ENC == "SJIS") {
		$dbEnc = DB_ENC;
		$dbEnc.= "-win";
	} else {
		$dbEnc = DB_ENC;
		$dbEnc.= "JP-win";
	}
	$str = mb_convert_encoding($str,$dbEnc,APP_ENC);
	if ($flg != 0) $str = str_replace("%","\%",$str);
	if (DB_TYPE == "psql") {
		$str = pg_escape_string($str);
	} else if (DB_TYPE == "mysql") {
		$str = mysql_escape_string($str);
	}
	return $str;
}

?>