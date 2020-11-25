<?php

# パラメータ設定
$arrParam = array(
	"cms" => "システムID",
	"s" => "ソート",
	"st" => "ソートタイプ",
	"id" => "ID検索",
	"q" => "フリーワード検索",
	"flg" => "状態",
);
// 管理画面文字エンコード
if (!defined('APP_ENC')) define("APP_ENC", "UTF-8");
// 設定ファイル読み込み
require_once '../../../webapp/config/cfg.inc.php';
// ライブラリ読み込み
require_once WEB_APP."cms.php";

// フォーム状態の取得
$formState = getFormState();
// データ取得
$requestData = getParam($arrParam, $formState);
if (!is_numeric($requestData['cms'])) exit;
if (!is_array($requestData['flg'])) $requestData['flg'] = array("none");

// DB
$infoSystem = findByIdSystem($requestData['cms']);
$infoSystem['system_content'] = unserialize($infoSystem['system_content']);
if (!is_numeric($infoSystem['system_id'])) exit;
$resOutputParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($infoSystem['system_id'])."'"), array("param_sort Asc", "param_id Asc"));

// 項目取得
if (isset($CSVDOWN_EXECUTE[$requestData['cms']]) && $CSVDOWN_EXECUTE[$requestData['cms']] != "") {
	require_once $CSVDOWN_EXECUTE[$requestData['cms']];
	exit;
}

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
foreach ($resOutputParam as $key2 => $val2) {
	echo "\"".optQuotes($val2['param_name'])."\",";
}
echo "\r\n";

foreach ($resData as $key => $val) {
	echo "\"".optQuotes($val[$infoSystem['system_table'].'_id'])."\",";
	foreach ($resOutputParam as $key2 => $val2) {
		if ($val2['param_type'] != "8") {
			echo "\"".optQuotes($val[$val2['param_column']])."\",";
		} else {
			if ($val[$val2['param_column']]) {
				echo "\"".optQuotes(implode("<<>>", unserialize($val[$val2['param_column']])))."\",";
			} else {
				echo "\"\",";
			}
		}
	}
	echo "\r\n";
}

?>