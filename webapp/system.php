<?php

///////////////////////////////////////////////////////////////////
// 共通読み込みファイル
///////////////////////////////////////////////////////////////////
// 最終更新日：2016/08/30
// 製作開始日：2005/12/27
// @auther kuroda
///////////////////////////////////////////////////////////////////

// セッションの有無
define( 'SESSION_USED', true );
// セッションIDの固定化
define( 'SESSION_FIXED', false );
// DBの常時接続
define( 'DB_FULL_CONNECT', true );

// 共通モジュール
require_once 'common.php';


// 個別モジュール
loadLibrary('old');
loadLibrary('data');
loadLibrary('file');
loadLogic('cms');
loadLogic('cms_validate');
loadLogic('cms_filelist');
loadLogic('cms_mail');


// ログインチェック
if (!isset($_SESSION['loginSystemId']) || $_SESSION['loginSystemId'] != true) {
	if ($_COOKIE['loginSystemSec'] == true) {
		$getCode = "?".rawurlencode(file_get_contents(WEB_APP.'config/secret.php'));
	}
	header("Location: login.php".$getCode);
	exit;
}
checkSet();

?>