<?php

# パラメータ設定
$arrParam = array(
	"cms" => "CMS",
	"name" => "パラメータ名",
	"type" => "型",
	"info" => "情報",
	"system_note" => "フォーム",
	"sort" => "並び順",
	"view_info" => "表示情報",
	"id" => "ID",
	"action" => "action",
	"success" => "success",
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
if (!isset($requestData['action'])) $requestData['action'] = '';

// DB
$infoSystem = findByIdSystem($requestData['cms']);
if (!is_numeric($infoSystem['system_id'])) exit;

// 登録
if ($requestData['action'] == "add") {
	$system_info = unserialize($infoSystem['system_info']);
	$system_info[$PARAM_TYPE_LINK[$requestData['type']]]++;
	$systemData = array("info" => serialize($system_info));

	$paramData = array("system_id" => $requestData['cms'], "param_name" => $requestData['name'], "param_type" => $requestData['type'], "param_info" => $requestData['info']);
	$typeCount = countParam(array("system_id = '".$objDB->quote($requestData['cms'])."'", "param_type = '".$objDB->quote($requestData['type'])."'"));
	$allCount = countParam(array("system_id = '".$objDB->quote($requestData['cms'])."'"));
	$paramData['param_column'] = DB_COLUMN_HEADER."".$PARAM_TYPE_LINK[$requestData['type']].($typeCount + 1).'_'.substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 3);
	$paramData['param_sort'] = ($allCount + 1);

	$tmpParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($infoSystem['system_id'])."'"), array("param_id Asc"));
	$objDB = dbObject();
	$objDB->begin();
	$result = $objDB->query(
		"Alter Table `".getTableName($requestData['cms'])."` Add `".$objDB->quote($paramData['param_column'])."` ".$objDB->quote($PARAM_FIELDS_LINK[$paramData['param_type']])." NOT NULL After `".$objDB->quote($tmpParam[(count($tmpParam) - 1)]['param_column'])."`"
	);
	if ($result !== false) {
		$resultUpdate = updateSystem($systemData, $requestData['cms']);
		$insertParam = $objDB->insertData('param', $paramData);
		if ($resultUpdate === false || $insertParam === false) {
			exit("テーブル操作エラー");
		}
	} else {
		exit("テーブル操作エラー");
	}
	$objDB->commit();
	$objDB->rebaseOrMaps();

	header("Location: param.php?cms=".escapeHtml($requestData['cms'])."&success=1");
	exit;
}

// 変更
if ($requestData['action'] == "chg") {
	foreach ($requestData['sort'] as $key => $val) {
		if (is_numeric($val)) {
			updateParam(array("name" => $requestData['name'][$key], "info" => $requestData['info'][$key], "sort" => $val, "view_info" => serialize($requestData['view_info'][$key])), $key);
			if (isset($_COOKIE['loginSystemAuth']) && $_COOKIE['loginSystemAuth'] == "master") {
				$objDB = dbObject();
				$objDB->query("Update ".DB_TABLE_HEADER."param Set param_system_note = '".$objDB->quote($requestData['system_note'][$key])."' Where param_id = '".$objDB->quote($key)."'");
			}
		}
	}
	$objDB->rebaseOrMaps();
	header("Location: param.php?cms=".escapeHtml($requestData['cms'])."&success=2");
	exit;
}

// 削除
if ($requestData['action'] == "del") {
	$delParam = findByIdParam($requestData['id']);
	$system_info = unserialize($infoSystem['system_info']);
	$system_info[$PARAM_TYPE_LINK[$delParam['param_type']]]--;
	$systemData = array("info" => serialize($system_info));

	$objDB = dbObject();
	$result = $objDB->query(
		"Alter Table `".getTableName($requestData['cms'])."` Drop `".$objDB->quote($delParam['param_column'])."`"
	);
	if ($result !== false) {
		updateSystem($systemData, $requestData['cms']);
		updateDf2tParam($requestData['id']);
	} else {
		exit("テーブル操作エラー");
	}
	$objDB->rebaseOrMaps();

	header("Location: param.php?cms=".escapeHtml($requestData['cms'])."&success=3");
	exit;
}

// 項目取得
$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($infoSystem['system_id'])."'"), array("param_sort Asc", "param_id Asc"));
foreach ($resParam as $key => $val) $resParam[$key]['param_view_info'] = unserialize($val['param_view_info']);

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
				<li><a href="param.php?cms=<?php echo escapeHtml($cms) ?>">パラメータ編集</a></li>
				<li><a href="table.php?cms=<?php echo escapeHtml($cms) ?>">テーブル編集</a></li>
				<li><a href="column.php?cms=<?php echo escapeHtml($cms) ?>">フィールド編集</a></li>
				<li><a href="validate.php?cms=<?php echo escapeHtml($cms) ?>">バリデーション編集</a></li>
			</ul>

		</div> <!-- /aside -->

		<hr class="noscreen" />

		<!-- Content (Right Column) -->
		<div id="content" class="box">

			<h1>CMS管理</h1>
			<h3 class="tit">パラメータ編集</span></h3>

<?php if ($success > 0) { ?>
<p class="msg info">
<?php if ($success == 1) { ?>
項目を登録しました。
<?php } elseif ($success == 2) { ?>
項目を編集しました。
<?php } elseif ($success == 3) { ?>
項目を削除しました。
<?php } ?>
</p>
<?php } ?>

<?php if (isset($errMsg)) { ?>
<p class="msg warning">
<?php foreach ($errMsg as $key => $val) { ?>
<?php echo escapeHtml($val) ?><br />
<?php } ?>
</p>
<?php } ?>

<form action="param.php" method="post" onsubmit="return confirm('項目を追加します');">
<table>
  <tr>
    <th>パラメータ名</th>
	<td><input type="text" name="name" value="<?php echo escapeHtml($name) ?>" /></td>
  </tr>
  <tr>
    <th>型</th>
	<td>
		<select name="type">
<?php foreach ($PARAM_TYPE as $key => $val) { ?>
			<option value="<?php echo escapeHtml($key) ?>" <?php if ($key == $type) echo "selected"; ?>><?php echo escapeHtml($val) ?></option>
<?php } ?>
		</select>
	</td>
  </tr>
  <tr>
    <th>情報</th>
	<td>
	<textarea name="info" cols="30" rows="3"><?php echo escapeHtml($info) ?></textarea>
	</td>
  </tr>
</table>
<input type="hidden" name="cms" value="<?php echo escapeHtml($cms) ?>" />
<input type="hidden" name="action" value="add" />
<input type="submit" value="追加する" />
</form>

<br />
<br />
<input value="バックアップファイルの取得（データCSV）" onclick="location.href='../cms/csvdown.php?cms=<?php echo escapeHtml($cms) ?>'" type="button">
<input value="バックアップファイルの取得（構造SQL）" onclick="location.href='data_export.php?cms=<?php echo escapeHtml($cms) ?>'" type="button">
<form action="param.php" method="post" onsubmit="return confirm('項目を編集します');">
<table>
  <tr>
    <th>パラメータ名</th>
	<th>型</th>
	<th>情報</th>
	<th>表示情報</th>
	<th>並び順</th>
	<th>操作</th>
  </tr>
<?php foreach ($resParam as $key => $val) { ?>
  <tr>
  	<td style="vertical-align:middle"><input type="text" name="name[<?php echo escapeHtml($val['param_id']) ?>]" value="<?php echo escapeHtml($val['param_name']) ?>" />
	</td>
  	<td style="vertical-align:middle"><?php echo escapeHtml($PARAM_TYPE[$val['param_type']]) ?></td>
  	<td style="vertical-align:middle">
	<textarea name="info[<?php echo escapeHtml($val['param_id']) ?>]" cols="30" rows="3"><?php echo escapeHtml($val['param_info']) ?></textarea>
<?php if (isset($_COOKIE['loginSystemAuth']) && $_COOKIE['loginSystemAuth'] == "master") { ?>
	<br />
	<textarea name="system_note[<?php echo escapeHtml($val['param_id']) ?>]" cols="30" rows="3"><?php echo escapeHtml($val['param_system_note']) ?></textarea>
<?php } ?>
	</td>
  	<td style="vertical-align:middle">
		登録 <select name="view_info[<?php echo escapeHtml($val['param_id']) ?>][insert]"><option value="" <?php if ($val['param_view_info']['insert'] == "") echo "selected"; ?>>標準</option><option value="visible" <?php if ($val['param_view_info']['insert'] == "visible") echo "selected"; ?>>表示</option><option value="invisible" <?php if ($val['param_view_info']['insert'] == "invisible") echo "selected"; ?>>非表示</option></select>
		<br style="line-height:25px;">
		編集 <select name="view_info[<?php echo escapeHtml($val['param_id']) ?>][update]"><option value="" <?php if ($val['param_view_info']['update'] == "") echo "selected"; ?>>標準</option><option value="visible" <?php if ($val['param_view_info']['update'] == "visible") echo "selected"; ?>>表示</option><option value="invisible" <?php if ($val['param_view_info']['update'] == "invisible") echo "selected"; ?>>非表示</option></select>
		<br style="line-height:25px;">
		削除 <select name="view_info[<?php echo escapeHtml($val['param_id']) ?>][delete]"><option value="" <?php if ($val['param_view_info']['delete'] == "") echo "selected"; ?>>標準</option><option value="visible" <?php if ($val['param_view_info']['delete'] == "visible") echo "selected"; ?>>表示</option><option value="invisible" <?php if ($val['param_view_info']['delete'] == "invisible") echo "selected"; ?>>非表示</option></select>
		<br style="line-height:25px;">
		詳細 <select name="view_info[<?php echo escapeHtml($val['param_id']) ?>][detail]"><option value="" <?php if ($val['param_view_info']['detail'] == "") echo "selected"; ?>>標準</option><option value="visible" <?php if ($val['param_view_info']['detail'] == "visible") echo "selected"; ?>>表示</option><option value="invisible" <?php if ($val['param_view_info']['detail'] == "invisible") echo "selected"; ?>>非表示</option></select>
	</td>
  	<td style="vertical-align:middle"><input type="text" name="sort[<?php echo escapeHtml($val['param_id']) ?>]" value="<?php echo escapeHtml($val['param_sort']) ?>" size="3" /></td>
  	<td style="vertical-align:middle">
		<input type="button" value="削除する" onclick="if (confirm('このアクションは取り消せません\n本当に削除しますか？')) { location.href='param.php?cms=<?php echo escapeHtml($cms) ?>&action=del&id=<?php echo escapeHtml($val['param_id']) ?>'; }" />
	</td>
  </tr>
<?php } ?>
</table>
<?php if (isset($_COOKIE['loginSystemAuth']) && $_COOKIE['loginSystemAuth'] == "master") { ?>
{name}
{value}
{selected}
{checked}
{array_checked}
<br />
<?php } ?>
<input type="hidden" name="action" value="chg" />
<input type="hidden" name="cms" value="<?php echo escapeHtml($cms) ?>" />
<input type="submit" value="編集する" />
<br />

<p>※情報を変更しても既存データは変更されないので、項目名を編集する際はデータも更新してください</p>
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
