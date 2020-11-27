<?php

# パラメータ設定
$arrParam = array(
	"cms" => "システムID",
	"id" => "ID",
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

// データ取得
$infoData = findByIdData($requestData['cms'], $requestData['id'], true);

if ($infoData[DB_COLUMN_HEADER.'stop_flg'] == "1") {
	updateSf2fData($requestData['cms'], $requestData['id']);
} else {
	updateSf2tData($requestData['cms'], $requestData['id']);
}

// リダイレクト
header("Location: list.php?cms=".$requestData['cms']);
exit;

?>