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
				<h3>メンバー登録</h3>
<?php if (count($errMsg) > 0) { ?>
				<div style="color:#FF0000; padding:10px;">
<?php foreach ($errMsg as $val) { ?>
				・<?php echo h($val) ?><br>
<?php } ?>
				</div>
<?php } ?>
				<form action="member-add-confirm" method="post">
				<input type="text" name="name" placeholder="山田 太郎" value="<?php echo h($name) ?>">
				<br>
				<input type="text" name="email" placeholder="sample@d-rpt.com" value="<?php echo h($email) ?>">
				<br>
				<input type="password" name="passwd" placeholder="パスワードを入力" value="<?php echo h($passwd) ?>">
				<br>
				<input type="password" name="passwd_con" placeholder="パスワード(確認用)を入力" value="<?php echo h($passwd_con) ?>">
				<br>
				<input type="submit" value="入力内容を確認する" class="btn">
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
