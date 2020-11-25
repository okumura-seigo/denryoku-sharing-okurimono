<?php

// CMSID設定
$cmsId = "{!cms!}";

/* 設定ここから *******************************************/
# 検索設定
$searchParam = array(
	{!sort!}
);
/* 設定ここまで *******************************************/

# パラメータ設定
$arrParam = array(
	"q" => "クエリ",
	"o" => "並び替え",
	"st" => "並び順",
	"lim" => "ページャー",
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
$requestData = getParam(array_merge($arrParam, $searchParam), $formState);
if (!checkData($requestData['lim'], "natural")) $requestData['lim'] = 0;

// DB取得
list($resData, $maxRows, $pager) = getCmsList($cmsId, $requestData, $searchParam);

// 出力設定
extract($requestData);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo escapeHtml($infoSystem['system_title']) ?>一覧 | <?php echo escapeHtml(SITE_NAME) ?></title>
</head>

<body>

<h1></h1>
<div class="pager">
<?php echo $pager ?>
</div>
<?php foreach ($resData as $key => $val) { ?>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
	{!data!}
	<tr>
		<th bgcolor="#FFFFCC">詳細ページ</th>
	    <td bgcolor="#FFFFFF">
    		<a href="detail.php?id=<?php echo escapeHtml($val[DB_COLUMN_HEADER.'id']) ?>">詳細</a>
		</td>
	</tr>
</table>
<br />
<?php } ?>
<div class="pager">
<?php echo $pager ?>
</div>

</body>
</html>
