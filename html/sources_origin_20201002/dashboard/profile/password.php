<?php

# パラメータ設定
$arrParam = array(
	"passwd_now" => "現在のパスワード",
	"passwd" => "新しいパスワード",
	"passwd_con" => "新しいパスワード（確認用）",
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
<?php if (count($errMsg) > 0) { ?>
				<div style="color:#FF0000; padding:10px;">
<?php foreach ($errMsg as $val) { ?>
				・<?php echo h($val) ?><br>
<?php } ?>
				</div>
<?php } ?>
				<form action="password_con" method="post">
				<input type="password" name="passwd_now" placeholder="現在のパスワードを入力" value="<?php echo h($passwd_now) ?>">
				<br>
				<input type="password" name="passwd" placeholder="新しいパスワードを入力" value="<?php echo h($passwd) ?>">
				<br>
				<input type="password" name="passwd_con" placeholder="新しいパスワード(確認用)を入力" value="<?php echo h($passwd_con) ?>">
				<br>
				<input type="submit" value="情報を確認する" class="btn">
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
