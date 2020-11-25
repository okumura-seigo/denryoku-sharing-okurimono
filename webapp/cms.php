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
loadLogic('markdown');


// ログインチェック
if (!isset($_SESSION['loginSystemId']) && !isset($_SESSION['loginCmsId'])) {
	header("Location: login.php");
	exit;
}

//if (isset($_SESSION['loginSystemId']) && $_SESSION['loginSystemId'] != true) {
	if (isset($_SESSION['loginCmsId']) && $_SESSION['loginCmsId'] != -1) {
		if ($_SESSION['loginCmsId'] > 0) {
			$infoAdministrator = $objDB->findByIdData('administrator', $_SESSION['loginCmsId']);
			if (!is_numeric($infoAdministrator['administrator_id'])) {
				header("Location: login.php");
				exit;
			}
		}
	}
//}

// DB
$whereArray = array("system_stop_flg = 0", "system_delete_flg = 0", "system_id != 19");
if ($infoAdministrator['permission'] != "1") $whereArray[] = "system_id != 2";
$resCMS = $objDB->findData(
	DB_TABLE_HEADER."system",
	array(
		"where" => $whereArray,
		"order" => array("system_sort", "system_id")
	)
);
if ($_REQUEST['cms'] == "2" && $infoAdministrator['permission'] != "1") {
	exit;
}

?>