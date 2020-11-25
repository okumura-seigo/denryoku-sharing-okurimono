<?php

// ライブラリ読み込み
require_once WEB_APP."mypage.php";
$EditProfile = new EditProfile();
$edit_datas = $_POST;
$EditProfile->upDateProfile($edit_datas);

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
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

<style type="text/css">
.bg-light-blue {
	background-color: lightblue;
}

.w-fc{
	width: fit-content;
}
</style>

</head>
<body id="base">

<!--header-->
<header>
<?php require_once TEMPLATE_DIR.'navi.php' ?>
</header>
<!--/header-->

<article class="w-75 m-auto">
	<p class="bg-light-blue px-1 w-fc">会員情報の照会・変更</p>
	<div class="w-75 m-auto">
		<p>会員情報変更は正常に終了いたしました。</p>
		<div class="text-center">
			<a href="edit" class="btn btn-primary px-5 py-2">戻る</a>
		</div>
	</div>
</article>

<footer>
<?php require_once TEMPLATE_DIR.'footer.php' ?>
</footer>

<!--js-->
<script src="js/scripts.js"></script>
<!--/js-->

</body>
</html>
