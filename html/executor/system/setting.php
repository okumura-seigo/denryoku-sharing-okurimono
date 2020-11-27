<?php

# パラメータ設定
$arrParam = array(
	"cms" => "CMS",
	"title" => "タイトル",
	"list_view" => "一覧表示",
	"list_sort" => "並び順",
	"list_sort_type" => "並び方",
	"content" => "コンテンツ",
	"csv_key" => "CSVキー",
	"sort" => "並び順",
	"action" => "action",
	"result" => "result",
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

// 項目取得
$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($infoSystem['system_id'])."'"), array("param_sort Asc", "param_id Asc"));

// アクション
if ($requestData['action'] == "setting") {
	$listView = array();
	foreach ($requestData['list_view'] as $key => $val) {
		if (is_numeric($val)) {
			$listView[$val] = $key;
		}
	}
	ksort($listView);
	$requestData['list_view'] = serialize($listView);
	$requestData['list_sort'].= " ".$requestData['list_sort_type'];
	$requestData['content'] = serialize($requestData['content']);
	updateSystem($requestData, $requestData['cms']);
	header("Location: setting.php?result=1&cms=".rawurlencode($requestData['cms']));
	exit;
}

// 初期値
$requestData['title'] = $infoSystem['system_title'];
$requestData['csv_key'] = $infoSystem['system_csv_key'];
$requestData['content'] = unserialize($infoSystem['system_content']);
$expSort = explode(" ", $infoSystem['system_list_sort']);
$listView = (!$infoSystem['system_list_view']) ? array() : unserialize($infoSystem['system_list_view']);
$requestData['list_view'] = array();
foreach ($listView as $key => $val) {
	$requestData['list_view'][$val] = $key;
}
if (count($requestData['list_view']) == 0) {
	foreach ($resParam as $key => $val) {
		$requestData['list_view'][$val['param_id']] = $key + 1;
		if ($key == 4) break;
	}
}
$requestData['list_sort'] = $expSort[0];
$requestData['list_sort_type'] = $expSort[1];
$requestData['sort'] = $infoSystem['system_sort'];

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
			<h3 class="tit">CMS設定変更</span></h3>

<?php if ($result == "1") { ?>
<p class="msg info">
設定を変更しました
</p>
<?php } ?>

<?php if (isset($errMsg)) { ?>
<p class="msg warning">
<?php foreach ($errMsg as $key => $val) { ?>
<?php echo escapeHtml($val) ?><br />
<?php } ?>
</p>
<?php } ?>

<form action="setting.php" method="post" onsubmit="return confirm('設定を変更します');">
<table>
  <tr>
    <th>CMSタイトル</th>
    <td><input name="title" type="text" id="title" value="<?php echo escapeHtml($title) ?>" size="60" maxlength="100" /></td>
  </tr>
  <tr>
    <th>CMS管理一覧表示項目</th>
    <td>
<?php foreach ($resParam as $key => $val) { ?>
			<input type="text" name="list_view[<?php echo escapeHtml($val['param_id']) ?>]" value="<?php if (isset($list_view[$val['param_id']])) echo escapeHtml($list_view[$val['param_id']]) ?>" size="3" /><?php echo escapeHtml($val['param_name']) ?><br />
<?php } ?>
    </td>
  </tr>
  <tr>
    <th>CMS管理一覧標準ソート</th>
    <td>
			<select name="list_sort">
				<option value="<?php echo h(getIdColumn($infoSystem['system_table'])) ?>" <?php if ($list_sort == getIdColumn($infoSystem['system_table'])) echo "selected"; ?>>ID</option>
<?php foreach ($resParam as $key => $val) { ?>
				<option value="<?php echo escapeHtml($val['param_column']) ?>" <?php if ($list_sort == $val['param_column']) echo "selected"; ?>><?php echo escapeHtml($val['param_name']) ?></option>
<?php } ?>
			</select>
			<select name="list_sort_type">
				<option value="Desc" <?php if ($list_sort_type == "Desc") echo "selected"; ?>>降順</option>
				<option value="Asc" <?php if ($list_sort_type == "Asc") echo "selected"; ?>>昇順</option>
			</select>		</td>
  </tr>
  <tr>
    <th>CMSコンテンツ</th>
    <td>
		<input name="content[]" type="checkbox" value="list" checked="checked" disabled="disabled" /> 一覧<br />
		<input name="content[]" type="checkbox" value="insert" <?php if (inArray("insert", $content)) echo "checked"; ?> /> 登録<br />
		<input name="content[]" type="checkbox" value="update" <?php if (inArray("update", $content)) echo "checked"; ?> /> 編集<br />
		<input name="content[]" type="checkbox" value="stop" <?php if (inArray("stop", $content)) echo "checked"; ?> /> 停止<br />
		<input name="content[]" type="checkbox" value="delete" <?php if (inArray("delete", $content)) echo "checked"; ?> /> 削除<br />
		<input name="content[]" type="checkbox" value="erasure" <?php if (inArray("erasure", $content)) echo "checked"; ?> /> 物理削除<br />
		<input name="content[]" type="checkbox" value="csvup" <?php if (inArray("csvup", $content)) echo "checked"; ?> /> CSVアップロード<br />
		<input name="content[]" type="checkbox" value="csvdown" <?php if (inArray("csvdown", $content)) echo "checked"; ?> /> CSVダウンロード<br />
		<input name="content[]" type="checkbox" value="crawler" <?php if (inArray("crawler", $content)) echo "checked"; ?> /> クローラー<br />		</td>
  </tr>
  <tr>
    <th>CSVキー</th>
    <td>
			<select name="csv_key">
				<option value=""></option>
<?php foreach ($resParam as $key => $val) { ?>
				<option value="<?php echo escapeHtml($val['param_column']) ?>" <?php if ($csv_key == $val['param_column']) echo "selected"; ?>><?php echo escapeHtml($val['param_name']) ?></option>
<?php } ?>
			</select>
			<br />
			※ID以外のユニーク項目		</td>
  </tr>
  <tr>
  		<th>並び順</th>
  		<td><input name="sort" type="text" id="sort" value="<?php echo escapeHtml($sort) ?>" size="3" maxlength="3" /></td>
  		</tr>
  <tr>
    <th>クローラー</th>
    <td>
		<textarea style="width:500px; font-size:12px;"><?php echo escapeHtml(HOME_URL."executor/cms/crawler.php?cms=".$cms) ?><?php foreach ($resParam as $key => $val) { echo "&".optParamName($val['param_column'])."={".$val['param_name']."}"; } ?></textarea><br />
		※encパラメータにSJIS、EUC、UTF-8を設定することで文字コードが変更（デフォルトはSJIS）	</td>
  </tr>
</table>
<input type="hidden" name="cms" value="<?php echo escapeHtml($cms) ?>" />
<input type="hidden" name="action" value="setting" />
<input type="submit" value="設定変更" />
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