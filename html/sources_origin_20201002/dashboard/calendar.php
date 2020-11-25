<?php

// ライブラリ読み込み
require_once WEB_APP."user.php";

// チームプロジェクト
$resProject = $objDB->findData(
	"project",
	array(
		"where" => array("team_id = ?", "new_task_date >= ?", "private_flg = 0", "stop_flg = 0", "delete_flg = 0"),
		"order" => array("new_task_date", "team_id"),
		"param" => array($infoLoginUser['team_id'], date("Y-m-d H:i:s", strtotime("-3 months"))),
	)
);

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

.modal-content {
	/*追加分*/
	z-index:2;
	position:fixed;
	background-color:#FFFFFF;
	border-radius: 8px;
	padding: 30px 20px;
	font-size: 14px;
}
.modal-content input {
	margin: 10px 0 0 0;
	font-size: 16px;
}
.modal-content input[type="time"] {
	margin: 5px 10px 5px 5px;
	padding: 10px 0 10px 10px;
	font-size: 16px;
	width: 6.5em;
	text-align: left;
}
#modal-overlay {
	z-index:1;
	display:none;
	position:fixed;
	top:0;
	left:0;
	width:100%;
	height:120%;
	background-color:rgba(0,0,0,0.75);
}
#modal-day-atext {
	width: 100%;
	min-height: 80px;
	box-sizing: border-box;
	padding: 10px;
}
.button-link {
	color: #fff;
	background: #3fc097;
	text-decoration: none;
	width: 33%;
	text-align: center;
	line-height: 40px;
	border: 1px solid #3fc097;
	margin-right: 1px;
	box-sizing: border-box;
	float: left;
}
.button-link:hover {
	color: #3fc097;
	background: #fff;
}
.button-link-gray {
	color: #fff;
	background: #999;
	text-decoration: none;
	width: 33%;
	text-align: center;
	line-height: 40px;
	border: 1px solid #999;
	margin-right: 1px;
	box-sizing: border-box;
	float: left;
}
.button-link-gray:hover {
	color: #999;
	background: #fff;
}
.modal_title {
	border-left: 2px solid #3fc097;
	padding-left: 6px;
}
</style>
</head>
<body class="calendar">
	
<!--wrap-->
<div class="wrapper">

<!--header-->
<header>
<?php require_once TEMPLATE_DIR.'navi.php' ?>
</header>
<!--/header-->

<!--container-->
<div class="container">

	<form action="report" method="post">
	<div class="calender_create"><input name="date" type="text" value="<?php echo h(date("Y/m/d")) ?>">の日報を作成します<input type="submit" value="文面を確認する" class="btn"></div>
	</form>
	
	<div id='calendar'></div>

	<div id="modal-day" class="modal-content" style="display:none; background-color:#FFFFFF;">
		<div class="mb20">
			<span class="modal_title">プロジェクト名<span style="font-size:10px;"> <a href="howto" target="_blank" style="color:red;">入力方法はこちら</a></span></span>
			<input type="text" name="name" id="modal-day-name" autocomplete="off"><br>
			<div id="tsArea"></div>
		</div>
		<div class="mb20">
			<span class="modal_title">作業タイトル</span>
			<input type="text" name="title" id="modal-day-title">
		</div>
		<p class="mb10">
			<span class="modal_title">登録日</span>　<span id="modal-day-date"></span><input type="hidden" name="date" value="" id="modal-day-date-param">
		</p>
		<p class="mb10">
			<span class="modal_title">開始時間</span>
			<input type="time" name="start_time" id="modal-day-start_time">　
			<span class="modal_title">終了時間</span>
			<input type="time" name="end_time" id="modal-day-end_time">
		</p>
		<textarea name="atext" id="modal-day-atext" class="mb10"></textarea>
		<div class="clearfix">
			<a id="modal-regist" class="button-link">登録する</a>
			<a id="modal-edit" class="button-link">編集する</a>
			<a id="modal-close" class="button-link">閉じる</a>
			<a id="modal-drop" class="button-link-gray">削除する</a>
		</div>
	</div>
</div>
<!--/container-->

<footer>
<?php require_once TEMPLATE_DIR.'footer.php' ?>
</footer>

<script type="text/javascript">
$(function(){
	$('#tsArea').TapSuggest({
		tsInputElement : '#modal-day-name',
		tsArrayList : [<?php foreach ($resProject as $key => $val) { ?>'<?php echo h($val['name']) ?>'<?php if (count($resProject) - 1 > $key) { ?>, <?php } ?><?php } ?>],
		tsRegExpAll : true
	});
});
</script>
	
</div>
<!--/wrap-->

</body>
</html>