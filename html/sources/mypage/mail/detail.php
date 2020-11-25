<?php

# パラメータ設定
$arrParam = array(
	"id" => "ID",
);

// ライブラリ読み込み
require_once WEB_APP."user.php";

// データ取得
$requestData = getRequestData($arrParam);

// 参加履歴
$infoMessage = $objDB->findRowData(
	'message',
	array(
		"where" => array("user_id = ?", "message_id = ?", "stop_flg = 0", "delete_flg = 0"),
		"param" => array($infoLoginUser['user_id'], $requestData['id']),
	)
);
if (count($infoMessage) == 0) redirectUrl(HOME_URL);

// 既読
if ($infoMessage['read_flg'] == 0) {
	$objDB->updateData('message', array('read_flg' => '1'), $infoMessage['message_id']);
}

// 出力設定
extract($requestData);

