<?php

# パラメータ設定
$arrParam = array(
	"title" => "タイトル",
	"info" => "項目情報",
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
if (!is_array($requestData['info'])) {
	$requestData['info'] = array();
	for ($i = 0;$i <= 100;$i++) $requestData['info'][$i] = '';
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
			<h3 class="tit">CMS登録</span></h3>

<?php if (isset($errMsg)) { ?>
<p class="msg warning">
<?php foreach ($errMsg as $key => $val) { ?>
<?php echo escapeHtml($val) ?><br />
<?php } ?>
</p>
<?php } ?>

<form action="cms_add01.php" method="post">
<table>
  <tr>
    <th>CMSタイトル</th>
    <td><input name="title" type="text" id="title" value="<?php echo escapeHtml($title) ?>" size="60" maxlength="100" /></td>
  </tr>
  <tr>
    <th>項目情報</th>
    <td>項目タイプごとに必要な数を記入してください<br />
      <br />
    ■数値項目<br />
    <input type="text" name="info[1]" value="<?php echo escapeHtml(empty0($info[1])) ?>" size="2" maxlength="2" />
    入力型<br />
    <input type="text" name="info[2]" value="<?php echo escapeHtml(empty0($info[2])) ?>" size="2" maxlength="2" />
単一選択型（セレクトボックス）<br />
<input name="info[15]" type="text" id="info[15]" value="<?php echo escapeHtml(empty0($info[15])) ?>" size="2" maxlength="2" />
単一選択型（ラジオボタン）<br />
<input name="info[17]" type="text" id="info[17]" value="<?php echo escapeHtml(empty0($info[17])) ?>" size="2" maxlength="2" /> 
hidden型
<br />
    <span class="explain_msg">　整数のみ入力可能な項目</span><br />
    ■小数点項目<br />
    <input type="text" name="info[3]" value="<?php echo escapeHtml(empty0($info[3])) ?>" size="2" maxlength="2" />
入力型<br />
    <span class="explain_msg">　小数点付数字のみ入力可能な項目</span><br />
    ■文字列項目<br />
    <input type="text" name="info[4]" value="<?php echo escapeHtml(empty0($info[4])) ?>" size="2" maxlength="2" />
    入力型<br />
    <input type="text" name="info[5]" value="<?php echo escapeHtml(empty0($info[5])) ?>" size="2" maxlength="2" />
単一選択型（セレクトボックス）<br />
<input name="info[16]" type="text" id="info[16]" value="<?php echo escapeHtml(empty0($info[16])) ?>" size="2" maxlength="2" />
単一選択型（ラジオボタン）<br />
<input name="info[18]" type="text" id="info[18]" value="<?php echo escapeHtml(empty0($info[18])) ?>" size="2" maxlength="2" />
hidden型 <br />
<input name="info[20]" type="text" id="info[20]" value="<?php echo escapeHtml(empty0($info[20])) ?>" size="2" maxlength="2" />
パスワード型 <br />
    <span class="explain_msg">　100文字以内の文字列が入力可能な項目</span><br />
    ■テキスト項目<br />
    <input type="text" name="info[6]" value="<?php echo escapeHtml(empty0($info[6])) ?>" size="2" maxlength="2" />
入力型<br />
<input type="text" name="info[7]" value="<?php echo escapeHtml(empty0($info[7])) ?>" size="2" maxlength="2" />
単一選択型（セレクトボックス）<br />
<input type="text" name="info[8]" value="<?php echo escapeHtml(empty0($info[8])) ?>" size="2" maxlength="2" />
複数選択型<br />
<input type="text" name="info[14]" value="<?php echo escapeHtml(empty0($info[14])) ?>" size="2" maxlength="2" />
wysiwyg型<br />
    <span class="explain_msg">　改行付テキストが入力可能な項目</span><br />
    ■日付項目<br />
    <input type="text" name="info[9]" value="<?php echo escapeHtml(empty0($info[9])) ?>" size="2" maxlength="2" />
選択型<br />
    <span class="explain_msg">　日付のみ入力可能な項目</span><br />
    ■タイムスタンプ項目<br />
    <input type="text" name="info[10]" value="<?php echo escapeHtml(empty0($info[10])) ?>" size="2" maxlength="2" />
選択型<br />
    <span class="explain_msg">　日付+時間のみ入力可能な項目</span><br />
■画像項目<br />
<input type="text" name="info[11]" value="<?php echo escapeHtml(empty0($info[11])) ?>" size="2" maxlength="2" />
入力型<br />
<span class="explain_msg">　画像のファイルアップロード項目</span><br />
■ファイル項目<br />
<input name="info[12]" type="text" id="info[12]" value="<?php echo escapeHtml(empty0($info[12])) ?>" size="2" maxlength="2" />
入力型<br />
<span class="explain_msg">　画像以外のファイルアップロード項目</span> <br />
■外部キー<br />
<input name="info[13]" type="text" id="info[13]" value="<?php echo escapeHtml(empty0($info[13])) ?>" size="2" maxlength="2" /> 
単一選択型
（セレクトボックス）<br />
<input name="info[19]" type="text" id="info[19]" value="<?php echo escapeHtml(empty0($info[19])) ?>" size="2" maxlength="2" />
hidden型 <br />
<span class="explain_msg">　他のCMSの選択項目</span> </td>
  </tr>
</table>
<input type="submit" value="項目詳細登録" />
</form>

<h3 class="tit">サンプル</span></h3>
例）<br />
■入力型<br />
<input type="text" />（数値項目など<br />
<textarea></textarea>（テキスト項目<br />
<input type="file" />（ファイル項目
<br />
<br />
■単一選択型（セレクトボックス）<br />
<select></select>
（数値項目、外部キーなど<br />
<br />
■単一選択型（ラジオボタン）<br />
<input type="radio" /><input type="radio" />
（数値項目、外部キーなど<br />
<br />
<br />
■複数選択型<br />
<input type="checkbox" /><input type="checkbox" />（テキスト項目
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