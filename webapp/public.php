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


// ログイン処理
if (!$_SESSION['loginUserId'] && $_COOKIE['ul']) {
	$tmpData = decryptData($_COOKIE['ul']);
	$expTmpData = explode("_", $tmpData);
	if (is_numeric($expTmpData[1])) {
		$infoTmpData = $objDB->findByIdData('user', $expTmpData[1]);
		if (is_numeric($infoTmpData['user_id']) && $expTmpData[0] == sha1($infoTmpData['user_id']) && $expTmpData[2] == md5($infoTmpData['insert_datetime'])) {
			$_SESSION['loginUserId'] = $infoTmpData['user_id'];
		}
	}
	if (!is_numeric($_SESSION['loginUserId'])) {
		setcookie("ul", "", time() - 3600, "/", DMN_NAME);
	}
}
if (is_numeric($_SESSION['loginUserId'])) {
	$infoLoginUser = $objDB->findByIdData("user", $_SESSION['loginUserId']);
}

