<?php

# パラメータ設定
$arrParam = array(
	"f" => "ファイル名",
);
// ライブラリ読み込み
require_once WEB_APP."public.php";

// データ取得
$requestData = getRequestData($arrParam);
if (!$requestData['f']) exit;

// 画像の表示
$image_type = array("", "image/gif", "image/jpeg", "image/png", "image/swf");
$size = getimagesize(UPLOAD_FILE_TEMP_DIR.basename($requestData['f']));
header("Content-type: ".$image_type[$size[2]]);
readfile(UPLOAD_FILE_TEMP_DIR.$requestData['f']);
