<?php

	// ライブラリ読み込み
	require_once WEB_APP."user.php";
	require_once WEB_APP."mypage.php";

	$my_page = new MyPage();
	$my_page_datas = $my_page->getMyPageData();
	$_POST['page_no'] ? $page_no = $_POST['page_no'] : $page_no = 1;
	$messages_conut = $my_page->getMessageCount();
	$message_datas = $my_page->getMessageListData($page_no);
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
			<?php

				foreach ($message_datas as $key => $val) {

					echo '<tr class="border">';
					echo '<td>';
					echo '<p>';
					echo '<span class="pr-3">';
					echo $val['insert_datetime'];
					echo '</span>';
					echo $val['title'];
					echo '</p>';
					echo '</td>';
					echo '<form name="friendForm'.$key.'" action="message_detail" method="post">';
					echo '<input type="hidden" name="id" value="'.$val['message_id'].'">';
					echo '</form>';
					echo '<td><button class="btn-primary" onClick="document.friendForm'.$key.'.submit();return false;">詳細</button></td>';
					echo "</tr>";
				}
			?>
		</table>
		<div class="w-100">
			<?php
				$max_count = ceil((int)$messages_conut['count'] / 5);
				for($i=1; $max_count>=$i; $i++) {
					echo '<form name="pager'.$i.'" action="" method="post">';
					echo '<input type="hidden" name="page_no" value="'.$i.'">';
					echo '</form>';
				}
			?>
			<p class="py-2 text-center">
				<?php
					for($i=1; $max_count>=$i; $i++) {
						if($i >= 2) {
							echo ' | ';
						}
						if($i == $page_no) {
							echo '<span>'.$i.'</span>';
						} else {
							echo '<a href="" onClick="document.pager'.$i.'.submit();return false;">'.$i.'</a>';
						}
					}
				?>
			</p>
		</div>
	</div>
</article>


<footer>
<?php require_once TEMPLATE_DIR.'footer.php' ?>
</footer>

</body>
</html>