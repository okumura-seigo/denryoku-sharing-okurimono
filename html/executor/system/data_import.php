<?php

# パラメータ設定
$arrParam = array(
);
// 管理画面文字エンコード
if (!defined('APP_ENC')) define("APP_ENC", "UTF-8");
// 設定ファイル読み込み
require_once '../../../webapp/config/cfg.inc.php';
// ライブラリ読み込み
require_once WEB_APP."cms.php";

// フォーム状態の取得
$formState = getFormState();
// データ取得
$requestData = getParam($arrParam, $formState);

// ファイル取得
$fp = fopen($_FILES['file']['tmp_name'], 'r');
$sqlCode.= "";
while (!feof($fp)) {
	$sqlCode.= fgets($fp);
}

$sqlCode = str_replace("\r", "", $sqlCode);
$expSql = explode("\n---\n", $sqlCode);

mysql_query(mb_convert_encoding($expSql[0], DB_ENC, APP_ENC));
if ($result === false) {
	$errMsg = array("正しいCMSデータではありません");
	require_once 'data.php';
	exit;
}
$expParam = explode("\n", $expSql[1]);
foreach ($expParam as $val) {
	$val = str_replace(DB_TABLE_HEADER."param (", DB_TABLE_HEADER."param (system_id, ", str_replace("Values (", "Values (".mysql_insert_id().", ", $val));
	mysql_query(mb_convert_encoding($val, DB_ENC, APP_ENC));
	if ($result === false) {
		$errMsg = array("処理が途中で中断しました");
		require_once 'data.php';
		exit;
	}
}

$expSql[2] = str_replace(DB_TABLE_HEADER."data", DB_TABLE_HEADER."data".mysql_insert_id(), $expSql[2]);
$mysqlVersion = 1;
if ($mysqlVersion == 1) {
	switch (DB_ENC) {
		case 'SJIS':
			$val = str_replace("CHARACTER SET utf8 COLLATE utf8_general_ci", "CHARACTER SET sjis COLLATE sjis_japanese_ci", $expSql[2]);
			break;
		case 'EUC':
			$val = str_replace("CHARACTER SET utf8 COLLATE utf8_general_ci", "CHARACTER SET ujis COLLATE ujis_japanese_ci", $expSql[2]);
			break;
	}
	$result = mysql_query(mb_convert_encoding($val, DB_ENC, APP_ENC));
	if ($result === false) {
		$mysqlVersion = 0;
		$val = str_replace("CHARACTER SET utf8 COLLATE utf8_general_ci", "", $expSql[2]);
		$result = mysql_query(mb_convert_encoding($val, DB_ENC, APP_ENC));
		if ($result === false) {
			$errMsg = array("処理が途中で中断しました");
			require_once 'data.php';
			exit;
		}
	}
}

header("Location: data.php?success=1");
exit;

?>