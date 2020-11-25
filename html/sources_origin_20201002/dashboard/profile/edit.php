<?php

// ライブラリ読み込み
require_once WEB_APP."mypage.php";

if (count($_POST) == 0) {
	$EditProfile = new EditProfile();
	$user_datas = $EditProfile->getProfile($edit_datas);
}
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
		<form name="edit_form" action="edit_con" method="post">
			<table class="w-100">
				<tr class="row">
					<td class="col-4">氏名</td>
					<td class="col-8">
					<label>姓</label><input type="text" name="name1" placeholder="姓" value="<?php echo $user_datas['name1'] ?>">
					<label>名</label><input type="text" name="name2" placeholder="名" value="<?php echo $user_datas['name2'] ?>"><br>
					<label>セイ</label><input type="text" name="firi1" placeholder="セイ" value="<?php echo $user_datas['furi1'] ?>">
					<label>メイ</label><input type="text" name="firi2" placeholder="メイ" value="<?php echo $user_datas['furi2'] ?>"><br>
					</td>
				</tr>
				<tr class="row">
					<td class="col-4">メールアドレス</td>
					<td class="col-8"><?php echo $user_datas['email']; ?></td>
				</tr>
				<tr class="row">
					<td class="col-4">性別</td>
					<td class="col-8">
						<input type="radio" name="sex" value="0" <?php if($user_datas['sex'] == '0') { echo 'checked="checked"'; } ?> ><label>男性</label>
						<input type="radio" name="sex" value="1" <?php if($user_datas['sex'] == '1') { echo 'checked="checked"'; } ?> ><label>女性</label>
					</td>
				</tr>
				<tr class="row">
					<td class="col-4">生年月日</td>
					<td class="col-8"><?php echo ($user_datas['birthday']); ?></td>
				</tr>
				<tr class="row">
					<td class="col-4">職業</td>
					<td class="col-8"><input type="text" name="works" placeholder="職業" value="<?php echo $user_datas['works'] ?>"></td>
				</tr>
				<tr class="row">
					<td class="col-4">郵便番号</td>
					<td class="col-8"><input type="text" name="post" placeholder="郵便番号" value="<?php echo $user_datas['post'] ?>"></td>
				</tr>
			</table>
			<div class="text-center">
				<button class="btn btn-primary px-5 py-2" onClick="document.edit_form.submit();return false;">保存</button>
			</div>
		</form>
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
