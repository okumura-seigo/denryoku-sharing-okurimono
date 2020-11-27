<?php

# パラメータ設定
$arrParam = array(
	"sql" => "SQL",
	"type" => "TYPE",
	"tbl" => "TABLE",
	"force" => "FORCE",
	"action" => "ACTION",
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

// 変更
if ($requestData['action'] == "execute") {
	$forceFlg = false;
	if ($requestData['force'] != "1") {
		if (strpos(strtolower($requestData['sql']), "delete") !== false || strpos(strtolower($requestData['sql']), "drop") !== false || strpos(strtolower($requestData['sql']), "truncate") !== false) {
			$forceFlg = true;
		}
	}
	if ($forceFlg == false) {
		$link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		$result = mysqli_query($link, $requestData['sql']);
		$resDB = array();
		if ($result) {
			while ($row = mysqli_fetch_assoc($result)) {
				$resDB[] = $row;
			}
		}
		$error = "";
		if ($resDB == false) {
			$dbError = mysqli_error($link);
		}
	} else {
		$errMsg = array("使用できない文字が入っています");
	}
}

$resTable = findEZData(DB_TABLE_HEADER."system", array(), array("system_id"));

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
	<script type="text/javascript" src="../js/dragscroll/jquery.dragscroll.js"></script>
	<script type="text/javascript">
	  $(document).ready(function () {
		$('#scroll_table').dragScroll();
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
			<li><a href="index.php"><span>CMS管理</span></a></li>
			<li><a href="html.php"><span>HTML編集</span></a></li>
			<li><a href="template.php"><span>テンプレート編集</span></a></li>
			<li id="menu-active"><a href="sql.php"><span>SQL発行</span></a></li>
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
				<li><a href="sql.php">SQLクエリ</a></li>
			</ul>

		</div> <!-- /aside -->

		<hr class="noscreen" />

		<!-- Content (Right Column) -->
		<div id="content" class="box">

			<h1>CMS管理</h1>
			

<?php if ($success > 0) { ?>
<p class="msg info">
<?php if ($success == 2) { ?>
SQLを実行しました。
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

<h3 class="tit">SQLクエリ</h3>
<form action="sql.php" method="post">
<table style="width:100%; border:none;">
<tr style="border:none;">
<td style="width:40%; border:none;">
<textarea name="sql" style="width:100%; height:150px;" id="sql"><?php echo escapeHtml($sql) ?></textarea><br />
<input type="submit" value="　　クエリ実行　　" />
<input type="button" value="クエリ削除" onclick="$('#sql').val('')" />
<input type="hidden" name="action" value="execute" />
</td>
<td style="border:none; vertical-align:top;">
<div style="margin-bottom:5px;">
<p style="background-color:#EEE; margin:0; font-weight:bold; padding:2px 5px;">テーブル</p>
<?php foreach ($resTable as $key => $val) { ?>
<label><input type="radio" name="tbl" value="<?php echo escapeHtml($val["system_table"]) ?>" <?php if ($tbl == $val["system_table"]) echo "checked"; ?> onclick="createSQL()" /><?php echo escapeHtml($val["system_title"]) ?>(<?php echo escapeHtml($val["system_table"]) ?>)</label>
<?php } ?>
</div>
<div style="margin-bottom:5px;">
<p style="background-color:#EEE; margin:0; font-weight:bold; padding:2px 5px;">用途</p>
<label><input type="radio" name="type" value="1" <?php if ($type == "1") echo "checked"; ?> onclick="createSQL()" />Select</label>
<label><input type="radio" name="type" value="2" <?php if ($type == "2") echo "checked"; ?> onclick="createSQL()" />Show Index</label>
<label><input type="radio" name="type" value="3" <?php if ($type == "3") echo "checked"; ?> onclick="createSQL()" />Create Index</label>
<label><input type="radio" name="type" value="4" <?php if ($type == "4") echo "checked"; ?> onclick="createSQL()" />Show Triggers</label>
<label><input type="radio" name="type" value="5" <?php if ($type == "5") echo "checked"; ?> onclick="createSQL()" />Create Triggers</label>
</div>
</td>
</table>
<input type="hidden" name="force" value="<?php echo escapeHtml($force) ?>" />
</form>

<script>
function createSQL() {
	if ($('input[name=type]:checked').val() !== undefined && $('input[name=tbl]:checked').val() !== undefined) {
		var sql = "";
		if ($('input[name=type]:checked').val() == "1") {
			sql = "Select * From " + $('input[name=tbl]:checked').val() + ";";
		} else if ($('input[name=type]:checked').val() == "2") {
			sql = "SELECT TABLE_NAME,INDEX_NAME,COLUMN_NAME,NON_UNIQUE,INDEX_TYPE,CARDINALITY,SEQ_IN_INDEX FROM information_schema.STATISTICS  WHERE TABLE_SCHEMA = '<?php echo escapeHtml(DB_NAME) ?>'  AND TABLE_NAME = '" + $('input[name=tbl]:checked').val() + "' ORDER BY TABLE_SCHEMA,TABLE_NAME,INDEX_NAME,SEQ_IN_INDEX;";
		} else if ($('input[name=type]:checked').val() == "3") {
			sql = "Create Index idx<?php echo escapeHtml(date("YmdHis")) ?> On " + $('input[name=tbl]:checked').val() + "(id);";
		} else if ($('input[name=type]:checked').val() == "4") {
			sql = "Show Triggers;";
		} else if ($('input[name=type]:checked').val() == "5") {
			sql = "Create Trigger trg<?php echo escapeHtml(date("YmdHis")) ?> Before Insert On " + $('input[name=tbl]:checked').val() + " For Each Row\nBegin\nUpdate test Set num = num + 1 Where id = NEW.id;\nEnd;";
		}
		$('#sql').val(sql);
	}
}
</script>


<?php if ($action == "execute") { ?>
<div style="margin-top:20px;">
<h3 class="tit">実行結果</h3>
<p class="msg done"><?php echo escapeHtml($sql) ?></p>
</div>
<?php if (!isset($dbError)) { ?>
<table id="scroll_table" style="display:block">
  <tr>
<?php foreach ($resDB[0] as $item => $v) { ?>
    <th><?php echo escapeHtml($item) ?></th>
<?php } ?>
  </tr>
<?php foreach ($resDB as $key => $val) { ?>
  <tr>
<?php foreach ($val as $key2 => $val2) { ?>
    <td><?php echo escapeHtml($val2) ?></td>
<?php } ?>
  </tr>
<?php } ?>
</table>
<?php } else { ?>
<span style="font-weight:bold; color:#FF0000;"><?php echo escapeHtml($dbError) ?></span>
<?php } ?>
<?php } ?>


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