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

$infoExchange = $objDB->findRowData(
	'exchange',
	array(
		"where" => array("user_id = ?", "item_id = ?", "state = 0", "delete_flg = 0"),
		"param" => array($infoLoginUser['user_id'], $infoItem['item_id'])
	)
);
if (count($infoExchange) == 0) {
	$requestData["user_id"] = $infoLoginUser['user_id'];
	$requestData["state"] = 0;
	$objDB->insertData('exchange', $requestData);
}

// メール内容読み込み
$contact_data['item_name'] = $infoItem['name'];
$contact_data['generator_name'] = $infoGenerator['name'];
$contact_data['item_point'] = $infoItem['point'];
$contact_data['name1'] = $infoLoginUser['name1'];
$contact_data['name2'] = $infoLoginUser['name2'];
$contact_data['post'] = $infoLoginUser['post'];
$contact_data['pref'] = $infoLoginUser['pref'];
$contact_data['address1'] = $infoLoginUser['address1'];
$contact_data['address2'] = $infoLoginUser['address2'];
$contact_data['address3'] = $infoLoginUser['address3'];
$contact_data['tel'] = $infoLoginUser['tel1'];
require_once WEB_APP.'mail/furusato/customer_mail.php';

mb_language("Japanese");
mb_internal_encoding("UTF-8");
mb_send_mail($infoLoginUser['email'], $customer_title, $customer_content);

// 出力設定
extract($requestData);

