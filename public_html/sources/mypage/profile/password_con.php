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
	require_once BOOT_PHP_DIR.'mypage/profile/password.php';
	require_once BOOT_HTML_DIR.'mypage/profile/password.html';
	exit;
}

// 出力設定
extract($requestData);

