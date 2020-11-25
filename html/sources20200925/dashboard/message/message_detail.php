<?php

// ライブラリ読み込み
require_once WEB_APP."user.php";
require_once WEB_APP."mypage.php";

$my_page = new MyPage();
$my_page_datas = $my_page->getMyPageData();
$message_datas = $my_page->getMessageData();
$message_details = $my_page->getMessageDetailByMessageId($_POST['id']);

if ($message_details['read_flg'] == 0) {
	$objDB->updateData('message', array('read_flg' => '1'), $message_details['message_id']);
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
<link href='../js/fullcalendar/fullcalendar.css' rel='stylesheet' />
<link href='../js/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<link rel="stylesheet" href="../js/tapsuggest/TapSuggest.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<script src='../js/fullcalendar/lib/moment.min.js'></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src='../js/fullcalendar/lib/calendar.js'></script>
<script src='../js/fullcalendar/fullcalendar.min.js'></script>
<script src="../js/tapsuggest/Jquery.TapSuggest.js"></script>
<style>

.bg-light-blue {
	background-color: lightblue;	
}

.w-fc{
	width: fit-content;
}

</style>
</head>
<body class="calendar">

<!--header-->
<header>
<?php require_once TEMPLATE_DIR.'navi.php' ?>
</header>
<!--/header-->


<article class="w-75 m-auto">
	<p class="bg-light-blue px-1 w-fc">受信ボックス</p>
	<div class="w-75 m-auto">
		<table class="w-100">
			<tr class="row">
				<td class="col-4">受信日</td>
				<td class="col-8"><?php echo $message_details['insert_datetime']; ?></td>
			</tr>
			<tr class="row">
				<td class="col-4">件名</td>
				<td class="col-8"><?php echo $message_details['title']; ?></td>
			</tr>
			<tr class="row">
				<td class="col-4">内容</td>
				<td class="col-8"><?php echo $message_details['content']; ?></td>
			</tr>
			<tr class="row">
				<td class="col-4">関連プロジェクト</td>
				<td class="col-8"></td>
			</tr>
			<tr class="row">
				<td class="col-4">関連リンク</td>
				<td class="col-8">
				<?php
					for ($i = 1;$i <= 3;$i++) {
						if ($message_details['link'.$i]) {
							echo '<a href="'.$message_details['link'.$i].'" target="_blank">'.$message_details['link'.$i].'</a>';
							echo "<br>";
						}
					}
				?>
				</td>
			</tr>
			<tr class="row">
				<td class="col-4">画像</td>
				<td class="col-8">
				<?php
					for ($i = 1;$i <= 3;$i++) {
						if ($message_details['file'.$i]) {
							echo '<img src="'.UPLOAD_FILE_URL.$message_details['file'.$i].'">';
							echo "<br>";
						}
					}
				?>
				</td>
			</tr>
		</table>
	</div>
</article>


<footer>
<?php require_once TEMPLATE_DIR.'footer.php' ?>
</footer>

</body>
</html>