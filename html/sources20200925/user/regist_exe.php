<?php

# パラメータ設定
$arrParam = array(
	"email" => "メールアドレス",
	"passwd" => "パスワード",
	"passwd_con" => "パスワード(確認)",
	"name1" => "お名前(姓)",
	"name2" => "お名前(名)",
	"furi1" => "フリガナ(セイ)",
	"furi2" => "フリガナ(メイ)",
	"pref" => "都道府県",
);

// ライブラリ読み込み
require_once WEB_APP."public.php";

// データ取得
$requestData = getRequestData($arrParam);

// エラーチェック
$errMsg = actionValidate("user_regist_val", $requestData, $arrParam);
if (count($errMsg) > 0) exit;

// DB操作
$requestData['passwd'] = password_hash($requestData['passwd'], PASSWORD_DEFAULT);
$result = $objDB->insertData("user", $requestData);
if ($result == false) {
	exit;
}

// ログイン情報保持
$_SESSION['loginUserId'] = $objDB->lastInsertId("user");

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
<body id="resist">
	
<!--wrap-->
<div class="wrapper">

<!--header-->
<header>
<?php require_once TEMPLATE_DIR.'navi.php' ?>
</header>
<!--/header-->

<div class="container">
<div class="boxA">
		<div class="wrap">
			<div class="txt">
				<h3>会員登録</h3>
会員登録が完了しました。<br>
<br>
<a href="../dashboard/">マイページへ</a>
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
