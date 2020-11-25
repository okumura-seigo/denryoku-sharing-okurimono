<?php

# パラメータ設定
$arrParam = array(
	"cms" => "システムID",
	"id" => "ID",
	"column" => "カラム名",
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
if (!isset($requestData)) $requestData = getParam($arrParam, $formState);
if (!is_numeric($requestData['cms'])) exit;

// DB
$infoSystem = findByIdSystem($requestData['cms']);
$infoSystem['system_content'] = unserialize($infoSystem['system_content']);
if (!is_numeric($infoSystem['system_id'])) exit;
$infoData = findByIdData($requestData['cms'], $requestData['id']);
if (!is_numeric($infoData[$infoSystem['system_table'].'_id'])) exit;

updateData(
	$requestData['cms'],
	array(
		optParamName($requestData['column']) => "",
	),
	$requestData['id']
);
unlink(UPLOAD_FILE_DIR.$infoData[$requestData['column']]);

// 出力設定
$viewData = viewExtractParam($requestData, $arrParam);
extract($viewData);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CMS画面</title>
<style>
* {
font-size: 12px;
}
</style>
</head>

<body>
ファイルを削除しました<br />
<br />
<br />
<a href="javascript:window.close();">閉じる</a>
</body>
</html>
