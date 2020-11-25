<?php

// ライブラリ読み込み
require_once WEB_APP."user.php";

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>
</head>
<body id="base">

<!--wrap-->
<div class="wrapper">
	
<!--header-->
<header>
<?php require_once TEMPLATE_DIR.'navi.php' ?>
</header>
<!--/header-->

<div class="boxB white">
	<div class="container">
		<div class="wrap">
			<div class="txt">
				<h3>プロジェクトの扱い</h3>
			</div>
		</div>
		<div class="wrap">
			<div class="txt">
				プロジェクトは自分自身が管理しやすい名前を入れ、<br>
				今後も同じプロジェクトの場合は同じプロジェクト名を入れてください。<br>
				<br>
				右側の参考画面では、D-Report運用プロジェクトのあいだに<br>
				人事プロジェクトが作業として入った例となります。
			</div>
			<p class="image"><a href="../images/howto/003.jpg" target="_blank"><img src="../images/howto/003.jpg"></a></p>
		</div>
		<div class="wrap">
			<div class="txt">
				09:00～10:00 D-Report運用プロジェクト メンテナンス作業<br>
				<br>
				10:00～11:00 人事プロジェクト 電話応対<br>
				<br>
				11:00～12:00 D-Report運用プロジェクト お問い合わせフォーム項目変更<br>
				<br>
			</div>
			<p class="image"><a href="../images/howto/005.jpg" target="_blank"><img src="../images/howto/005.jpg"></a></p>
		</div>
		<div class="wrap">
			<div class="txt">
				プロジェクト名をまとめることで、<br>
				内部的にプロジェクトごとの工数をまとめることができます。
			</div>
			<p class="image"><a href="../images/howto/006.jpg" target="_blank"><img src="../images/howto/006.jpg"></a></p>
		</div>
	</div>
</div>

<footer>
<?php require_once TEMPLATE_DIR.'footer.php' ?>
</footer>

<!--js-->
<script src="../js/scripts.js"></script>
<!--/js-->
	
</div>
<!--/wrap-->

</body>
</html>
