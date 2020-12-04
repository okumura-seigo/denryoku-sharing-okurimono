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

// 名産品取得
$infoItem = $objDB->findByIdData('item', $requestData['id']);
if (!is_numeric($infoItem['item_id'])) exit;

// 発電者取得
$infoGenerator = $objDB->findByIdData('generator', $infoItem['generator_id']);
if (!is_numeric($infoGenerator['generator_id'])) exit;


// エラーチェック
$errMsg = actionValidate("address_val", $requestData, $arrParam);
if (count($errMsg) > 0) {
	require_once BOOT_PHP_DIR.'mypage/contents/furusato/item/index.php';
	require_once BOOT_HTML_DIR.'mypage/contents/furusato/item/index.html';
	exit;
}

// 出力設定
extract($requestData);