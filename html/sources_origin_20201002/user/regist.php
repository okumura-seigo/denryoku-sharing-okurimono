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
<?php if (count($errMsg) > 0) { ?>
<div style="padding:10px; color:#FF0000;">
<?php foreach ($errMsg as $val) { ?>
・<?php echo h($val) ?><br>
<?php } ?>
</div>
<?php } ?>
				<form action="regist_con" method="post">
メールアドレス<input type="email" name="email" placeholder="メールアドレスを入力" value="<?php echo h($email) ?>" ><br>
パスワード<input type="password" name="passwd" placeholder="パスワードを入力" value="<?php echo h($passwd) ?>" ><br>
パスワード（確認）<input type="password" name="passwd_con" placeholder="パスワードを入力" value="<?php echo h($passwd_con) ?>" ><br>
お名前<input type="text" name="name1" placeholder="姓を入力" value="<?php echo h($name1) ?>" > <input type="text" name="name2" placeholder="名を入力" value="<?php echo h($name2) ?>" ><br>
フリガナ<input type="text" name="furi1" placeholder="セイを入力" value="<?php echo h($furi1) ?>" > <input type="text" name="furi2" placeholder="メイを入力" value="<?php echo h($furi2) ?>" ><br>
都道府県<select name="pref">
<option value="">選択してください</option>
<?php foreach ($PREFECTURE_CODE as $key => $val) { ?>
<option value="<?php echo h($key) ?>" <?php if ($pref == $key) echo "selected"; ?>><?php echo h($val) ?></option>
<?php } ?>
</select>
				<br>
				<input type="submit" value="確認する" class="btn">
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
