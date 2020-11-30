<?php

# パラメータ設定
$arrParam = array(
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

// 出力設定
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=".escapeHtml($requestData['data']));

echo file_get_contents(UPLOAD_FILE_TEMP_DIR.$requestData['data']);

?>