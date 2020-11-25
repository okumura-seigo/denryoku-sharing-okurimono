<?php

# パラメータ設定
$arrParam = array(
	"name" => "お名前",
	"email" => "メールアドレス",
	"passwd" => "パスワード",
	"passwd_con" => "パスワード（確認用）",
);

// ライブラリ読み込み
require_once WEB_APP."user.php";
// モジュール読み込み
loadLibrary("mail");

// データ取得
$requestData = getRequestData($arrParam);

// エラーチェック
//$errMsg = actionValidate("edit_profile_val", $requestData, $arrParam);
//if (count($errMsg) == 0) {
if (implode("", $requestData) != "") {
	$requestData['team_id'] = $infoLoginTeam['team_id'];
 	$requestData['passwd'] = password_hash($requestData['passwd'], PASSWORD_DEFAULT);
	$requestData['regist_state'] = 1;
	$objDB->insertData("user", $requestData);
	redirectUrl("member-add-execute");
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
<link rel="stylesheet" href="../../css/style.css">
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
				<h3>メンバー登録の完了</h3>
				<p><i class="fa fa-check-circle" aria-hidden="true" style="font-size:10.0rem; color:limegreen; margin-bottom: 10px;"></i><br></p>
				<p class="mb30">メンバーを追加しました</p>
				<a href="member-add" class="btn">更に追加する</a>
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
