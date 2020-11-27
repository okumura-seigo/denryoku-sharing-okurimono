<?php

# パラメータ設定
$arrParam = array(
	"sid" => "",
	"id" => "",
	"data" => "",
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

// DB
$infoSystem = findByIdSystem($requestData['sid']);
if (!is_numeric($infoSystem['system_id'])) exit;

// データ取得
$infoData = findByIdData($infoSystem['system_table'], $requestData['id']);
if (!is_numeric($infoData[$infoSystem['system_table'].'_id'])) exit();
if (!$infoData[DB_COLUMN_HEADER.$requestData['data']]) exit();

// 出力設定
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=".basename($infoData[DB_COLUMN_HEADER.$requestData['data']]));

echo file_get_contents(UPLOAD_FILE_DIR.$infoData[DB_COLUMN_HEADER.$requestData['data']]);

?>