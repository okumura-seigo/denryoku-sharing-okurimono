<?php

# パラメータ設定
$arrParam = array(
);
// 管理画面文字エンコード
if (!defined('APP_ENC')) define("APP_ENC", "UTF-8");
// 設定ファイル読み込み
require_once '../../../webapp/config/cfg.inc.php';
// ライブラリ読み込み
require_once WEB_APP."public.php";

// ログアウト
unset($_SESSION['loginCmsId']);

// リダイレクト
header("Location: ./login.php");
exit;

?>