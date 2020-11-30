<?php

# パラメータ設定
$arrParam = array(
	"cms" => "CMS",
	"output" => "出力",
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

// DB
$infoSystem = findByIdSystem($requestData['cms']);
if (!is_numeric($infoSystem['system_id'])) exit;

// 変更
if ($requestData['action'] == "chg") {
	$systemId = $infoSystem['system_id'];
	// ファイル操作
	$careerArray = array("1" => "", "2" => "mobile/", "3" => "sp/");
	for ($j = 1;$j <= 3;$j++) {
		$CAREER_DIR = $careerArray[$j];
		$MAIN_DIR = APP_DIR.$CAREER_DIR.$infoSystem['system_folder'];
		if (substr($MAIN_DIR, (strlen($MAIN_DIR) - 1), 1) == "/") $MAIN_DIR = substr($MAIN_DIR, 0, (strlen($MAIN_DIR) - 1));

		if (!file_exists($MAIN_DIR."/list.php") && $requestData['output'][(($j - 1) * 3 + 1)] == "1") {
			$source = file_get_contents("./user/".$CAREER_DIR."list.php");
			$fp = fopen($MAIN_DIR."/list.php", "w");
			$source = str_replace("{!cms!}", $systemId, $source);
			$source = str_replace("{!path!}", WEB_APP, $source);
			$sortArray = array();
			$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($systemId)."'"), array("param_sort Asc", "param_id Asc"));
			foreach ($resParam as $val) {
				switch ($val['param_type']) {
					case 9:
						$sortArray[] = '"p'.$val['param_id'].'" => "4",';
						break;
					case 1:
					case 3:
						$sortArray[] = '"p'.$val['param_id'].'" => "3",';
						break;
					case 4:
					case 6:
					case 8:
					case 14:
						$sortArray[] = '"p'.$val['param_id'].'" => "2",';
						break;
					case 11:
						break;
					default:
						$sortArray[] = '"p'.$val['param_id'].'" => "1",';
						break;
				}
			}
			$sortCode = implode("\n\t", $sortArray);
			$source = str_replace("{!sort!}", $sortCode, $source);
			$dataArray = array();
			for ($i = 0;$i < 5;$i++) {
				$dataArray[$i] = '<tr>
				<th bgcolor="#FFFFCC">'.$resParam[$i]['param_name'].'</th>
					<td bgcolor="#FFFFFF">
						{!item!}
				</td>
			</tr>';

				$dataArray[$i] = str_replace('{!item!}', listOutputData($resParam[$i]), $dataArray[$i]);

			}
			$dataCode = implode("\n\t", $dataArray);
			$source = str_replace("{!data!}", $dataCode, $source);
			header("Content-Type: text/html; charset=UTF-8");
			echo nl2br(escapeHtml($source));
			exit;
		}
		if (!file_exists($MAIN_DIR."/detail.php") && $requestData['output'][(($j - 1) * 3 + 2)] == "1") {
			$source = file_get_contents("./user/".$CAREER_DIR."detail.php");
			$fp = fopen($MAIN_DIR."/detail.php", "w");
			$source = str_replace("{!cms!}", $systemId, $source);
			$source = str_replace("{!path!}", WEB_APP, $source);
			$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($systemId)."'"), array("param_sort Asc", "param_id Asc"));
			$dataArray = array();
			for ($i = 0;$i < count($resParam);$i++) {
				$dataArray[$i] = '
			<tr>
				<th bgcolor="#FFFFCC">'.$resParam[$i]['param_name'].'</th>
					<td bgcolor="#FFFFFF">
						{!item!}
				</td>
			</tr>';

				$dataArray[$i] = str_replace('{!item!}', detailOutputData($resParam[$i]), $dataArray[$i]);

			}
			$dataCode = implode("\n\t", $dataArray);
			$source = str_replace("{!data!}", $dataCode, $source);
			header("Content-Type: text/html; charset=UTF-8");
			echo nl2br(escapeHtml($source));
			exit;
		}
		if (!file_exists($MAIN_DIR."/form.php") && $requestData['output'][(($j - 1) * 3 + 3)] == "1") {
			$source = file_get_contents("./user/".$CAREER_DIR."form.php");
			$fp = fopen($MAIN_DIR."/form.php", "w");
			$source = str_replace("{!cms!}", $systemId, $source);
			$source = str_replace("{!path!}", WEB_APP, $source);
			$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($systemId)."'"), array("param_sort Asc", "param_id Asc"));
			$dataArray = array();
			$paramArray = array();
			for ($i = 0;$i < count($resParam);$i++) {
				$dataArray[$i] = '
			<tr>
				<th bgcolor="#FFFFCC">'.$resParam[$i]['param_name'].'</th>
					<td bgcolor="#FFFFFF">
						{!item!}
				</td>
			</tr>';
				$dataArray[$i] = str_replace('{!item!}', makeParamFormType($resParam[$i], $requestData), $dataArray[$i]);
			}
			$arrParam = setParamCms(array(), $resParam);
			$paramCodeArray = array();
			foreach ($arrParam as $key => $val) {
				$paramCodeArray[] = "\t".'"'.$key.'" => "'.$val.'",';
			}
			$dataCode = implode("\n\t", $dataArray);
			$paramCode = implode("\n", $paramCodeArray);
			$source = str_replace("{!form!}", $dataCode, $source);
			$source = str_replace("{!param_array!}", $paramCode, $source);
			header("Content-Type: text/html; charset=UTF-8");
			echo nl2br(escapeHtml($source));
			exit;
		}
		if (!file_exists($MAIN_DIR."/confirm.php") && $requestData['output'][3] == "1") {
			$source = file_get_contents("./user/".$CAREER_DIR."confirm.php");
			$fp = fopen($MAIN_DIR."/confirm.php", "w");
			$source = str_replace("{!cms!}", $systemId, $source);
			$source = str_replace("{!path!}", WEB_APP, $source);
			$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($systemId)."'"), array("param_sort Asc", "param_id Asc"));
			$dataArray = array();
			$paramArray = array();
			for ($i = 0;$i < count($resParam);$i++) {
				$dataArray[$i] = '
			<tr>
				<th bgcolor="#FFFFCC">'.$resParam[$i]['param_name'].'</th>
					<td bgcolor="#FFFFFF">
						{!item!}
				</td>
			</tr>';
				$dataArray[$i] = str_replace('{!item!}', makeOutputData($resParam[$i]), $dataArray[$i]);
			}
			$arrParam = setParamCms(array(), $resParam);
			$paramCodeArray = array();
			foreach ($arrParam as $key => $val) {
				$paramCodeArray[] = "\t".'"'.$key.'" => "'.$val.'",';
			}
			$dataCode = implode("\n\t", $dataArray);
			$paramCode = implode("\n", $paramCodeArray);
			$source = str_replace("{!confirm!}", $dataCode, $source);
			$source = str_replace("{!param_array!}", $paramCode, $source);
			header("Content-Type: text/html; charset=UTF-8");
			echo nl2br(escapeHtml($source));
			exit;
		}
		if (!file_exists($MAIN_DIR."/thanks.php") && $requestData['output'][3] == "1") {
			$source = file_get_contents("./user/".$CAREER_DIR."thanks.php");
			$fp = fopen($MAIN_DIR."/thanks.php", "w");
			$source = str_replace("{!cms!}", $systemId, $source);
			$source = str_replace("{!path!}", WEB_APP, $source);
			$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($systemId)."'"), array("param_sort Asc", "param_id Asc"));
			$dataArray = array();
			$paramArray = array();
			$arrParam = setParamCms(array(), $resParam);
			$paramCodeArray = array();
			foreach ($arrParam as $key => $val) {
				$paramCodeArray[] = "\t".'"'.$key.'" => "'.$val.'",';
			}
			$paramCode = implode("\n", $paramCodeArray);
			$source = str_replace("{!param_array!}", $paramCode, $source);
			header("Content-Type: text/html; charset=UTF-8");
			echo nl2br(escapeHtml($source));
			exit;
		}
		if ($requestData['output'][4] == "1") {
			$errMsg = array();
			$objDB = DBD_Query::singleQuery();

			$trimData = array();
			$supportData = array();
			$changeData = array();
			$validateData = array();
			$minData = array();
			$maxData = array();
			$otherData = array();

			$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($infoSystem['system_id'])."'"));
			foreach ($resParam as $key => $val) {
				if ($val['param_type'] == "1" && $val['param_type'] == "2" && $val['param_type'] == "3") {
					// 文字サポート
					$trimData[] = '"'.optParamName($val['param_column']).'",';
					$supportData[] = '"'.optParamName($val['param_column']).'" => "a",';
				}
				if ($val['param_type'] == "9") {
					$changeData[] = '$requestData['.optParamName($val['param_column']).'] = $requestData["'.optParamName($val['param_column']).'_y"]."-".$requestData["'.optParamName($val['param_column']).'_m"]."-".$requestData["'.optParamName($val['param_column']).'_d"];';
				}
				if ($val['param_type'] == "10") {
					$changeData[] = '$requestData['.optParamName($val['param_column']).'] = $requestData["'.optParamName($val['param_column']).'_y"]."-".$requestData["'.optParamName($val['param_column']).'_m"]."-".$requestData["'.optParamName($val['param_column']).'_d"]." ".$requestData["'.optParamName($val['param_column']).'_h"].":".$requestData["'.optParamName($val['param_column']).'_i"].":".$requestData["'.optParamName($val['param_column']).'_s"];';
				}

				// エラーチェック
				if ($val['param_validate'] != "") {
					$validateData[] = '"'.optParamName($val['param_column']).'" => "'.$val['param_validate'].'",';
				} else {
					if ($val['param_type'] >= 1 && $val['param_type'] <= 3 || $val['param_type'] == "15" || $val['param_type'] == "17" || $val['param_type'] == "13" || $val['param_type'] == "19") {
						$validateData[] = '"'.optParamName($val['param_column']).'" => "digitE",';
					}
					if ($val['param_type'] == "9") {
						$validateData[] = '"'.optParamName($val['param_column']).'" => "dateE",';
					}
					if ($val['param_type'] == "10") {
						$validateData[] = '"'.optParamName($val['param_column']).'" => "timestampE",';
					}
				}

				// 長さチェック
				if ($val['param_max'] != "" || $val['param_min'] != "") {
					if (is_numeric($val['param_max'])) {
						$maxData[] = '"'.optParamName($val['param_column']).'" => "'.$val['param_max'].'",';
					}
					if (is_numeric($val['param_min'])) {
						$minData[] = '"'.optParamName($val['param_column']).'" => "'.$val['param_min'].'",';
					}
				} else {
					if ($val['param_type'] >= 1 && $val['param_type'] <= 3 || $val['param_type'] == "15" || $val['param_type'] == "17" || $val['param_type'] == "13" || $val['param_type'] == "19") {
						$maxData[] = '"'.optParamName($val['param_column']).'" => "10",';
					}
					if ($val['param_type'] == "4" || $val['param_type'] == "5" || $val['param_type'] == "16" || $val['param_type'] == "18") {
						$maxData[] = '"'.optParamName($val['param_column']).'" => "100",';
					}
					if ($val['param_type'] == "6" || $val['param_type'] == "7" || $val['param_type'] == "8" || $val['param_type'] == "14") {
						$maxData[] = '"'.optParamName($val['param_column']).'" => "80000",';
					}
				}

				if ($val['param_type'] == "11") {
					$otherData[] = '$extension = getFileExtension($_FILES[\''.optParamName($val['param_column']).'\'][\'name\']);'."\n".'if ($_FILES[\''.optParamName($val['param_column']).'\'][\'tmp_name\'] && strtolower($extension) != "gif" && strtolower($extension) != "jpg" && strtolower($extension) != "jpeg" && strtolower($extension) != "png" && strtolower($extension) != "bmp") $errMsg[] = "ファイルの形式が正しくありません";';
				}
			}
			function prevN($str, $prev = "\n") { if ($str != "") { $str = $prev.$str; } return $str; }
 			$output = '
function '.lcfirst(strtr(ucwords(strtr($infoSystem['system_table'], array('_' => ' '))), array(' ' => ''))).'Val(&$requestData, $arrParam, $safeFlg = true) {
	// 文字サポート
	$requestData = applyTrim(
		$requestData,
		array('.prevN(implode("\n", $trimData)).'
		)
	);
	$requestData = applyKana(
		$requestData,
		array('.prevN(implode("\n", $supportData)).'
		)
	);'.prevN(implode("\n", $changeData), "\n\n// パラメータサポート\n").'

	// エラーチェック
	$errMsg = checkDataType(
		$requestData,
		$arrParam,
		array('.prevN(implode("\n", $validateData)).'
		)
	);
	$errMsg = array_merge($errMsg,
		checkLength(
			$requestData,
			$arrParam,
			array('.prevN(implode("\n", $maxData)).'
			),
			array('.prevN(implode("\n", $minData)).'
			)
		)
	);
	'.prevN(implode("\n", $otherData)).'

	// セーフフラグ
	if (count($errMsg) > 0 && $safeFlg == false) {
		header("Location: ".HOME_URL);
		exit;
	}

	return $errMsg;
}
';
			echo nl2br($output);

			exit;
		}
	}

	header("Location: output.php?cms=".escapeHtml($requestData['cms'])."&success=2");
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
			<h3 class="tit">テーブル編集</span></h3>

			<?php if ($success > 0) { ?>
<p class="msg info">
<?php if ($success == 2) { ?>
ページを出力しました。
<?php } ?>
</p>
<?php } ?>

<?php if (isset($errMsg)) { ?>
<p class="msg warning">
<?php foreach ($errMsg as $key => $val) { ?>
<?php echo escapeHtml($val) ?><br />
<?php } ?>
</p>
<?php } ?><br />
<br />
<form action="output.php" method="post" onsubmit="return confirm('項目を編集します');">
<table>
  <tr>
    <th>PC出力</th>
  	<td>
			<input type="checkbox" name="output[1]" value="1" /> 一覧<br />
			<input type="checkbox" name="output[2]" value="1" /> 詳細<br />
			<input type="checkbox" name="output[3]" value="1" /> フォーム<br />
			<input type="checkbox" name="output[4]" value="1" /> バリデーション
	</td>
  </tr>
</table>
※ファイルがあった場合は上書きされません<br />
<input type="hidden" name="action" value="chg" />
<input type="hidden" name="cms" value="<?php echo escapeHtml($cms) ?>" />
<input type="submit" value="出力する" />
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
</html>>