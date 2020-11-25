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

// 会員情報
$resUser = $objDB->findData(
	'user',
	array(
		"where" => array("user_id in (0".str_repeat(",?", count($requestData['ids'])).")", "stop_flg = 0", "delete_flg = 0"),
		"param" => $requestData['ids'],
	)
);

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
			
<?php if (isset($errMsg)) { ?>
<p class="msg warning">
<?php foreach ($errMsg as $val) { ?>
<?php echo escapeHtml($val) ?><br />
<?php } ?>
</p>
<?php } ?>

<form action="send_message_con.php" method="post" enctype="multipart/form-data">
<table>

  <tbody>
  <tr>
    <th>会員</th>
    <td>
<?php foreach ($resUser as $key => $val) { ?>
      <?php echo h($val['name1']) ?> <?php echo h($val['name2']) ?><?php if (count($resUser) - 1 != $key) { ?>, <?php } ?>
<?php } ?>
    </td>
  </tr>
  <tr>
    <th>件名</th>
    <td>
    	<input type="text" name="title" value="<?php echo h($title) ?>" size="40" id="title">    </td>
  </tr>
  <tr>
    <th>内容</th>
    <td>
    	<textarea name="content" cols="60" rows="8" id=""><?php echo h($content) ?></textarea>    </td>
  </tr>
  <tr>
    <th>関連リンク1</th>
    <td>
    	<input type="text" name="link1" value="<?php echo h($link1) ?>" size="40" id="link1">    </td>
  </tr>
  <tr>
    <th>関連リンク2</th>
    <td>
    	<input type="text" name="link2" value="<?php echo h($link2) ?>" size="40" id="link2">    </td>
  </tr>
  <tr>
    <th>関連リンク3</th>
    <td>
    	<input type="text" name="link3" value="<?php echo h($link3) ?>" size="40" id="link3">    </td>
  </tr>
  <tr>
    <th>添付ファイル1</th>
    <td>
    	<input type="file" name="file1" id="file1">    </td>
  </tr>
  <tr>
    <th>添付ファイル2</th>
    <td>
    	<input type="file" name="file2" id="file2">    </td>
  </tr>
  <tr>
    <th>添付ファイル3</th>
    <td>
    	<input type="file" name="file3" id="file3">    </td>
  </tr>
</tbody></table>
<?php foreach ($ids as $key => $val) { ?>
<input type="hidden" name="ids[]" value="<?php echo h($val) ?>">
<?php } ?>
<input type="submit" value="確認する">
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