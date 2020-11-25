<?php

# パラメータ設定
$arrParam = array(
	"ids" => "ID",
	"title" => "件名",
	"content" => "内容",
	"link1" => "関連リンク1",
	"link2" => "関連リンク2",
	"link3" => "関連リンク3",
	"file1" => "添付ファイル1",
	"file2" => "添付ファイル2",
	"file3" => "添付ファイル3",
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

// エラーチェック
$errMsg = array();
if ($requestData['title'] == "") $errMsg[] = "件名が正しく入力されておりません";
if ($requestData['content'] == "") $errMsg[] = "内容が正しく入力されておりません";
if (count($errMsg) > 0) {
	exit;
}

// ファイルアップロード
for ($i = 1;$i <= 3;$i++) {
	if ($requestData['file'.$i]) {
		$tmpFilePath = $requestData['file'.$i];
		$requestData['file'.$i] = date("Ym")."/".$requestData['file'.$i];
		if (!is_dir(UPLOAD_FILE_DIR.date("Ym"))) {
			mkdir(UPLOAD_FILE_DIR.date("Ym"));
			chmod(UPLOAD_FILE_DIR.date("Ym"), 0777);
		}
		copy(UPLOAD_FILE_TEMP_DIR.$tmpFilePath, UPLOAD_FILE_DIR.$requestData['file'.$i]);
		unlink(UPLOAD_FILE_TEMP_DIR.$tmpFilePath);
	}
}

// DB登録
$objDB->begin();
foreach ($requestData['ids'] as $key => $val) {
	$insertArray = $requestData;
	$insertArray['user_id'] = $val;
	$insertArray['admin_id'] = $infoAdministrator['administrator_id'];
	$insertArray['send_date'] = date("Y-m-d");
	$objDB->insertData('message', $insertArray);
}
$objDB->commit();

// 出力設定
extract($requestData);

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
			</ul>

		</div> <!-- /aside -->

		<hr class="noscreen" />

		<!-- Content (Right Column) -->
		<div id="content" class="box">

			<h1>メッセージ管理</h1>
			<h3 class="tit">メッセージ一括送信</span></h3>
			
	
<p class="msg info">
送信が完了しました。
</p>
<input type="button" onclick="location.href='list.php?cms=7';" value="一覧に戻る" />
<input type="button" onclick="location.href='index.php';" value="CMS管理" />


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