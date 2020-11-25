<?php

# パラメータ設定
$arrParam = array(
	"loginid" => "ログインID",
	"passwd" => "パスワード",
	"save" => "ログイン保持",
);

// ライブラリ読み込み
require_once WEB_APP."public.php";

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
				<h3>パスワードを忘れた方</h3>
				<p class="mb10">登録されているメールアドレスをご入力ください。</p>
<?php if (count($errMsg) > 0) { ?>
<div style="padding:10px; color:#FF0000;">
<?php foreach ($errMsg as $val) { ?>
・<?php echo h($val) ?><br>
<?php } ?>
</div>
<?php } ?>
				<form action="remind_exe" method="post">
				<input type="text" name="loginid" placeholder="メールアドレス" value="<?php echo h($loginid) ?>" >
				<br>
				<input type="submit" value="送信する" class="btn">
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
