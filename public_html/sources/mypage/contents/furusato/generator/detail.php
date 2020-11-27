<?php

# パラメータ設定
$arrParam = array(
	"id" => "ID",
);

// ライブラリ読み込み
require_once WEB_APP."user.php";

// データ取得
$requestData = getRequestData($arrParam);
if (!is_numeric($requestData['id'])) exit;

// 発電者取得
$infoGenerator = $objDB->findByIdData('generator', $requestData['id']);
if (!is_numeric($infoGenerator['generator_id'])) exit;

// 出力設定
extract($requestData);

