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

// 出力設定
extract($requestData);
