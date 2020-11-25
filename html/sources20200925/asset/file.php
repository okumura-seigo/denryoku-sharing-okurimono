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

// ファイルのダウンロード
header("Content-type: application/octet-stream");
header('Content-Disposition: attachment; filename='.$requestData['f']);
header('Content-Length: '.filesize(UPLOAD_FILE_DIR.$requestData['f']));
readfile(UPLOAD_FILE_DIR.$requestData['f']);
