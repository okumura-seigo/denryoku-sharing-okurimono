<?php

// 設定
set_time_limit(0);
ini_set("memory_limit", -1);
ini_set("upload_max_filesize", "10M");
ini_set("post_max_size", "10M");

# パラメータ設定
$arrParam = array(
	"cms" => "システムID",
	"action" => "action",
	"success" => "success",
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

// エラーチェック
$errMsg = cmsAddVal($requestData, $arrParam, $resParam, true);
if (count($errMsg) > 0) {
	exit;
}

// 登録
if ($requestData['action'] == "upload") {
	if (isset($CSVUP_EXECUTE[$requestData['cms']]) && $CSVUP_EXECUTE[$requestData['cms']] != "") {
		require_once $CSVUP_EXECUTE[$requestData['cms']];
		exit;
	}
	if ($_FILES['csv']['tmp_name'] != "") {
		$errMsg = array();

		$uniqArray = array();
		$dataArray = array("id");
		$typeArray = array("1");
		foreach ($resParam as $key2 => $val2) {
			$dataArray[] = $val2['param_column'];
			$typeArray[] = $val2['param_type'];
		}
		$typeArray[] = "1";
		$typeArray[] = "1";
		$handle = fopen($_FILES['csv']['tmp_name'], "r");
		fgetcsv_reg($handle);

		$objDB->begin();
		while (($data = fgetcsv_reg($handle)) !== false) {
			if (trim(implode("", $data)) == "") continue;

			$setData = array();
			foreach ($data as $key => $val) {
				if (isset($dataArray[$key]) && $dataArray[$key] != "") {
					if ($typeArray[$key] != "8") {
						$setData[optParamName($dataArray[$key])] = mb_convert_encoding($val, APP_ENC, "SJIS-win");
					} else {
						$setData[optParamName($dataArray[$key])] = explode("<<>>", mb_convert_encoding($val, APP_ENC, "SJIS-win"));
					}
				}
			}
			unset($setData['insert_datetime'], $setData['update_datetime']);

			$dbFlg = false;
			if (is_numeric($setData['id']) && $dbFlg == false) {
				$tmpData = findByIdData($requestData['cms'], $setData['id']);
				if (isset($tmpData[$infoSystem['system_table'].'_id']) && is_numeric($tmpData[$infoSystem['system_table'].'_id'])) {
					$result = updateData($requestData['cms'], $setData, $setData['id']);
					if ($result === false) {
						$objDB->rollback();
						$errMsg = array("データの更新ができませんでした。データ内容が正しいか確認してください。");
						break;
					}
					$dbFlg = true;
				}
			}
			if ($infoSystem['system_csv_key'] != "" && $dbFlg == false) {
				$tmpData =  findEZData($requestData['cms'], array($objDB->quote($infoSystem['system_csv_key'])." = '".$objDB->quote($setData[optParamName($infoSystem['system_csv_key'])])."'"));
				if (isset($tmpData[$infoSystem['system_table'].'_id']) && is_numeric($tmpData[0][$infoSystem['system_table'].'_id'])) {
					$result = updateData($requestData['cms'], $setData, $tmpData[0][DB_COLUMN_HEADER.'id']);
					if ($result === false) {
						$objDB->rollback();
						$errMsg = array("データの更新ができませんでした。データ内容が正しいか確認してください。");
						break;
					}
					$dbFlg = true;
				}
			}
			if ($dbFlg == false) {
				$result = insertData($requestData['cms'], $setData);
				if ($result === false) {
					$objDB->rollback();
					$errMsg = array("データの更新ができませんでした。データ内容が正しいか確認してください。");
					break;
				}
			}			
			if ($infoSystem['system_csv_key'] != "") $uniqArray[] = $setData[$infoSystem['system_csv_key']];
		}
		
		if (count($errMsg) == 0) {
			$objDB->commit();
			header("Location: csvup.php?success=1&cms=".escapeHtml($requestData['cms']));
			exit;
		}
	} else {
		$errMsg = array("ファイルが確認できませんでした");
	}
}

// 出力設定
$viewData = viewExtractParam($requestData, $arrParam);
extract($viewData);
if (isset($CSVUP_FORM[$requestData['cms']]) && $CSVUP_FORM[$requestData['cms']] != "") {
	require_once $CSVUP_FORM[$requestData['cms']];
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
			<h3 class="tit"><?php echo escapeHtml($infoSystem['system_title']) ?>CSVアップロード</span></h3>

<?php if (count($errMsg) > 0) { ?>
<p class="msg warning">
<?php foreach ($errMsg as $val) { ?>
<?php echo escapeHtml($val) ?><br />
<?php } ?>
</p>
<?php } elseif ($success == 1) { ?>
			<p class="msg info">
登録が完了しました。
</p>
<?php } ?>

<form action="csvup.php" method="post" enctype="multipart/form-data">
<input type="file" name="csv" />
<input type="submit" value="アップロード" />
<input type="hidden" name="action" value="upload" />
<input type="hidden" name="cms" value="<?php echo escapeHtml($cms) ?>" />
</form>
※1行目は項目名として処理します

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