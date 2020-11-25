<?php

# パラメータ設定
$arrParam = array(
	"f" => "ファイル名",
);
// ライブラリ読み込み
require_once WEB_APP."user.php";

// データ取得
$requestData = getRequestData($arrParam);
if (!$requestData['f']) exit;

// エラーチェック
$expName = explode("_", $requestData['f']);
if (strlen($expName[0]) != 6 || $infoLoginUser['user_id'] != $expName[0]) exit;

// 画像の表示
$image_type = array("", "image/gif", "image/jpeg", "image/png", "image/swf");
$size = getimagesize(UPLOAD_FILE_TEMP_DIR.basename($requestData['f']));
header("Content-type: ".$image_type[$size[2]]);
readfile(UPLOAD_FILE_TEMP_DIR.$requestData['f']);
