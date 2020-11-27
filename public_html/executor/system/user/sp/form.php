<?php

// CMSID設定
$cmsId = "{!cms!}";

# パラメータ設定
$arrParam = array(
{!param_array!}
);
// 管理画面文字エンコード
define("APP_ENC", "UTF-8");
// 設定ファイル読み込み
require_once '{!path!}config/cfg.inc.php';
// ライブラリ読み込み
require_once WEB_APP."public.php";

// フォーム状態の取得
$formState = getFormState();
// データ取得
$requestData = getParam($arrParam, $formState);

// 出力設定
extract($requestData);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo escapeHtml($infoSystem['system_title']) ?>フォーム | <?php echo escapeHtml(SITE_NAME) ?></title>
</head>

<body>

<?php if (count($errMsg) > 0) { ?>
<div style="color:#FF0000">
<?php foreach ($errMsg as $val){ ?>
・<?php echo escapeHtml($val) ?><br />
<?php } ?>
</div>
<?php } ?>
<form action="confirm.php" method="post" enctype="multipart/form-data">
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
{!form!}
</table>
<input type="submit" value="確認する" />
</form>
</body>
</html>
