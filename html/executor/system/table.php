<?php

# パラメータ設定
$arrParam = array(
	"cms" => "CMS",
	"table" => "テーブル名",
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

// 変更
if ($requestData['action'] == "chg") {
	// エラーチェック
	$errMsg = array();
	if (DB_TABLE_HEADER != substr($requestData['table'], 0, strlen(DB_TABLE_HEADER))) $errMsg[] = "テーブルヘッダーが正しくありません";
	$resDB = findEZSystem(array("system_table = '".$objDB->quote($requestData['table'])."'"));
	if (is_numeric($resDB[0]['system_id'])) $errMsg[] = "このテーブルは既に存在しています";
	
	if (count($errMsg) == 0) {
		// テーブル操作
		$objDB->begin();
		$result = $objDB->query(
			"RENAME TABLE `".$objDB->quote(getTableName($requestData['cms']))."` TO `".$objDB->quote($requestData['table'])."`",
			array(),
			null,
			MDB2_PREPARE_MANIP
		);
		if ($result === false) {
			exit("テーブル操作エラー");
		}
		$result = $objDB->query(
			"ALTER TABLE `".$objDB->quote($requestData['table'])."` CHANGE `".$objDB->quote(getTableName($requestData['cms']))."_id` `".$objDB->quote($requestData['table'])."_id` int AUTO_INCREMENT"
		);
		if ($result === false) {
			exit("テーブル操作エラー");
		}
		updateSystem(array("table" => $requestData['table']), $requestData['cms']);

		$objDB->commit();

		$objDB->rebaseOrMaps();

		header("Location: table.php?cms=".escapeHtml($requestData['cms'])."&success=2");
		exit;
	}
}

$requestData['table'] = getTableName($requestData['cms']);

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
			<h3 class="tit">テーブル編集</span></h3>

			<?php if ($success > 0) { ?>
<p class="msg info">
<?php if ($success == 2) { ?>
項目を編集しました。
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


<form action="table.php" method="post" onsubmit="return confirm('項目を編集します');">
<table>
  <tr>
    <th>テーブル名</th>
  	<td><input type="text" name="table" value="<?php echo escapeHtml($table) ?>" /></td>
  </tr>
</table>
<input type="hidden" name="action" value="chg" />
<input type="hidden" name="cms" value="<?php echo escapeHtml($cms) ?>" />
<input type="submit" value="編集する" />
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