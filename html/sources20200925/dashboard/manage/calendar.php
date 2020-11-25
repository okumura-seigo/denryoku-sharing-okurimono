<?php

# パラメータ設定
$arrParam = array(
	"staff" => "スタッフ",
);
// ライブラリ読み込み
require_once WEB_APP."manager.php";

// データ取得
$requestData = getRequestData($arrParam);

if (is_numeric($requestData['staff'])) {
	$infoStaff = $objDB->findRowData(
		'user',
		array(
			'where' => array("user_id = ?", "team_id = ?", "delete_flg = 0", "stop_flg = 0"),
			'param' => array($requestData['staff'], $infoLoginUser['team_id']),
		)
	);
	if (isset($infoStaff['user_id'])) {
		$_SESSION['manageUserId'] = $infoStaff['user_id'];
	} else {
		unset($_SESSION['manageUserId']);
	}
}

// スタッフ一覧
$resStaff = $objDB->findData(
	"user",
	array(
		"where" => array("team_id = ?", "delete_flg = 0", "stop_flg = 0"),
		"param" => array($infoLoginUser['team_id']),
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
<link rel="stylesheet" href="../../css/style.css">
<link href='../../js/fullcalendar/fullcalendar.css' rel='stylesheet' />
<link href='../../js/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<link rel="stylesheet" href="../../js/tapsuggest/TapSuggest.css">
<script src='../../js/fullcalendar/lib/moment.min.js'></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src='../../js/fullcalendar/lib/calendar_manage.js'></script>
<script src='../../js/fullcalendar/fullcalendar.min.js'></script>
<script src="../../js/tapsuggest/Jquery.TapSuggest.js"></script>
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
<div class="boxB">
	<div class="container">
		<div class="wrap">
			<h3>カレンダー確認<?php if (isset($infoStaff['user_id'])) { ?> - <?php echo h($infoStaff['name']) ?><?php } ?></h3>

			<div style="font-size:12px; margin-bottom:20px;">
<?php foreach ($resStaff as $key => $val) { ?>
			<a href="calendar?staff=<?php echo h($val['user_id']) ?>"><?php echo h($val['name']) ?></a>
<?php } ?>
			</div>

<div id='calendar'></div>

		</div>
	</div>
</div>
<!--/container-->

<footer>
<?php require_once TEMPLATE_DIR.'footer.php' ?>
</footer>
	
</div>
<!--/wrap-->

</body>
</html>