<?php

# パラメータ設定
$arrParam = array(
  "passwd_now" => "現在のパスワード",
  "passwd" => "新しいパスワード",
  "passwd_con" => "新しいパスワード（確認用）",
);
// ライブラリ読み込み
require_once WEB_APP."user.php";

// データ取得
$requestData = getRequestData($arrParam);

// エラーチェック
$errMsg = actionValidate("user_password_val", $requestData, $arrParam);
if (count($errMsg) > 0) {
	require_once 'password.php';
	exit;
}

// 出力設定
extract($requestData);

