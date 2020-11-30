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

// 名産品取得
$infoItem = $objDB->findByIdData('item', $requestData['id']);
if (!is_numeric($infoItem['item_id'])) exit;

// 出力設定
extract($requestData);

