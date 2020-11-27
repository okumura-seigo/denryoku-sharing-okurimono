<?php

// CMSID設定
$cmsId = "{!cms!}";

# パラメータ設定
$arrParam = array(
	"id" => "ID",
);
// 管理画面文字エンコード
define("APP_ENC", "UTF-8");
// 設定ファイル読み込み
require_once '{!path!}config/cfg.inc.php';
// ライブラリ読み込み
require_once WEB_APP."public.php";

// DB
$infoSystem = findByIdSystem($cmsId);
if (!is_numeric($infoSystem['system_id'])) exit;

// フォーム状態の取得
$formState = getFormState();
// データ取得
$requestData = getParam($arrParam, $formState);
if (!is_numeric($requestData['id'])) exit;

// DB取得
$infoData = findByIdData($cmsId, $requestData['id']);
if (!is_numeric($infoData[DB_COLUMN_HEADER.'id'])) exit;

// 出力設定
extract($requestData);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo escapeHtml($infoSystem['system_title']) ?>詳細 | <?php echo escapeHtml(SITE_NAME) ?></title>
</head>

<body>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
	{!data!}
</table>
</body>
</html>
