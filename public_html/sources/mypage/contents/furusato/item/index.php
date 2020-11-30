<?php

# パラメータ設定
$arrParam = array(
	"id" => "ID",
	"address" => "送付先住所",
);

// ライブラリ読み込み
require_once WEB_APP."user.php";

// データ取得
$requestData = getRequestData($arrParam);
if (!is_numeric($requestData['id'])) exit;
if (!$requestData['address']) $requestData['address'] = $infoLoginUser['pref'].$infoLoginUser['address1'].$infoLoginUser['address2'].$infoLoginUser['address3'];

// 名産品取得
$infoItem = $objDB->findByIdData('item', $requestData['id']);
if (!is_numeric($infoItem['item_id'])) exit;

// 発電者取得
$infoGenerator = $objDB->findByIdData('generator', $infoItem['generator_id']);
if (!is_numeric($infoGenerator['generator_id'])) exit;

// 出力設定
extract($requestData);

