<?php

# パラメータ設定
$arrParam = array(
	"passwd_now" => "現在のパスワード",
	"passwd" => "新しいパスワード",
	"passwd_con" => "新しいパスワード（確認用）",
);

// ライブラリ読み込み
require_once WEB_APP."user.php";
// モジュール読み込み
loadLibrary("mail");

// データ取得
$requestData = getRequestData($arrParam);

// エラーチェック
$errMsg = actionValidate("user_password_val", $requestData, $arrParam);
if (count($errMsg) == 0) {
	$requestData['passwd'] = password_hash($requestData['passwd'], PASSWORD_DEFAULT);
	$objDB->updateData("user", array("passwd" => $requestData['passwd']), $infoLoginUser['user_id']);
	redirectUrl("password_exe");
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

<!--header-->
<header>
<?php require_once TEMPLATE_DIR.'navi.php' ?>
</header>
<!--/header-->

<div class="boxA">
	<div class="container">
		<div class="wrap">
			<div class="txt">
				<h3>パスワードの変更</h3>
				<p><i class="fa fa-check-circle" aria-hidden="true" style="font-size:10.0rem; color:limegreen; margin-bottom: 10px;"></i><br></p>
				<p class="mb30">パスワードを変更しました</p>
				<a href="../" class="btn">マイページへ</a>
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

</body>
</html>
