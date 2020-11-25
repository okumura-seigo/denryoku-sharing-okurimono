<?php

# パラメータ設定
$arrParam = array(
	"id" => "ID",
	"token" => "TOKEN",
	"passwd" => "新しいパスワード",
	"passwd_con" => "新しいパスワード（確認用）",
);
// ライブラリ読み込み
require_once WEB_APP."public.php";

// データ取得
$requestData = getRequestData($arrParam);

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

// エラーチェック
$errMsg = actionValidate("forget_password_val", $requestData, $arrParam);
if (count($errMsg) > 0) {
	require_once 'password.php';
	exit;
}else{
	$requestData['passwd'] = password_hash($requestData['passwd'], PASSWORD_DEFAULT);
	$objDB->begin();
	$objDB->updateData("user", array("passwd" => $requestData['passwd']), $infoUser[0]['user_id']);
	$objDB->deleteData("user_password", $infoUser[0]['user_password_id']);
	$objDB->commit();
	redirectUrl("password_complete");
}
