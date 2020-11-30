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

mb_send_mail($requestData['email'], "電力シェアリング会員登録完了のお知らせ", trim('
'.$requestData['name1'].' '.$requestData['name2'].' 様

この度は電力シェアリング会員プログラムにご登録いただき、誠にありがとうございます。
会員登録が完了しました。ご登録内容の確認をお願いいたします。

【ご登録情報】
　メールアドレス： '.$requestData['email'].'
　パ ス ワ ー ド： ご登録されたパスワード

マイページへのログインはこちらから
https://www.dsmp.jp/

今後とも電力シェアリング会員プログラムをよろしくお願いいたします。

※本メールは送信専用アドレスから送信されております。
本メールに返信いただきましても回答できませんのでご了承ください。
お問い合わせはこちらからお願いいたします。
https://www.dsmp.jp/contact/

電力シェアリング会員プログラム
'));

// ログイン情報保持
$_SESSION['loginUserId'] = $objDB->lastInsertId("user");

// 出力設定
extract($requestData);

