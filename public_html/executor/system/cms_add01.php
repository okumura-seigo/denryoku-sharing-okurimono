<?php

# パラメータ設定
$arrParam = array(
	"title" => "タイトル",
	"info" => "項目情報",
	"param_name" => "パラメータ名",
	"param_type" => "パラメータタイプ",
	"param_info" => "詳細情報",
	"param_column" => "カラム名",
	"param_sort" => "順位",
);
// 管理画面文字エンコード
if (!defined('APP_ENC')) define("APP_ENC", "UTF-8");
// 設定ファイル読み込み
require_once '../../../webapp/config/cfg.inc.php';
// ライブラリ読み込み
require_once WEB_APP."system.php";

// フォーム状態の取得
$formState = getFormState();
// データ取得
$requestData = getParam($arrParam, $formState);

// NowID
$nowId = 1;

// エラーチェック
loadValidate('system_add01_val');
$errMsg = systemAdd01Val($requestData, $arrParam);
if (count($errMsg) > 0) {
	require_once 'cms_add.php';
	exit;
}

// 出力設定
$viewData = viewExtractParam($requestData, $arrParam);
extract($viewData);

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

		<p class="f-left box">

			<!-- Switcher -->
			<span class="f-left" id="switcher">
				<a href="#" rel="1col" class="styleswitch ico-col1" title="Display one column"><img src="../design/switcher-1col.gif" alt="1 Column" /></a>
				<a href="#" rel="2col" class="styleswitch ico-col2" title="Display two columns"><img src="../design/switcher-2col.gif" alt="2 Columns" /></a>
			</span>

			Project: <strong><?php echo escapeHtml(SITE_NAME) ?> システムマネージャー</strong>

		</p>

		<p class="f-right">
		User: <strong>System</strong>
		<!--
		 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
		<strong><a href="#" id="logout">Log out</a></strong>
		-->
		</p>

	</div> <!--  /tray -->

	<hr class="noscreen" />

	<!-- Menu -->
	<div id="menu" class="box">
	
		<ul class="box">
			<li id="menu-active"><a href="index.php"><span>CMS管理</span></a></li>
			<li><a href="html.php"><span>HTML編集</span></a></li>
			<li><a href="template.php"><span>テンプレート編集</span></a></li>
			<li><a href="sql.php"><span>SQL発行</span></a></li>
		</ul>

	</div> <!-- /header -->

	<hr class="noscreen" />

	<!-- Columns -->
	<div id="cols" class="box">

		<!-- Aside (Left Column) -->
		<div id="aside" class="box">

			<div class="padding box">

				<!-- Logo (Max. width = 200px) -->
				<p id="logo"><a href="<?php echo escapeHtml(HOME_URL) ?>" target="_blank"><img src="../tmp/logo.gif" alt="Our logo" title="Visit Site" /></a></p>

				<!-- Create a new project -->
				<p id="btn-create" class="box"><a href="<?php echo escapeHtml(HOME_URL) ?>" target="_blank"><span>ユーザー画面を見る</span></a></p>
			</div> <!-- /padding -->


			<ul class="box">
				<li><a href="index.php">一覧</a></li>
				<li><a href="cms_add.php">登録</a></li>
				<li><a href="data.php">データ管理</a></li>
			</ul>

		</div> <!-- /aside -->

		<hr class="noscreen" />

		<!-- Content (Right Column) -->
		<div id="content" class="box">
			<h1>CMS管理</h1>
			<h3 class="tit">CMS登録</span></h3>
			
<?php if (isset($errMsg1) && count($errMsg1) > 0) { ?>
<p class="msg warning">
<?php foreach ($errMsg1 as $key => $val) { ?>
<?php echo escapeHtml($val) ?><br />
<?php } ?>
</p>
<?php } ?>

<form action="cms_add02.php" method="post">
<table>
  <tr>
    <th>CMSタイトル</th>
    <td><?php echo escapeHtml($title) ?></td>
  </tr>
  <tr>
    <th>詳細項目情報</th>
    <td>
    	<span class="explain_msg">
        ※選択型に関しては表示したい項目を情報欄に改行区切りで入力してください<br />
				※外部キーに関しては表示したいCMSIDを情報欄の先頭に入力してください<br />
        ※表示順位は管理画面の項目表示順番となり、番号が被った場合は上のものが優先されます
        </span><br />
        <table>
          <tr>
            <th>項目名</th>
            <th>情報</th>
            <th>表示順位</th>
          </tr>
<?php foreach ($PARAM_TYPE as $key => $val) { ?>
<?php if (isset($info[$key]) && $info[$key] > 0) { ?>
          <tr>
            <td colspan="3">
                ■&nbsp;<?php echo escapeHtml($val) ?>            </td>
          </tr>
<?php for ($i = $nowId;$i < ($nowId + $info[$key]);$i++) { ?>
<?php
if (!is_array($param_name)) $param_name = array();
if (!is_array($param_info)) $param_info = array();
if (!is_array($param_sort)) $param_sort = array();
if (!isset($param_name[$i])) $param_name[$i] = '';
if (!isset($param_info[$i])) $param_info[$i] = '';
if (!isset($param_sort[$i])) $param_sort[$i] = '';
?>
          <tr>
            <td align="center" style="vertical-align:middle"><input type="text" name="param_name[<?php echo escapeHtml($i) ?>]" value="<?php echo escapeHtml($param_name[$i]) ?>" size="20" maxlength="50" /></td>
            <td align="center"><textarea name="param_info[<?php echo escapeHtml($i) ?>]" cols="30" rows="3"><?php echo escapeHtml($param_info[$i]) ?></textarea></td>
            <td align="center" style="vertical-align:middle"><input type="text" name="param_sort[<?php echo escapeHtml($i) ?>]" value="<?php if (!is_numeric($param_sort[$i])) { echo escapeHtml($i); } else { echo escapeHtml($param_sort[$i]); } ?>" size="3" maxlength="3" /></td>
          </tr>
          <input type="hidden" name="param_type[<?php echo escapeHtml($i) ?>]" value="<?php echo escapeHtml($key) ?>" />
          <input type="hidden" name="param_column[<?php echo escapeHtml($i) ?>]" value="<?php echo escapeHtml(DB_COLUMN_HEADER."".$PARAM_TYPE_LINK[$key].($i - $nowId + 1)) ?>" />
<?php } ?>
<?php $nowId = $i; ?>
<?php } ?>
<?php } ?>
        </table>
    </td>
  </tr>
</table>

<input type="submit" value="項目詳細登録" />
<?php foreach ($requestData as $key => $val) { ?>
<?php if (strpos($key, "param_") === false) { ?>
<?php if (!is_array($val)) { ?>
<input type="hidden" name="<?php echo escapeHtml($key) ?>" value="<?php echo escapeHtml($val) ?>" />
<?php } else { ?>
<?php foreach ($val as $key2 => $val2) { ?>
<input type="hidden" name="<?php echo escapeHtml($key) ?>[<?php echo escapeHtml($key2) ?>]" value="<?php echo escapeHtml($val2) ?>" />
<?php } ?>
<?php } ?>
<?php } ?>
<?php } ?>
</form>

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