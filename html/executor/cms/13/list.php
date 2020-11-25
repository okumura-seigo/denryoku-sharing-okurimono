<?php

// DB
$infoSystem = findByIdSystem($requestData['cms']);
$infoSystem['system_content'] = unserialize($infoSystem['system_content']);
if (!is_numeric($infoSystem['system_id'])) exit;

// 初期化
if (!isset($requestData['lim']) || !is_numeric($requestData['lim'])) $requestData['lim'] = 0;

// 一覧項目取得
$listView = unserialize($infoSystem['system_list_view']);
$itemArray = array();
$foreignArray = array();
$imageArray = array();
$paramTableArray = array();
$multiArray = array();
$tmpParam = array();
$tmpData = array();
if (count($listView) == 0) {
	$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($infoSystem['system_id'])."'"), array("param_sort Asc", "param_id Asc"), 5);
	foreach ($resParam as $key => $val) {
		$itemArray[$val['param_column']] = $val['param_name'];
		if ($val['param_type'] == 13 || $infoParam['param_type'] == 19) $foreignArray[] = $val;
		if ($val['param_type'] == 11) $imageArray[] = $val;
		if ($val['param_type'] == 8) $multiArray[] = $val;
		switch ($val['param_type']) {
			case 2:
			case 5:
			case 7:
			case 15:
			case 16:
				$paramTableArray[] = $val;
				break;
		}
	}
} else {
	foreach ($listView as $key => $val) {
		if ($val > 0) {
			$infoParam = findByIdParam($val);
			$itemArray[$infoParam['param_column']] = $infoParam['param_name'];
			if ($infoParam['param_type'] == 13 || $infoParam['param_type'] == 19) $foreignArray[] = $infoParam;
			if ($infoParam['param_type'] == 11) $imageArray[] = $infoParam;
			if ($infoParam['param_type'] == 8) $multiArray[] = $infoParam;
			switch ($infoParam['param_type']) {
				case 2:
				case 5:
				case 7:
				case 15:
				case 16:
					$paramTableArray[] = $infoParam;
					break;
			}
		} elseif ($val == -1) {
			$itemArray[DB_COLUMN_HEADER.'insert_datetime'] = '登録日時';
		} elseif ($val == -2) {
			$itemArray[DB_COLUMN_HEADER.'update_datetime'] = '更新日時';
		} elseif ($val == -3) {
			$itemArray[DB_COLUMN_HEADER.'stop_flg'] = '表示状態';
		}
	}
}

// 停止フラグ・削除フラグ
$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($infoSystem['system_id'])."'", "param_column = 'stop_flg'"));
$existsStopFlg = (isset($resParam[0]['param_id'])) ? 1 : 0;
$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($infoSystem['system_id'])."'", "param_column = 'delete_flg'"));
$existsDeleteFlg = (isset($resParam[0]['param_id'])) ? 1 : 0;

// データ
$whereArray = array();
if (isset($_REQUEST['user_id']) && $_REQUEST['user_id']) $whereArray[] = "user_id = '".$objDB->quote($_REQUEST['user_id'])."'";
if (isset($requestData['id']) && $requestData['id']) $whereArray[] = getIdColumn($infoSystem['system_table'])." = '".$objDB->quote($requestData['id'])."'";
if (isset($requestData['q']) && $requestData['q']) {
	$queryParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($infoSystem['system_id'])."'", "param_type in (4, 5, 6, 7, 8, 14, 16, 18)"), array("param_sort Asc", "param_id Asc"));
	if (count($queryParam) > 0) {
		$queryWhere = array();
		foreach ($queryParam as $key => $val) $queryWhere[] = $objDB->quote($val['param_column'])." Like '%".$objDB->quote($requestData['q'])."%'";
		$whereArray[] = "(".implode(" Or ", $queryWhere).")";
	}
}
if (count($requestData['flg']) > 0 && ($existsStopFlg || $existsDeleteFlg)) {
	$orArray = array();
	if (in_array("none", $requestData['flg'])) {
		$noneArray = array();
		if ($existsStopFlg) $noneArray[] = "stop_flg = 0";
		if ($existsDeleteFlg) $noneArray[] = "delete_flg = 0";
		$orArray[] = "(".implode(" And ", $noneArray).")";
	}
	if ($existsStopFlg && in_array("stop_flg", $requestData['flg'])) $orArray[] = "stop_flg = 1";
	if ($existsDeleteFlg && in_array("delete_flg", $requestData['flg'])) $orArray[] = "delete_flg = 1";
	$whereArray[] = "(".implode(" Or ", $orArray).")";
}

$orderArray = array();
if (isset($requestData['s']) && $requestData['s'] != "") {
	$orderArray[] = ($requestData['st'] == 1) ? $requestData['s']." Asc" : $objDB->quote($requestData['s'])." Desc" ;
} else {
	$orderArray = array($infoSystem['system_list_sort']);
}

if (isset($_REQUEST['user_id']) && is_numeric($_REQUEST['user_id'])) {
	$resData = findEZData($requestData['cms'], $whereArray, $orderArray);
} else {
	$resData = findEZData($requestData['cms'], $whereArray, $orderArray, ADM_LIST_NUM, $requestData['lim']);
}
$resViewData = $resData;
foreach ($paramTableArray as $key => $val) {
	$paramTable = paramTable($val);
	foreach ($resViewData as $key2 => $val2) {
		$resViewData[$key2][$val['param_column']] = $paramTable[$resViewData[$key2][$val['param_column']]];
	}
}
foreach ($multiArray as $key => $val) {
	$paramTable = paramTable($val);
	foreach ($resViewData as $key2 => $val2) {
		$outputData = array();
		foreach (unserialize($resViewData[$key2][$val['param_column']]) as $val3) {
			$outputData[] = $paramTable[$val3];
		}
		$resViewData[$key2][$val['param_column']] = implode(", ", $outputData);
	}
}

foreach ($foreignArray as $key => $val) {
	foreach ($resViewData as $key2 => $val2) {
		$tmpParam[trim($val['param_info'])] = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote(trim($val['param_info']))."'"), array("param_sort", "param_id"), 1);
		$tmpData[trim($val['param_info'])][$val['param_column']] = findByIdData(trim($val['param_info']), $val2[$val['param_column']]);
		if ($tmpData[trim($val['param_info'])][$val['param_column']]) {
			$resViewData[$key2][$val['param_column']] = $tmpData[trim($val['param_info'])][$val['param_column']][$tmpParam[trim($val['param_info'])][0]['param_column']];
		}
	}
}
foreach ($imageArray as $key => $val) {
	foreach ($resViewData as $key2 => $val2) {
		$resViewData[$key2][$val['param_column']] = '<img src="'.UPLOAD_FILE_URL.$resViewData[$key2][$val['param_column']].'" width="80" />';
	}
}
$maxRows = countData($requestData['cms'], $whereArray);
$pager = pagerStr($maxRows, ADM_LIST_NUM, $requestData['lim'], $requestData);

// ID
if (strpos($infoSystem['system_table'], '___') !== false) {
	$expAccessTable = explode('___', $infoSystem['system_table']);
	$idColumn = str_replace($expAccessTable[0].'___', '', $infoSystem['system_table']).'_id';
} else {
	$idColumn = $infoSystem['system_table'].'_id';
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

			<h3 class="tit">検索条件</span></h3>

			<div id="search_area">
			<form action="list.php" method="post">
			<dl id="search_box">
				<dt>ID</dt>
				<dd><input type="text" name="id" value="<?php echo escapeHtml($id) ?>" /></dd>
				<dt>フリーワード</dt>
				<dd><input type="text" name="q" value="<?php echo escapeHtml($q) ?>" /></dd>
<?php if ($existsStopFlg || $existsDeleteFlg) { ?>
				<dt>状態</dt>
				<dd>
					<input type="checkbox" name="flg[]" value="none" <?php if (is_array($flg) && in_array("none", $flg)) echo "checked"; ?> />稼働中
<?php if ($existsStopFlg) { ?>
					<input type="checkbox" name="flg[]" value="stop_flg" <?php if (is_array($flg) && in_array("stop_flg", $flg)) echo "checked"; ?> />停止
<?php } ?>
<?php if ($existsDeleteFlg) { ?>
					<input type="checkbox" name="flg[]" value="delete_flg" <?php if (is_array($flg) && in_array("delete_flg", $flg)) echo "checked"; ?> />削除
<?php } ?>
				</dd>
<?php } ?>
			</dl>
			<input type="submit" value="検索する" />
			<input type="hidden" name="cms" value="<?php echo escapeHtml($cms) ?>" />
			</form>
			</div>
			<div style="clear:both">

			<h3 class="tit"><?php echo escapeHtml($infoSystem['system_title']) ?>一覧</span></h3>
<?php if (!isset($_REQUEST['user_id']) || !is_numeric($_REQUEST['user_id'])) { ?>
<?php echo $pager ?>
<?php } ?>
</div>
<table border="0" cellpadding="3" cellspacing="1">
  <tr class="list_tr">
    <th bgcolor="#FFFFCC"><a href="list.php?cms=<?php echo escapeHtml($cms) ?>&s=<?php echo escapeHtml(getIdColumn($infoSystem['system_table'])) ?>&st=<?php if ($st == 1) { echo "0"; } else { echo "1"; } ?><?php foreach ($requestData as $key3 => $val3) { ?><?php if ($key3 != "lim" && $key3 != "s" && $key3 != "st") { if (!is_array($val3)) { ?>&<?php echo escapeHtml($key3) ?>=<?php echo escapeHtml($val3) ?><?php } else { foreach ($val3 as $key4 => $val4) {  ?>&<?php echo escapeHtml($key3) ?>[]=<?php echo escapeHtml($val4) ?><?php  } } ?><?php } ?><?php } ?>">ID</a></th>
<?php foreach ($itemArray as $key2 => $val2) { ?>
    <th bgcolor="#FFFFCC"><a href="list.php?cms=<?php echo escapeHtml($cms) ?>&s=<?php echo escapeHtml($key2) ?>&st=<?php if ($st == 1) { echo "0"; } else { echo "1"; } ?><?php foreach ($requestData as $key3 => $val3) { ?><?php if ($key3 != "lim" && $key3 != "s" && $key3 != "st") { if (!is_array($val3)) { ?>&<?php echo escapeHtml($key3) ?>=<?php echo escapeHtml($val3) ?><?php } else { foreach ($val3 as $key4 => $val4) {  ?>&<?php echo escapeHtml($key3) ?>[]=<?php echo escapeHtml($val4) ?><?php  } } ?><?php } ?><?php } ?>"><?php echo escapeHtml($val2) ?></a></th>
<?php } ?>
<?php if ($infoSystem['system_table'] == 'image') { ?>
    <th bgcolor="#FFFFCC">表示タグ</th>
<?php } ?>
    <th bgcolor="#FFFFCC">詳細</th>
  </tr>
<?php foreach ($resViewData as $key => $val) { ?>
  <tr<?php if (isset($val[DB_COLUMN_HEADER.'delete_flg']) && $val[DB_COLUMN_HEADER.'delete_flg'] == "1") { echo ' class="deleted"'; } elseif (isset($val[DB_COLUMN_HEADER.'stop_flg']) && $val[DB_COLUMN_HEADER.'stop_flg'] == "1") { echo ' class="stoped"'; } else { if ($key % 2 == 1) { echo ' class="bg"'; } } ?>>
    <td align="center" bgcolor="#FFFFFF"><?php echo escapeHtml($val[$idColumn]) ?></td>
<?php foreach ($itemArray as $key2 => $val2) { ?>
    <td bgcolor="#FFFFFF">
<?php if ($key2 == DB_COLUMN_HEADER."stop_flg") { ?>
			<?php if ($val[$key2] == "1") { echo "停止"; } else { echo "稼働中"; } ?>
<?php } elseif ($key2 == DB_COLUMN_HEADER."delete_flg") { ?>
			<?php if ($val[$key2] == "1") { echo "削除"; } else { echo "稼働中"; } ?>
<?php } else { ?>
<?php if (substr($val[$key2], 0, 4) == "<img") { ?>
			<?php echo ($val[$key2]) ?>
<?php } else { ?>
			<?php echo escapeHtml(mb_strimwidth($val[$key2], 0, 50, "...", APP_ENC)) ?>
<?php } ?>
<?php } ?>
		</td>
<?php } ?>
<?php if ($infoSystem['system_table'] == 'image') { ?>
    <td bgcolor="#FFFFFF">
	<textarea>![<?php echo h($val['name']) ?>](IMAGEID:<?php echo h($val['image_id']) ?>)</textarea>
    </td>
<?php } ?>
    <td bgcolor="#FFFFFF">
<?php if (inArray("stop", $infoSystem['system_content'])) { ?>
<?php if ($existsStopFlg) { ?>
<?php if (isset($val[DB_COLUMN_HEADER.'stop_flg']) && $val[DB_COLUMN_HEADER.'stop_flg'] == "1") { ?>
    	<input type="button" value="表示する" onclick="if (confirm('表示します')) { location.href='view.php?cms=<?php echo escapeHtml($cms) ?>&id=<?php echo escapeHtml($val[$idColumn]) ?>'; }" />
<?php } else { ?>
    	<input type="button" value="表示しない" onclick="if (confirm('非表示にします')) { location.href='view.php?cms=<?php echo escapeHtml($cms) ?>&id=<?php echo escapeHtml($val[$idColumn]) ?>'; }" />
<?php } ?>
<?php } ?>
<?php } ?>
<?php if (inArray("update", $infoSystem['system_content'])) { ?>
    	<input type="button" value="編集する" onclick="location.href='edit.php?cms=<?php echo escapeHtml($cms) ?>&id=<?php echo escapeHtml($val[$idColumn]) ?>'" />
<?php } ?>
<?php if (inArray("delete", $infoSystem['system_content'])) { ?>
<?php if ($existsDeleteFlg) { ?>
<?php if (isset($val[DB_COLUMN_HEADER.'delete_flg']) && $val[DB_COLUMN_HEADER.'delete_flg'] == "1") { ?>
    	<input type="button" value="復旧する" onclick="location.href='restore.php?cms=<?php echo escapeHtml($cms) ?>&id=<?php echo escapeHtml($val[$idColumn]) ?>'" />
<?php } else { ?>
    	<input type="button" value="削除する" onclick="location.href='delete.php?cms=<?php echo escapeHtml($cms) ?>&id=<?php echo escapeHtml($val[$idColumn]) ?>'" />
<?php } ?>
<?php } ?>
<?php } ?>
<?php if (inArray("erasure", $infoSystem['system_content'])) { ?>
    	<input type="button" value="消去する" onclick="location.href='erasure.php?cms=<?php echo escapeHtml($cms) ?>&id=<?php echo escapeHtml($val[$idColumn]) ?>'" />
<?php } ?>
    	<input type="button" value="詳細表示" onclick="location.href='detail.php?cms=<?php echo escapeHtml($cms) ?>&id=<?php echo escapeHtml($val[$idColumn]) ?>'" />
    </td>
  </tr>
<?php } ?>
</table>
<?php if (!isset($_REQUEST['user_id']) || !is_numeric($_REQUEST['user_id'])) { ?>
<?php echo $pager ?>
<?php } ?>
<?php if (inArray("csvdown", $infoSystem['system_content'])) { ?>
<div align="right">
<form action="csvdown.php" method="post">
<input type="submit" value="CSVダウンロード" />
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
</div>
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