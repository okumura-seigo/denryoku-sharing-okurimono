<?php

# パラメータ設定
$arrParam = array(
  "loginid" => "ログインID",
  "passwd" => "パスワード",
  "save" => "ログイン保持",
);

// ライブラリ読み込み
require_once WEB_APP."public.php";

// データ取得
$requestData = getRequestData($arrParam);
if (count($requestData) == 0) $requestData['save'] = 1;

// 出力設定
extract($requestData);

