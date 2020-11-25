<?php

# パラメータ設定
$arrParam = array(
	"date" => "日付",
	"subject" => "メールタイトル",
	"body" => "メール内容",
);

// ライブラリ読み込み
require_once WEB_APP."user.php";
// モジュール読み込み
loadLibrary("mail");

// データ取得
$requestData = getRequestData($arrParam);

// エラーチェック
$errMsg = actionValidate("send_report_val", $requestData, $arrParam);
if (count($errMsg) == 0) {
	$expMail = explode("\n", $infoLoginTeam['report_send_list']);
	foreach ($expMail as $key => $val) {
		if (checkData(trim($val), "email")) sendMail(trim($val), $requestData['subject'], $requestData['body'], FROM_MAIL, ADM_MAILER);
	}
	$requestData['user_id'] = $infoLoginUser['user_id'];
	$objDB->insertData("day_report", $requestData);
	redirectUrl("report-execute");
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
				<h3>日報の送信</h3>
				<p><i class="fa fa-check-circle" aria-hidden="true" style="font-size:10.0rem; color:limegreen; margin-bottom: 10px;"></i><br></p>
				<p class="mb30">日報を送信しました</p>
				<a href="http://d-rpt.com/dashboard/calendar" class="btn">カレンダーへ戻る</a>
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
