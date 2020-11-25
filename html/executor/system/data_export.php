<?php

# パラメータ設定
$arrParam = array(
	"cms" => "システムID",
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

// DB
$infoSystem = findByIdSystem($requestData['cms']);
if (!is_numeric($infoSystem['system_id'])) exit;

// 項目取得
$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($infoSystem['system_id'])."'"), array("param_sort Asc", "param_id Asc"));
$arrParam = setParamCms($arrParam, $resParam);
$requestData = getParam($arrParam, $formState);

// 出力
$systemArray = array("0" => $infoSystem);
$expSystem = data2insert($systemArray, DB_TABLE_HEADER."system");

// 出力設定
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=data".$requestData['cms']."_".date("Ymd").".data");

foreach ($expSystem as $key => $val) {
	echo $val."\r\n";
}
echo "---\r\n";
$expParam = data2insert($resParam, DB_TABLE_HEADER."param");
foreach ($expParam as $key => $val) {
	echo $val."\r\n";
}

$objDB = dbObject();
$resDB = $objDB->query("SHOW CREATE TABLE `".$infoSystem['system_table']."`");
echo "---\r\n";
echo substr($resDB[0]["Create Table"], 0, strpos($resDB[0]["Create Table"], "ENGINE"))."ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;";

function data2insert($system, $table) {
	$column = array();
	$sql = array();
	
	$i = 0;
	// カラム
	foreach ($system[0] as $key => $val) {
		if (!is_numeric($key) && $key != "param_id") {
			$column[] = str_replace("'", "\\'", $key);
		}
	}
	
	foreach ($system as $key => $val) {
		$data = array();
		foreach ($val as $key2 => $val2) {
			if (!is_numeric($key2) && $key2 != "param_id") {
				$data[] = str_replace("'", "\\'", $val2);
			}
		}
		$sql[] = "Insert Into ".$table." (".implode(", ", $column).") Values ('".implode("', '", $data)."');";
	}
	
	return $sql;
}

?>