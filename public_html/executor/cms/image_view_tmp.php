<?php

# パラメータ設定
$arrParam = array(
	"f" => "ファイル名",
);
// 管理画面文字エンコード
if (!defined('APP_ENC')) define("APP_ENC", "UTF-8");
// 設定ファイル読み込み
require_once '../../../webapp/config/cfg.inc.php';
// ライブラリ読み込み
require_once WEB_APP."cms.php";

// データ取得
$requestData = getRequestData($arrParam);
if (!$requestData['f']) exit;

// 画像の表示
$image_type = array("", "image/gif", "image/jpeg", "image/png", "image/swf");
$size = getimagesize(UPLOAD_FILE_TEMP_DIR.basename($requestData['f']));
header("Content-type: ".$image_type[$size[2]]);
readfile(UPLOAD_FILE_TEMP_DIR.$requestData['f']);

?>