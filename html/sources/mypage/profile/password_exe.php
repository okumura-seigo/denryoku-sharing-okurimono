<?php

# パラメータ設定
$arrParam = array(
	"passwd_now" => "現在のパスワード",
	"passwd" => "新しいパスワード",
	"passwd_con" => "新しいパスワード（確認用）",
);

// ライブラリ読み込み
require_once WEB_APP."user.php";
// モジュール読み込み
loadLibrary("mail");

// データ取得
$requestData = getRequestData($arrParam);

// エラーチェック
$errMsg = actionValidate("user_password_val", $requestData, $arrParam);
if (count($errMsg) == 0) {
	$requestData['passwd'] = password_hash($requestData['passwd'], PASSWORD_DEFAULT);
	$objDB->updateData("user", array("passwd" => $requestData['passwd']), $infoLoginUser['user_id']);
	redirectUrl("password_exe");
}

// 出力設定
extract($requestData);
