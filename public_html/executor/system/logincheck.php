<?php

# パラメータ設定
$arrParam = array(
	"loginid" => "ログインID",
	"passwd" => "パスワード",
);
// 管理画面文字エンコード
if (!defined('APP_ENC')) define("APP_ENC", "UTF-8");
// 設定ファイル読み込み
require_once '../../../webapp/config/cfg.inc.php';
// ライブラリ読み込み
require_once WEB_APP."public.php";

// フォーム状態の取得
$formState = getFormState();
// データ取得
$requestData = getParam($arrParam, $formState);

if (WA_USERNAME == $requestData['loginid'] && WA_PASSWORD == $requestData['passwd']) {
	$_SESSION['loginSystemId'] = true;
	header('Location: ./');
	exit;
} else {
	$errMsg = array("ログインIDまたはパスワードが一致しておりません");
}

// 出力設定
extract($requestData);
require_once 'login.php';

?>