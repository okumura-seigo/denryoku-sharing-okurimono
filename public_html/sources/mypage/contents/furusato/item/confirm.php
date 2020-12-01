<?php

# パラメータ設定
$arrParam = array(
	"item_id" => "ID",
	"address" => "送付先住所",
);

// ライブラリ読み込み
require_once WEB_APP."user.php";

// データ取得
$requestData = getRequestData($arrParam);
if (!is_numeric($requestData['item_id'])) exit;

// 名産品取得
$infoItem = $objDB->findByIdData('item', $requestData['item_id']);
if (!is_numeric($infoItem['item_id'])) exit;

// 発電者取得
$infoGenerator = $objDB->findByIdData('generator', $infoItem['generator_id']);
if (!is_numeric($infoGenerator['generator_id'])) exit;


// エラーチェック
$errMsg = actionValidate("address_val", $requestData, $arrParam);
if (count($errMsg) > 0) {
	header('Location:' .$_SERVER['HTTP_REFERER']);
	exit;
}

// 出力設定
extract($requestData);