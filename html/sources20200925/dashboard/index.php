<?php

	// ライブラリ読み込み
	require_once WEB_APP."user.php";
	require_once WEB_APP."mypage.php";

	$my_page = new MyPage();
	$my_page_datas = $my_page->getMyPageData();

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
#calendar {
	font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
	font-size: 14px;
	max-width: 900px;
	margin: 0 auto;
}
.fc-sun, .fc-sat {
	color: #FF0000;
}

.modal-content{
	/*追加分*/
	z-index:2;
	position:fixed;
	background-color:#FFFFFF;
}
#modal-overlay{
	z-index:1;
	display:none;
	position:fixed;
	top:0;
	left:0;
	width:100%;
	height:120%;
	background-color:rgba(0,0,0,0.75);
}

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

<!--container-->
<div class="container">
<a href="./profile/edit">会員情報の変更</a>
<a href="./profile/password">パスワードの変更</a>
<a href="./point/">ポイント履歴</a>
<a href="./message/">受信ボックス</a>
<a href="./project/">プロジェクト参加履歴</a>
<a href="./contact/">お問い合わせ</a>
</div>
<!--/container-->

<article class="w-100">
	<div class="row m-0">
		<div class="col-3">
			<p class="bg-light-blue px-1 w-fc">現在の所有ポイント</p>
			<p><strong><?php echo $my_page_datas['total_point']; ?></strong><small>ポイント</small></p>
		</div>
		<div class="col-9">
			<div class="row m-0">
				<div class="col-12">
					<p class="bg-light-blue px-1 w-fc">受信ボックス</p>
					<?php
						foreach ($my_page_datas['disp_messages'] as $key => $val) {
							echo '<form name="friendForm'.$key.'" action="message/message_detail" method="post">';
							echo '<input type="hidden" name="id" value="'.$val['message_id'].'">';
							echo '</form>';
							echo '<p>';
							echo '<span class="pr-3">';
							echo $val['insert_datetime'];
							echo '</span>';
							echo '<a href="" class="btn btn-link" onClick="document.friendForm'.$key.'.submit();return false;">';
							if ($val['read_flg'] == 0) echo '[未読] ';
							echo $val['title'].'</a>';
							echo '</p>';
						}
					?>
				</div>
				<div class="col-12">
					<p class="bg-light-blue px-1 w-fc">プロジェクト参加履歴</p>
					<?php
						foreach ($my_page_datas['disp_project'] as $key => $val) {
							echo '<p>';
							echo '<span class="pr-3">';
							echo $val['project_start_day'];
							echo '</span>';
							echo '<a href="project/" class="btn btn-link">'.$val['project_name'].'</a>';
							echo '</p>';
						}
					?>
				</div>
			</div>
		</div>
	</div>
</article>


<footer>
<?php require_once TEMPLATE_DIR.'footer.php' ?>
</footer>

</body>
</html>