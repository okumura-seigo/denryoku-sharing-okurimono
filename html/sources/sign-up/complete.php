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
if (count($errMsg) > 0) exit;

// DB操作
$requestData['passwd'] = password_hash($requestData['passwd'], PASSWORD_DEFAULT);
$result = $objDB->insertData("user", $requestData);
if ($result == false) {
	exit;
}

// メール送信
mb_language("Japanese");
mb_internal_encoding("UTF-8");

mb_send_mail($requestData['email'],"電力シェアリング会員登録完了のお知らせ","電力シェアリング会員登録が完了しました");

// ログイン情報保持
$_SESSION['loginUserId'] = $objDB->lastInsertId("user");

// 出力設定
extract($requestData);

