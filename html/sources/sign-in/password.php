<?php

# パラメータ設定
$arrParam = array(
  "id" => "ID",
  "token" => "TOKEN",
  "passwd" => "パスワード",
  "passwd_con" => "パスワード(確認)"
);

// ライブラリ読み込み
require_once WEB_APP."public.php";

// データ取得
$requestData = getRequestData($arrParam);
if (!is_numeric($requestData['id'])) redirectUrl(HOME_URL);

$infoUser = $objDB->findData(
	'user_password',
	array(
		"where" => array("user_id = ?", "token = ?", "insert_datetime >= ?"),
		"param" => array($requestData['id'], $requestData['token'], date("Y-m-d H:i:s", strtotime("-1 hour"))),
	)
);
if (count($infoUser) != 1) {
	redirectUrl(HOME_URL);
}

// 出力設定
extract($requestData);

