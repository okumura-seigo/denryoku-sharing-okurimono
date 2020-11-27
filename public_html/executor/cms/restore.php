<?php

# パラメータ設定
$arrParam = array(
	"cms" => "システムID",
	"id" => "ID",
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
if (!is_numeric($requestData['cms'])) exit;

// DB
$infoSystem = findByIdSystem($requestData['cms']);
$infoSystem['system_content'] = unserialize($infoSystem['system_content']);
if (!is_numeric($infoSystem['system_id'])) exit;

// 項目取得
$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($infoSystem['system_id'])."'"), array("param_sort Asc", "param_id Asc"));
$arrParam = setParamCms($arrParam, $resParam);
$requestData = getParam($arrParam, $formState);

// データ取得
$infoData = findByIdData($requestData['cms'], $requestData['id']);

// 出力設定
$viewData = viewExtractParam($requestData, $arrParam);
extract($viewData);
if (isset($DELETE_CONFIRM[$requestData['cms']])) {
	require_once $DELETE_CONFIRM[$requestData['cms']];
	exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="content-language" content="en" />
	<meta name="robots" content="noindex,nofollow" />
	<link rel="stylesheet" media="screen,projection" type="text/css" href="../css/reset.css" /> <!-- RESET -->
	<link rel="stylesheet" media="screen,projection" type="text/css" href="../css/main.css" /> <!-- MAIN STYLE SHEET -->
	<link rel="stylesheet" media="screen,projection" type="text/css" href="../css/2col.css" title="2col" /> <!-- DEFAULT: 2 COLUMNS -->
	<link rel="alternate stylesheet" media="screen,projection" type="text/css" href="../css/1col.css" title="1col" /> <!-- ALTERNATE: 1 COLUMN -->
	<!--[if lte IE 6]><link rel="stylesheet" media="screen,projection" type="text/css" href="css/main-ie6.css" /><![endif]--> <!-- MSIE6 -->
	<link rel="stylesheet" media="screen,projection" type="text/css" href="../css/style.css" /> <!-- GRAPHIC THEME -->
	<link rel="stylesheet" media="screen,projection" type="text/css" href="../css/mystyle.css" /> <!-- WRITE YOUR CSS CODE HERE -->
	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../js/switcher.js"></script>
	<script type="text/javascript" src="../js/toggle.js"></script>
	<script type="text/javascript" src="../js/ui.core.js"></script>
	<script type="text/javascript" src="../js/ui.tabs.js"></script>
	<script type="text/javascript" src="../js/wys/scripts/wysiwyg.js"></script>
	<script type="text/javascript" src="../js/wys/scripts/wysiwyg-settings.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$(".tabs > ul").tabs();
	});
	</script>
	<title><?php echo escapeHtml(SITE_NAME) ?> システムマネージャー</title>
</head>

<body>

<div id="main">

	<!-- Tray -->
	<div id="tray" class="box">
<?php require_once './template/header.php' ?>
	</div> <!--  /tray -->

	<hr class="noscreen" />

	<!-- Menu -->
	<div id="menu" class="box">
<?php require_once './template/gmenu.php' ?>
	</div> <!-- /header -->

	<hr class="noscreen" />

	<!-- Columns -->
	<div id="cols" class="box">

		<!-- Aside (Left Column) -->
		<div id="aside" class="box">

			<div class="padding box">
<?php require_once './template/leftside.php' ?>
			</div> <!-- /padding -->


			<ul class="box">
				<li><a href="list.php?cms=<?php echo escapeHtml($infoSystem['system_id']) ?>">一覧</a></li>
<?php if (inArray("insert", $infoSystem['system_content'])) { ?>
				<li><a href="add.php?cms=<?php echo escapeHtml($infoSystem['system_id']) ?>">登録</a></li>
<?php } ?>
<?php if (inArray("csvup", $infoSystem['system_content'])) { ?>
				<li><a href="csvup.php?cms=<?php echo escapeHtml($infoSystem['system_id']) ?>">CSVアップロード</a></li>
<?php } ?>
			</ul>

		</div> <!-- /aside -->

		<hr class="noscreen" />

		<!-- Content (Right Column) -->
		<div id="content" class="box">

			<h1><?php echo escapeHtml($infoSystem['system_title']) ?>管理</h1>
			<h3 class="tit"><?php echo escapeHtml($infoSystem['system_title']) ?>復旧</span></h3>

<?php if (isset($errMsg)) { ?>
<p class="msg warning">
<?php foreach ($errMsg as $val) { ?>
<?php echo escapeHtml($val) ?><br />
<?php } ?>
</p>
<?php } ?>

<table border="0" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
<?php foreach ($resParam as $key => $val) { ?>

<?php if ($val['param_view_info']['delete'] != 'invisible') { ?>
<?php if ($val['param_column'] == "stop_flg") { ?>
  <tr>
    <th bgcolor="#FFFFCC"><?php echo escapeHtml($val['param_name'], $requestData) ?></th>
    <td bgcolor="#FFFFFF">
    	<?php if ($infoData['stop_flg'] == "1") { echo "停止"; } else { echo "稼働中"; } ?>
    </td>
  </tr>
<?php } elseif ($val['param_column'] == "delete_flg") { ?>
  <tr>
    <th bgcolor="#FFFFCC"><?php echo escapeHtml($val['param_name'], $requestData) ?></th>
    <td bgcolor="#FFFFFF">
    	<?php if ($infoData['delete_flg'] == "1") { echo "削除"; } else { echo "稼働中"; } ?>
    </td>
  </tr>
<?php } else { ?>
  <tr>
    <th bgcolor="#FFFFCC"><?php echo escapeHtml($val['param_name'], $requestData) ?></th>
    <td bgcolor="#FFFFFF">
    	<?php echo outputData($val, $infoData) ?>
    </td>
  </tr>
<?php } ?>
<?php } ?>

<?php } ?>
</table>
<form action="restore_exe.php" method="post">
<input type="submit" value="復旧する" />
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
<input type="button" value="キャンセル" onclick="history.back();" />

		</div> <!-- /content -->
	</div> 
	<!-- /cols -->

	<hr class="noscreen" />

	<!-- Footer -->
	<div id="footer" class="box">

		<p class="f-left">&nbsp;</p>

	</div> <!-- /footer -->

</div> <!-- /main -->

</body>
</html>