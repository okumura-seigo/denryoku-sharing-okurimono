<?php
	$edit_datas = $_POST;
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
		<form name="yes_form" action="edit_exe" method="post">
			<?php
				foreach ($edit_datas as $key => $val) {
					echo '<input type="hidden" name="'.$key.'" value="'.$val.'">';
				}
			?>
		</form>
		<form name="no_form" action="edit" method="post">
			<?php
				foreach ($edit_datas as $key => $val) {
					echo '<input type="hidden" name="'.$key.'" value="'.$val.'">';
				}
			?>
		</form>
		<table class="w-100">
			<tr class="row">
				<td class="col-4">氏名</td>
				<td class="col-8">
					<label class="px-2">姓</label><?php echo $edit_datas['name1']; ?>
					<label class="px-2">名</label><?php echo $edit_datas['name2']; ?>
					<br>
					<label class="px-2">セイ</label><?php echo $edit_datas['firi1'] ?>
					<label class="px-2">メイ</label><?php echo $edit_datas['firi2'] ?>
					<br>
				</td>
			</tr>
			<tr class="row">
				<td class="col-4">性別</td>
				<td class="col-8">
					<?php
						if($edit_datas['sex'] == '0') {
							echo '<label>男性</label>';
						} else {
							echo '<label>女性</label>';
						}
					?>
				</td>
			</tr>
			<tr class="row">
				<td class="col-4">職業</td>
				<td class="col-8"><?php echo $edit_datas['works'] ?></td>
			</tr>
			<tr class="row">
				<td class="col-4">郵便番号</td>
				<td class="col-8"><?php echo $edit_datas['post'] ?></td>
			</tr>
		</table>
		<div class="text-center">
			<p>上記の内容でよろしいですか？</p>
			<button class="btn btn-primary px-3 py-2" onClick="document.yes_form.submit();return false;">はい</button>
			<button class="btn btn-primary px-3 py-2" onClick="document.no_form.submit();return false;">いいえ</button>
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
