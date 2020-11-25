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

// データ取得
$requestData = getRequestData($arrParam);

// エラーチェック
/*
$errMsg = actionValidate("edit_profile_val", $requestData, $arrParam);
if (count($errMsg) > 0) {
	require_once 'profile.php';
	exit;
}
*/

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
				<h3>メンバー登録の確認</h3>
				<p class="mb20">お名前：<?php echo h($name) ?></p>
				<p class="mb20">メールアドレス：<?php echo h($email) ?></p>
				<p class="mb20">パスワード：<?php echo h(str_repeat("*", strlen($passwd))) ?></p>
				<form action="member-add-execute" method="post" style="display:inline;">
				<input type="submit" value="メンバーを登録する" class="btn">
				<input type="hidden" name="<?php echo h(CRYPT_PARAM) ?>" value="<?php echo h(encryptParam($requestData)) ?>">
				</form>
				<form action="member-add" method="post" style="display:inline;">
				<input type="submit" value="キャンセル" class="btn">
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

</body>
</html>
