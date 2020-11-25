<?php

# パラメータ設定
$arrParam = array(
	"cms" => "ID",
	"action" => "action",
	"file" => "file",
	"html" => "html",
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

// ファイル取得
list($fileList, $is_php) = getdirtree(array(APP_DIR.'executor/system/user', false));


if ($requestData['action'] == "save") {
	$fp = fopen($requestData['file'], "w");
	fwrite($fp, $requestData['html']);
}

if ($requestData['file'] != "") {
	$html = file_get_contents($requestData['file']);
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
			<li><a href="index.php"><span>CMS管理</span></a></li>
			<li><a href="html.php"><span>HTML編集</span></a></li>
			<li id="menu-active"><a href="template.php"><span>テンプレート編集</span></a></li>
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
				<li><a href="">- PC</a>
				<li><a href="template.php?file=<?php echo escapeHtml(APP_DIR."executor/system/user/list.php") ?>">&nbsp;&nbsp;+ 一覧</a></li>
				<li><a href="template.php?file=<?php echo escapeHtml(APP_DIR."executor/system/user/detail.php") ?>">&nbsp;&nbsp;+ 詳細</a></li>
				<li><a href="template.php?file=<?php echo escapeHtml(APP_DIR."executor/system/user/form.php") ?>">&nbsp;&nbsp;+ フォーム（入力）</a></li>
				<li><a href="template.php?file=<?php echo escapeHtml(APP_DIR."executor/system/user/confirm.php") ?>">&nbsp;&nbsp;+ フォーム（確認）</a></li>
				<li><a href="template.php?file=<?php echo escapeHtml(APP_DIR."executor/system/user/thanks.php") ?>">&nbsp;&nbsp;+ フォーム（完了）</a></li>
				<li><a href="">- モバイル</a>
				<li><a href="template.php?file=<?php echo escapeHtml(APP_DIR."executor/system/user/mobile/list.php") ?>">&nbsp;&nbsp;+ 一覧</a></li>
				<li><a href="template.php?file=<?php echo escapeHtml(APP_DIR."executor/system/user/mobile/detail.php") ?>">&nbsp;&nbsp;+ 詳細</a></li>
				<li><a href="template.php?file=<?php echo escapeHtml(APP_DIR."executor/system/user/mobile/form.php") ?>">&nbsp;&nbsp;+ フォーム（入力）</a></li>
				<li><a href="template.php?file=<?php echo escapeHtml(APP_DIR."executor/system/user/mobile/confirm.php") ?>">&nbsp;&nbsp;+ フォーム（確認）</a></li>
				<li><a href="template.php?file=<?php echo escapeHtml(APP_DIR."executor/system/user/mobile/thanks.php") ?>">&nbsp;&nbsp;+ フォーム（完了）</a></li>
				<li><a href="">- スマホ</a>
				<li><a href="template.php?file=<?php echo escapeHtml(APP_DIR."executor/system/user/sp/list.php") ?>">&nbsp;&nbsp;+ 一覧</a></li>
				<li><a href="template.php?file=<?php echo escapeHtml(APP_DIR."executor/system/user/sp/detail.php") ?>">&nbsp;&nbsp;+ 詳細</a></li>
				<li><a href="template.php?file=<?php echo escapeHtml(APP_DIR."executor/system/user/sp/form.php") ?>">&nbsp;&nbsp;+ フォーム（入力）</a></li>
				<li><a href="template.php?file=<?php echo escapeHtml(APP_DIR."executor/system/user/sp/confirm.php") ?>">&nbsp;&nbsp;+ フォーム（確認）</a></li>
				<li><a href="template.php?file=<?php echo escapeHtml(APP_DIR."executor/system/user/sp/thanks.php") ?>">&nbsp;&nbsp;+ フォーム（完了）</a></li>
			</ul>

		</div> <!-- /aside -->

		<hr class="noscreen" />

		<!-- Content (Right Column) -->
		<div id="content" class="box">

			<h1>テンプレート編集</h1>
			<h3 class="tit"><?php echo escapeHtml(str_replace(APP_DIR, "", $file)) ?></span></h3>
        <form action="template.php" method="post" onsubmit="return confirm('保存しますか？');">
        	<textarea name="html" cols="80" rows="40"><?php echo escapeHtml($html) ?></textarea>
            <br />
			<input type="submit" value="保存する" />
            <input type="hidden" name="file" value="<?php echo escapeHtml($file) ?>" />
            <input type="hidden" name="action" value="save" />
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