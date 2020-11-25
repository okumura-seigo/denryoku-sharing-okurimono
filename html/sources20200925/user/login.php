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
if (count($requestData) == 0) $requestData['save'] = 1;

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
</header>
<!--/header-->

<div class="container">
	<div class="boxA">
		<div class="wrap">
			<div class="txt">
				<h3>メンバーログイン</h3>
<?php if (count($errMsg) > 0) { ?>
<div style="padding:10px; color:#FF0000;">
<?php foreach ($errMsg as $val) { ?>
・<?php echo h($val) ?><br>
<?php } ?>
</div>
<?php } ?>
				<form action="login_exe" method="post">
				ログインID：<input type="email" name="loginid" placeholder="メールアドレス" value="<?php echo h($loginid) ?>" >
				<br>
				パスワード：<input type="password" name="passwd" placeholder="パスワード">
				<br>
				<input type="checkbox" name="save" value="1" <?php if ($save == 1) echo "checked"; ?>>ログイン状態を保存する
				<br>
				<input type="submit" value="ログインする" class="btn">
				<input type="hidden" name="<?php echo h(CRYPT_PARAM) ?>" value="<?php echo h(encryptParam(array("action" => "login", "time" => date("YmdHis"),))) ?>">
				<br>
				<div class="regist_link">
					<a href="./remind" class="arrow">パスワードを忘れた方はこちら</a>
				</div>
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
