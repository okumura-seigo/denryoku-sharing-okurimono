<?php

# パラメータ設定
$arrParam = array(
  "loginid" => "ログインID",
);

// ライブラリ読み込み
require_once WEB_APP."public.php";

// データ取得
$requestData = getRequestData($arrParam);

// 出力設定
extract($requestData);
