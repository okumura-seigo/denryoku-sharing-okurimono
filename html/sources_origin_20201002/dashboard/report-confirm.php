<?php

# パラメータ設定
$arrParam = array(
	"date" => "日付",
	"subject" => "メールタイトル",
	"body" => "メール内容",
);

// ライブラリ読み込み
require_once WEB_APP."user.php";

// データ取得
$requestData = getRequestData($arrParam);
if (!checkData($requestData['date'], "date")) exit;

// エラーチェック
$errMsg = actionValidate("send_report_val", $requestData, $arrParam);
if (count($errMsg) > 0) {
	require_once 'report.php';
	exit;
}

// 出力設定
extract($requestData);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
<title></title>
<meta name="description" content="">
<meta name="keywords" content="">
<link rel="stylesheet" href="../css/style.css">
</head>
<body id="base">

<!--wrap-->
<div class="wrapper">

<!--header-->
<header>
<?php require_once TEMPLATE_DIR.'navi.php' ?>
</header>
<!--/header-->

<div class="boxA">
	<div class="container">
		<div class="wrap">
			<div class="txt">
				<h3>日報の確認</h3>
				<div style="width:70%; padding:10px; font-size:1.4rem; margin:0 auto; text-align:left;"><p style="margin-bottom:10px; padding-bottom:10px; border-bottom: 1px solid #ccc;">Subject: <?php echo h($subject) ?></p>
				<?php echo nl2br(h($body)) ?></div>
				<br>
				<form action="report-execute" method="post" style="display:inline;">
				<input type="submit" value="メールを送信する" class="btn">
				<input type="hidden" name="<?php echo h(CRYPT_PARAM) ?>" value="<?php echo h(encryptParam($requestData)) ?>">
				</form>
				<form action="report" method="post" style="display:inline;">
				<input type="submit" value="修正する" class="btn">
				<input type="hidden" name="<?php echo h(CRYPT_PARAM) ?>" value="<?php echo h(encryptParam($requestData)) ?>">
				</form>
			</div>
		</div>
	</div>
</div>

<footer>
<?php require_once TEMPLATE_DIR.'footer.php' ?>
</footer>

<!--js-->
<script src="js/scripts.js"></script>
<!--/js-->

</div>
<!--/wrap-->
	
</body>
</html>
