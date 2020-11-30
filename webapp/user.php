<?php

// セッションの有無
define( 'SESSION_USED', true );
// セッションIDの固定化
define( 'SESSION_FIXED', false );
// DBの常時接続
define( 'DB_FULL_CONNECT', true );

// 共通モジュール
require_once 'common.php';


// 個別モジュール


// ログインチェック
if (is_numeric($_SESSION['loginUserId'])) {
	$infoLoginUser = $objDB->findByIdData('user', $_SESSION['loginUserId']);
	if (!is_numeric($infoLoginUser['user_id'])) {
		session_destroy();
		redirectUrl(HOME_URL.'sign-in/');
	}
} else {
	session_destroy();
	redirectUrl(HOME_URL.'sign-in/');
}

