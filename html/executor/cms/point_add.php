<?php

# パラメータ設定
$arrParam = array(
	"user_id" => "ID",
	"point_division_id" => "内容",
	"point" => "ポイント",
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
$infoUser = $objDB->findByIdData('user', $requestData['user_id']);

// ポイント区分
$resPointDivision = $objDB->findData(
	'point_division',
	array(
		"where" => array("stop_flg = 0", "delete_flg = 0"),
		"order" => array("point_division_id"),
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

			<h1>ポイント管理</h1>
			<h3 class="tit">ポイント付与</span></h3>
			
<?php if (isset($errMsg)) { ?>
<p class="msg warning">
<?php foreach ($errMsg as $val) { ?>
<?php echo escapeHtml($val) ?><br />
<?php } ?>
</p>
<?php } ?>

<form action="point_add_con.php" method="post" enctype="multipart/form-data">
<table>

  <tbody>
  <tr>
    <th>会員</th>
    <td>
      <?php echo h($infoUser['name1']) ?> <?php echo h($infoUser['name2']) ?>
    </td>
  </tr>
  <tr>
    <th>ポイント区分</th>
    <td>
    	<select name="point_division_id">
<?php foreach ($resPointDivision as $key => $val) { ?>
		<option value="<?php echo h($val['point_division_id']) ?>" <?php if ($point_division_id == $val['point_division_id']) echo "selected"; ?>><?php echo h($val['point_division']) ?></option>
<?php } ?>
	</select>
    </td>
  </tr>
  <tr>
    <th>付与ポイント数</th>
    <td>
    	<input type="text" name="point" value="<?php echo h($point) ?>" size="40" id="">    </td>
  </tr>
</tbody></table>
<input type="hidden" name="user_id" value="<?php echo h($user_id) ?>">
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