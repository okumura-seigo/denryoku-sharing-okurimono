<?php

# パラメータ設定
$arrParam = array(
	"email" => "メールアドレス",
	"passwd" => "パスワード",
	"passwd_con" => "パスワード(確認)",
	"name1" => "お名前(姓)",
	"name2" => "お名前(名)",
	"furi1" => "フリガナ(セイ)",
	"furi2" => "フリガナ(メイ)",
	"pref" => "都道府県",
	"checkbox" => "利用規約"
);

// ライブラリ読み込み
require_once WEB_APP."public.php";

// データ取得
$requestData = getRequestData($arrParam);

// エラーチェック
$errMsg = actionValidate("user_regist_val", $requestData, $arrParam);
if (count($errMsg) > 0) {        
	require_once BOOT_PHP_DIR.'sign-up/index.php';
	require_once BOOT_HTML_DIR.'sign-up/index.html';
	exit;
}

// 出力設定
extract($requestData);

