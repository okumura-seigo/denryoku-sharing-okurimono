<?php

# パラメータ設定
$arrParam = array(
	"date" => "日付",
	"subject" => "メールタイトル",
	"body" => "メール内容",
);

// ライブラリ読み込み
require_once WEB_APP."user.php";

// データ取得
$requestData = getRequestData($arrParam);
if (!checkData($requestData['date'], "date")) {
	$errMsg = array("日付が正しくありません");
	require_once 'calendar.php';
	exit;
}

// タスク
$resTask = $objDB->findData(
	"task",
	array(
		"column" => array("task.task_id", "project.name", "task.title", "task.start_date", "task.end_date", "task.atext"),
		"join" => array("Inner Join project On task.project_id = project.project_id"),
		"where" => array("task.user_id = ?", "task.start_date >= ?", "task.start_date <= ?", "task.stop_flg = 0", "task.delete_flg = 0", "project.stop_flg = 0", "project.delete_flg = 0"),
		"order" => array("task.start_date", "task_id"),
		"param" => array($infoLoginUser['user_id'], $requestData['date']." 00:00:00", $requestData['date']." 23:59:59"),
	)
);

// 初期値
if (!$requestData['subject'] && !$requestData['body']) {
	$mailDate = date("Y/m/d", strtotime($requestData['date']));
	$mailName = $infoLoginUser['name'];
	$mailBodyArray = array();
	foreach ($resTask as $key => $val) {
		$timeHour = gmdate("G", strtotime($val['end_date']) - strtotime($val['start_date']));
		$timeHour = ($timeHour == "0") ? "" : $timeHour."時間";
		$timeMinute = gmdate("i", strtotime($val['end_date']) - strtotime($val['start_date']));
		$timeMinute = ($timeMinute == "0") ? "" : $timeMinute."分";
		$timeText = $timeHour.$timeMinute;
		$atextText = ($val['atext']) ? "\n".$val['atext'] : "";
		$mailBodyArray[] = "> ".substr($val['start_date'], 11, 5)." - ".substr($val['end_date'], 11, 5)."  ".$timeText."\n[".$val['name']."] ".$val['title'].$atextText;
	}
	$requestData['subject'] = str_replace("{date}", $mailDate, str_replace("{name}", $mailName, str_replace("{body}", implode("\n\n", $mailBodyArray), $infoLoginTeam['report_subject'])));
	$requestData['body'] = str_replace("{date}", $mailDate, str_replace("{name}", $mailName, str_replace("{body}", implode("\n\n", $mailBodyArray), $infoLoginTeam['report_body'])));
}

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
<body id="base">
	
<!--wrap-->
<div class="wrapper">

<!--header-->
<header>
<?php require_once TEMPLATE_DIR.'navi.php' ?>
</header>
<!--/header-->

<div class="container">
	<div class="boxA" style="margin-top:-48px;">
		<div class="wrap">
			<div class="txt">
				<h3>日報の確認</h3>
<?php if (count($resTask) == 0) { ?>
				<div style="background:#FF0000; color:#fff; padding:10px;" class="mb20">この日はタスクが登録されていません</div>
<?php } ?>
<?php if (count($errMsg) > 0) { ?>
				<div style="color:#FF0000; padding:10px;">
<?php foreach ($errMsg as $val) { ?>
				・<?php echo h($val) ?><br>
<?php } ?>
				</div>
<?php } ?>
				<form action="report-confirm" method="post">
				<div class="report-subject">Subject: <input type="text" name="subject" value="<?php echo h($subject) ?>"></div>
				<textarea name="body" style="min-height:500px; width:70%; padding:10px; font-size:1.4rem;" class="mb10"><?php echo h($body) ?></textarea>
				<br>
				<input type="submit" value="メール内容を確認する" class="btn">
				<input type="hidden" name="date" value="<?php echo h($date) ?>">
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
