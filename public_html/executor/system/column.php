<?php

# パラメータ設定
$arrParam = array(
	"cms" => "CMS",
	"check" => "変更",
	"column" => "カラム名",
	"type" => "型",
	"length" => "長さ",
	"default" => "デフォルト",
	"null" => "NULL",
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
if ($requestData['column'] == '') $requestData['column'] = array();
if ($requestData['type'] == '') $requestData['type'] = array();
if ($requestData['length'] == '') $requestData['length'] = array();
if ($requestData['default'] == '') $requestData['default'] = array();
if ($requestData['null'] == '') $requestData['null'] = array();

// DB
$infoSystem = findByIdSystem($requestData['cms']);
if (!is_numeric($infoSystem['system_id'])) exit;

// 変更
if ($requestData['action'] == "chg") {
	// エラーチェック
	$errMsg = array();
	$uniqColumn = array();
	foreach ($requestData['column'] as $key => $val) {
		if (inArray($key, $requestData['check']) === false) continue;
		if (DB_COLUMN_HEADER != substr($val, 0, strlen(DB_COLUMN_HEADER))) {
			$errMsg[] = "カラムヘッダーが正しくありません";
			break;
		}
		if (inArray($val, $uniqColumn)) {
			$errMsg[] = "同じカラム名が存在しています";
			break;
		}
		$uniqColumn[] = $val;
	}
	
	if (count($errMsg) == 0) {
		$objDB->begin();
		foreach ($requestData['column'] as $key => $val) {
			if (inArray($key, $requestData['check']) == false) continue;
			$infoParam = findByIdParam($key);
			
			// 長さ
			$lengthQuery = ($requestData['length'][$key] == "") ? "" : " (".$requestData['length'][$key].") ";
			if ($requestData['type'][$key] == "TEXT") $lengthQuery = "";
			if ($requestData['type'][$key] == "MEDIUMTEXT") $lengthQuery = "";
			if ($requestData['type'][$key] == "TINYTEXT") $lengthQuery = "";
			if ($requestData['type'][$key] == "LONGTEXT") $lengthQuery = "";
			
			// NULL
			$nullQuery = (!isset($requestData['null'][$key])) ? " NOT NULL " : " NULL ";
			
			// デフォルト
			$defaultQuery = ($requestData['default'][$key] == "") ? "" : " DEFAULT '".$requestData['default'][$key]."' ";
			
			$result = $objDB->query(
				"ALTER TABLE `".getTableName($requestData['cms'])."` CHANGE `".$objDB->quote($infoParam['param_column'])."` `".$objDB->quote($val)."` ".$objDB->quote($requestData['type'][$key]).$lengthQuery.$nullQuery.$defaultQuery
			);
			
			$res = $objDB->query("
				Select
					COLUMN_NAME as column_name,
					DATA_TYPE as data_type,
					COLUMN_DEFAULT as column_default,
					IS_NULLABLE as is_nullable,
					CHARACTER_MAXIMUM_LENGTH as character_maximum_length
				From
					information_schema.COLUMNS
				Where
					TABLE_SCHEMA = '".$objDB->quote(DB_NAME)."'
				And
					TABLE_NAME = '".getTableName($requestData['cms'])."'
				And
					COLUMN_NAME = '".$objDB->quote($val)."'
			");
			if ($res[0]['column_name']) {
				updateParam(array("column" => $val), $key);
			}
		}
		$objDB->commit();
		
		$objDB->rebaseOrMaps();
		
		$checkParam = "";
		foreach ($requestData['check'] as $checkkey => $checkval) $checkParam.= "&check[".rawurlencode($checkkey)."]=".rawurlencode($checkval);
		header("Location: column.php?cms=".escapeHtml($requestData['cms'])."&success=2".$checkParam);
		exit;
	}
}

// 項目取得
$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($infoSystem['system_id'])."'"), array("param_sort Asc", "param_id Asc"));
$res = $objDB->query("
	Select
		COLUMN_NAME as column_name,
		DATA_TYPE as data_type,
		COLUMN_DEFAULT as column_default,
		IS_NULLABLE as is_nullable,
		CHARACTER_MAXIMUM_LENGTH as character_maximum_length,
		NUMERIC_PRECISION as numeric_precision,
		NUMERIC_SCALE as numeric_scale
	From
		information_schema.COLUMNS
	Where
		TABLE_SCHEMA = '".$objDB->quote(DB_NAME)."'
	And
		TABLE_NAME = '".getTableName($requestData['cms'])."'
");
$columnData = array();
foreach ($res as $val) $columnData[$val['column_name']] = $val;

foreach ($resParam as $val) {
	$requestData['column'][$val['param_id']] = $val['param_column'];
	$requestData['type'][$val['param_id']] = $columnData[$val['param_column']]['data_type'];
	if ($columnData[$val['param_column']]['data_type'] == 'int' || $columnData[$val['param_column']]['data_type'] == 'double' || $columnData[$val['param_column']]['data_type'] == 'float') {
		$requestData['length'][$val['param_id']] = $columnData[$val['param_column']]['numeric_precision'];
	} elseif ($columnData[$val['param_column']]['data_type'] == 'decimal') {
		$requestData['length'][$val['param_id']] = $columnData[$val['param_column']]['numeric_precision'].', '.$columnData[$val['param_column']]['numeric_scale'];
	} else {
		$requestData['length'][$val['param_id']] = $columnData[$val['param_column']]['character_maximum_length'];
	}
	$requestData['default'][$val['param_id']] = $columnData[$val['param_column']]['column_default'];
	$requestData['null'][$val['param_id']] = $columnData[$val['param_column']]['is_nullable'];
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
			<h3 class="tit">フィールド編集</span></h3>

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


<form action="column.php" method="post" onsubmit="return confirm('項目を編集します');">
<input type="button" value="全てを変更項目にする" onclick="$('.checkitems').attr('checked', true);">
<input type="button" value="全てを変更項目からはずす" onclick="$('.checkitems').attr('checked', false);">
<table>
  <tr>
    <th>変更</th>
    <th>パラメータ名</th>
	<th>カラム名</th>
	<th>型</th>
	<th>長さ</th>
	<th>デフォルト値</th>
	<th>NULL</th>
  </tr>
<?php foreach ($resParam as $key => $val) { ?>
  <tr>
  	<td align="center"><input type="checkbox" name="check[<?php echo escapeHtml($val['param_id']) ?>]" class="checkitems" value="<?php echo escapeHtml($val['param_id']) ?>" <?php if (isset($check[$val['param_id']]) && $check[$val['param_id']] == $val['param_id']) echo "checked"; ?> /></td>
  	<td><?php echo escapeHtml($val['param_name']) ?></td>
  	<td><input type="text" name="column[<?php echo escapeHtml($val['param_id']) ?>]" value="<?php echo escapeHtml($column[$val['param_id']]) ?>" /></td>
  	<td>
<select name="type[<?php echo escapeHtml($val['param_id']) ?>]">
<option <?php if (strtoupper($type[$val['param_id']]) == "INT") echo "selected"; ?>>INT</option>
<option <?php if (strtoupper($type[$val['param_id']]) == "VARCHAR") echo "selected"; ?>>VARCHAR</option>
<option <?php if (strtoupper($type[$val['param_id']]) == "TEXT") echo "selected"; ?>>TEXT</option>
<option <?php if (strtoupper($type[$val['param_id']]) == "DATE") echo "selected"; ?>>DATE</option>
<option <?php if (strtoupper($type[$val['param_id']]) == "TINYINT") echo "selected"; ?>>TINYINT</option>
<option <?php if (strtoupper($type[$val['param_id']]) == "SMALLINT") echo "selected"; ?>>SMALLINT</option>
<option <?php if (strtoupper($type[$val['param_id']]) == "MEDIUMINT") echo "selected"; ?>>MEDIUMINT</option>
<option <?php if (strtoupper($type[$val['param_id']]) == "BIGINT") echo "selected"; ?>>BIGINT</option>
<option <?php if (strtoupper($type[$val['param_id']]) == "FLOAT") echo "selected"; ?>>FLOAT</option>
<option <?php if (strtoupper($type[$val['param_id']]) == "DOUBLE") echo "selected"; ?>>DOUBLE</option>
<option <?php if (strtoupper($type[$val['param_id']]) == "DECIMAL") echo "selected"; ?>>DECIMAL</option>
<option <?php if (strtoupper($type[$val['param_id']]) == "DATE") echo "selected"; ?>>DATE</option>
<option <?php if (strtoupper($type[$val['param_id']]) == "DATETIME") echo "selected"; ?>>DATETIME</option>
<option <?php if (strtoupper($type[$val['param_id']]) == "TIME") echo "selected"; ?>>TIME</option>
<option <?php if (strtoupper($type[$val['param_id']]) == "CHAR") echo "selected"; ?>>CHAR</option>
<option <?php if (strtoupper($type[$val['param_id']]) == "TINYTEXT") echo "selected"; ?>>TINYTEXT</option>
<option <?php if (strtoupper($type[$val['param_id']]) == "TEXT") echo "selected"; ?>>TEXT</option>
<option <?php if (strtoupper($type[$val['param_id']]) == "MEDIUMTEXT") echo "selected"; ?>>MEDIUMTEXT</option>
<option <?php if (strtoupper($type[$val['param_id']]) == "LONGTEXT") echo "selected"; ?>>LONGTEXT</option>
</select>
	</td>
  	<td><input type="text" name="length[<?php echo escapeHtml($val['param_id']) ?>]" value="<?php echo escapeHtml($length[$val['param_id']]) ?>" size="10" /></td>
  	<td><input type="text" name="default[<?php echo escapeHtml($val['param_id']) ?>]" value="<?php echo escapeHtml($default[$val['param_id']]) ?>" size="15" /></td>
  	<td align="center"><input type="checkbox" name="null[<?php echo escapeHtml($val['param_id']) ?>]" value="YES" <?php if ($null[$val['param_id']] == "YES") echo "checked"; ?> /></td>
  </tr>
<?php } ?>
</table>
<input type="hidden" name="action" value="chg" />
<input type="hidden" name="cms" value="<?php echo escapeHtml($cms) ?>" />
<input type="submit" value="編集する" />
</form>
※変更されない場合はSQLで無理な変更を加えている可能性があります



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