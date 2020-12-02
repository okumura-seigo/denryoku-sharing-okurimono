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

// エラーチェック
$errMsg = actionValidate("address_val", $requestData, $arrParam);
if (count($errMsg) == 0) {

	// 名産品取得
	$infoItem = $objDB->findByIdData('item', $requestData['id']);
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
	$contact_data['pref'] = $requestData['address'];
	$contact_data['tel'] = $infoLoginUser['tel1'];


	mb_language("Japanese");
	mb_internal_encoding("UTF-8");

	// 出力設定
	extract($requestData);
	// メール送信
	require_once WEB_APP.'mail/furusato/customer_mail.php';
	mb_send_mail($infoLoginUser['email'], $customer_title, $customer_content, ADM_MAILER, ADM_MAIL);
	redirectURL('complete');
}