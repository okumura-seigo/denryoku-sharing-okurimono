<?php

// 停止フラグ・削除フラグ
$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($infoSystem['system_id'])."'", "param_column = 'stop_flg'"));
$existsStopFlg = (isset($resParam[0]['param_id'])) ? 1 : 0;
$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($infoSystem['system_id'])."'", "param_column = 'delete_flg'"));
$existsDeleteFlg = (isset($resParam[0]['param_id'])) ? 1 : 0;

// データ
$whereArray = array();
if ($requestData['id']) $whereArray[] = $infoSystem['system_table']."_id = '".$objDB->quote($requestData['id'])."'";
if ($requestData['q']) {
	$queryParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($infoSystem['system_id'])."'", "param_type in (4, 5, 6, 7, 8, 14, 16, 18)"), array("param_sort Asc", "param_id Asc"));
	$queryWhere = array();
	foreach ($queryParam as $key => $val) $queryWhere[] = $objDB->quote($val['param_column'])." Like '%".$objDB->quote($requestData['q'])."%'";
	$whereArray[] = "(".implode(" Or ", $queryWhere).")";
}
if (count($requestData['flg']) > 0 && ($existsStopFlg || $existsDeleteFlg)) {
	$orArray = array();
	if (in_array("none", $requestData['flg'])) {
		$noneArray = array();
		if ($existsStopFlg) $noneArray[] = "stop_flg = 0";
		if ($existsDeleteFlg) $noneArray[] = "delete_flg = 0";
		$orArray[] = "(".implode(" And ", $noneArray).")";
	}
	if ($existsStopFlg && in_array("stop_flg", $requestData['flg'])) $orArray[] = "stop_flg = 1";
	if ($existsDeleteFlg && in_array("delete_flg", $requestData['flg'])) $orArray[] = "delete_flg = 1";
	$whereArray[] = "(".implode(" Or ", $orArray).")";
}

$orderArray = array();
if ($requestData['s'] != "") {
	$orderArray[] = ($requestData['st'] == 1) ? $requestData['s']." Asc" : $requestData['s']." Desc" ;
} else {
	$orderArray = array($infoSystem['system_list_sort']);
}
$resData = findEZData($requestData['cms'], $whereArray, $orderArray);

// 出力設定
header("Content-Type: application/octet-stream; charset=Shift_JIS");
header("Content-Disposition: attachment; filename=data".$requestData['cms']."_".date("Ymd").".csv");

echo "\"".optQuotes("RecordID")."\",";
echo "\"".optQuotes("会員名(姓)")."\",";
echo "\"".optQuotes("会員名(名)")."\",";
echo "\"".optQuotes("名産品")."\",";
echo "\"".optQuotes("送付先住所")."\",";
echo "\r\n";

foreach ($resData as $key => $val) {
	$infoUser = $objDB->findByIdData("user", $val['user_id']);
	$infoItem = $objDB->findByIdData("item", $val['item_id']);

	echo "\"".optQuotes($val[$infoSystem['system_table'].'_id'])."\",";
	echo "\"".optQuotes($infoUser['name1'])."\",";
	echo "\"".optQuotes($infoUser['name2'])."\",";
	echo "\"".optQuotes($infoItem['name'])."\",";
	echo "\"".optQuotes($val['address'])."\",";

	echo "\r\n";
}

?>