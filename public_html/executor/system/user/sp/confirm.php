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

// エラーチェック
$errMsg = userCmsAddVal($requestData, $arrParam, $cmsId);
if (count($errMsg) > 0) {
	require_once 'form.php';
	exit;
}

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
下記項目で送信します。確認してください。<br />
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
{!confirm!}
</table>
<form action="thanks.php" method="post">
<input type="submit" value="送信する" />
<?php foreach ($requestData as $key => $val) { ?>
<?php if (!is_array($val)) { ?>
<input type="hidden" name="<?php echo escapeHtml($key) ?>" value="<?php echo escapeHtml($val) ?>" />
<?php } else { ?>
<?php foreach ($val as $key2 => $val2) { ?>
<input type="hidden" name="<?php echo escapeHtml($key) ?>[<?php echo escapeHtml($key2) ?>]" value="<?php echo escapeHtml($val2) ?>" />
<?php } ?>
<?php } ?>
<?php } ?>
</form>
<form action="form.php" method="post">
<input type="submit" value="戻る" />
<?php foreach ($requestData as $key => $val) { ?>
<?php if (!is_array($val)) { ?>
<input type="hidden" name="<?php echo escapeHtml($key) ?>" value="<?php echo escapeHtml($val) ?>" />
<?php } else { ?>
<?php foreach ($val as $key2 => $val2) { ?>
<input type="hidden" name="<?php echo escapeHtml($key) ?>[<?php echo escapeHtml($key2) ?>]" value="<?php echo escapeHtml($val2) ?>" />
<?php } ?>
<?php } ?>
<?php } ?>
</form>
</body>
</html>
