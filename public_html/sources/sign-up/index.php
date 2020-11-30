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

// 出力設定
extract($requestData);

