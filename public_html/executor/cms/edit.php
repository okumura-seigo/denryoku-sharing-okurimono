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
if (!isset($requestData)) $requestData = getParam($arrParam, $formState);
if (!is_numeric($requestData['cms'])) exit;

// DB
$infoSystem = findByIdSystem($requestData['cms']);
$infoSystem['system_content'] = unserialize($infoSystem['system_content']);
if (!is_numeric($infoSystem['system_id'])) exit;

// 項目取得
$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($infoSystem['system_id'])."'"), array("param_sort Asc", "param_id Asc"));
$resSetParam = array();
foreach ($resParam as $key => $val) {
	$resParam[$key]['param_view_info'] = ($resParam[$key]['param_view_info']) ? unserialize($resParam[$key]['param_view_info']) : array('insert' => '', 'update' => '', 'delete' => '', 'detail' => '');
	if ($resParam[$key]['param_view_info']['update'] == '') $resSetParam[] = $val;
}
$arrParam = setParamCms($arrParam, $resSetParam);
$requestData = getParam($arrParam, $formState);

// データ取得
$infoData = findByIdData($requestData['cms'], $requestData['id']);

// 初期値
if (checkFirstAction($arrParam, array('cms', 'id'))) {
	$requestData = array_merge($requestData, requestByIdData($requestData['cms'], $requestData['id']));
}

// 出力設定
$viewData = viewExtractParam($requestData, $arrParam);
extract($viewData);
if (isset($CHANGE_FORM[$requestData['cms']])) {
	require_once $CHANGE_FORM[$requestData['cms']];
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
	<script type="text/javascript">
	$(document).ready(function(){
		$(".tabs > ul").tabs();
	});
	</script>
	<script type="text/javascript" src="../js/nicEdit/nicEdit.js"></script>
	<script type="text/javascript">
		bkLib.onDomLoaded(function() {
			$('.nicwys').each(function() {
				new nicEditor({fullPanel : true, convertToText : false}).panelInstance($(this).attr('id'));
			});
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
			<h3 class="tit"><?php echo escapeHtml($infoSystem['system_title']) ?>編集</span></h3>
			
<?php if (isset($errMsg)) { ?>
<p class="msg warning">
<?php foreach ($errMsg as $val) { ?>
<?php echo escapeHtml($val) ?><br />
<?php } ?>
</p>
<?php } ?>
<form action="edit_con.php" method="post" enctype="multipart/form-data">
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
<?php foreach ($resParam as $key => $val) { ?>

<?php if ($val['param_view_info']['update'] != 'invisible') { ?>
<?php if ($val['param_column'] == "insert_datetime") { ?>
<?php } elseif ($val['param_column'] == "update_datetime") { ?>
<?php } elseif ($val['param_column'] == "stop_flg") { ?>
  <tr>
    <th><?php echo escapeHtml($val['param_name'], $requestData) ?></th>
    <td>
<?php if ($val['param_view_info']['update'] == '') { ?>
    	<select name="stop_flg">
		<option value="0" <?php if ($stop_flg == "0") echo "selected"; ?>>稼働中</option>
		<option value="1" <?php if ($stop_flg == "1") echo "selected"; ?>>停止</option>
	</select>
<?php } else { ?>
	<?php echo outputData($val, $infoData) ?>
<?php } ?>
    </td>
  </tr>
<?php } elseif ($val['param_column'] == "delete_flg") { ?>
  <tr>
    <th><?php echo escapeHtml($val['param_name'], $requestData) ?></th>
    <td>
<?php if ($val['param_view_info']['update'] == '') { ?>
	<select name="delete_flg">
		<option value="0" <?php if ($delete_flg == "0") echo "selected"; ?>>稼働中</option>
		<option value="1" <?php if ($delete_flg == "1") echo "selected"; ?>>削除</option>
	</select>
<?php } else { ?>
	<?php echo outputData($val, $infoData) ?>
<?php } ?>
    </td>
  </tr>
<?php } else { ?>
  <tr>
    <th bgcolor="#FFFFCC"><?php echo escapeHtml($val['param_name'], $requestData) ?></th>
    <td bgcolor="#FFFFFF">
<?php if ($val['param_view_info']['update'] == '') { ?>
    	<?php echo paramFormType($val, $requestData) ?>
<?php if ($val['param_type'] == "20") { ?>
		<br /><strong>※変更する場合のみ入力してください</strong>
<?php } ?>
<?php if ($val['param_type'] == "11" && $infoData[$val['param_column']] != "") { ?>
		<br />
		<a href="<?php echo escapeHtml(UPLOAD_FILE_URL.$infoData[$val['param_column']]) ?>" target="_blank"><img src="<?php echo escapeHtml(UPLOAD_FILE_URL.$infoData[$val['param_column']]) ?>" width="<?php echo escapeHtml(IMAGE_M_WIDTH) ?>" /></a><br />
		<a href="#" onclick="if (confirm('この画像を削除しますか？')) { window.open('file_delete.php?cms=<?php echo escapeHtml($cms) ?>&id=<?php echo escapeHtml($id) ?>&column=<?php echo escapeHtml($val['param_column']) ?>', 'imgdel', 'width=400,height=300,scrollbars=yes,resizable=yes'); return false; } else { return false; }">削除する</a>
<?php } ?>
<?php if ($val['param_type'] == "12" && $infoData[$val['param_column']] != "") { ?>
		<br />
		<a href="download_file.php?sid=<?php echo escapeHtml($cms) ?>&id=<?php echo escapeHtml($id) ?>&data=<?php echo escapeHtml(optParamName($val['param_column'])) ?>" target="_blank">ファイルを確認する</a><br />
		<a href="#" onclick="if (confirm('このファイルを削除しますか？')) { window.open('file_delete.php?cms=<?php echo escapeHtml($cms) ?>&id=<?php echo escapeHtml($id) ?>&column=<?php echo escapeHtml($val['param_column']) ?>', 'imgdel', 'width=400,height=300,scrollbars=yes,resizable=yes'); return false; } else { return false; }">削除する</a>
<?php } ?>
<?php } else { ?>
	<?php echo outputData($val, $infoData) ?>
<?php } ?>
    </td>
  </tr>
<?php } ?>
<?php } ?>
<?php } ?>

</table>
<input type="hidden" name="cms" value="<?php echo escapeHtml($cms) ?>" />
<input type="hidden" name="id" value="<?php echo escapeHtml($id) ?>" />
<input type="submit" value="確認する" />
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