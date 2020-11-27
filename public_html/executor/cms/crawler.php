<?php

# パラメータ設定
$arrParam = array(
	"cms" => "システムID",
	"enc" => "エンコード",
	"stop" => "ストップ",
);
// 管理画面文字エンコード
if (!defined('APP_ENC')) define("APP_ENC", "UTF-8");
// 設定ファイル読み込み
require_once '../../../webapp/config/cfg.inc.php';
// ライブラリ読み込み
require_once WEB_APP."public.php";

// フォーム状態の取得
$formState = getFormState();
// データ取得
$_POST = $_GET = $_REQUEST;
$requestData = getParam($arrParam, $formState);
if (!is_numeric($requestData['cms'])) exit;

// DB
$infoSystem = findByIdSystem($requestData['cms']);
$infoSystem['system_content'] = unserialize($infoSystem['system_content']);
if (!is_numeric($infoSystem['system_id'])) exit;
if (!inArray("crawler", $infoSystem['system_content'])) exit;

// 項目取得
$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($infoSystem['system_id'])."'"), array("param_sort Asc", "param_id Asc"));
$arrParam = setParamCms($arrParam, $resParam);
$requestData = getParam($arrParam, $formState);
if ($requestData['enc'] == "") $requestData['enc'] = "SJIS";

// エラーチェック
$errMsg = cmsAddVal($requestData, $arrParam, $resParam, true);
if (count($errMsg) > 0) {
	exit;
}

// 登録
$dbFlg = false;
if ($infoSystem['system_csv_key'] != "" && $dbFlg == false) {
	$tmpData =  findEZData($requestData['cms'], array($objDB->quote($infoSystem['system_csv_key'])." = '".$objDB->quote($requestData[optParamName($infoSystem['system_csv_key'])])."'"));
	if (is_numeric($tmpData[0][DB_COLUMN_HEADER.'id'])) {
		updateData($requestData['cms'], $requestData, $tmpData[0][DB_COLUMN_HEADER.'id']);
		$dbFlg = true;
	}
}
if ($dbFlg == false) {
	$insertId = insertData($requestData['cms'], $requestData);
	updateSf2tData($requestData['cms'], $insertId);
}

?>