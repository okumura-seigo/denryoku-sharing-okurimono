<?php

define("MONTHLY_WORK_DAYS", 20);
# パラメータ設定
$arrParam = array(
	"start_date" => "開始日",
	"end_date" => "終了日",
	"search_type" => "種別",
	"user" => "スタッフ",
);

// ライブラリ読み込み
require_once WEB_APP."manager.php";

// データ取得
$requestData = getRequestData($arrParam);
if (!checkData($requestData['start_date'], "date")) $requestData['start_date'] = date("Y/m/01");
if (!checkData($requestData['end_date'], "date")) $requestData['end_date'] = date("Y/m/t");
if (!is_numeric($requestData['search_type'])) $requestData['search_type'] = "0";

// タスク取得
$columnArray = array("sum(cost_value) as total_cost", "user.monthly_cost");
$joinArray = array("Inner Join user On task.user_id = user.user_id");
$whereArray = array("task.team_id = ?", "task.start_date >= ?", "task.end_date <= ?", "task.stop_flg = 0", "task.delete_flg = 0");
$groupArray = array("user.monthly_cost");
$orderArray = array();
if ($requestData['search_type'] == "0") {
	$columnArray[] = "task.project_id as key_id";
	$columnArray[] = "project.name";
	$joinArray[] = "Inner Join project On task.project_id = project.project_id";
	$groupArray[] = "task.project_id";
	$groupArray[] = "project.name";
	$orderArray[] = "sum(cost_value) Desc";
} elseif ($requestData['search_type'] == "1") {
	$columnArray[] = "task.division_id as key_id";
	$columnArray[] = "division.name";
	$joinArray[] = "Inner Join division On task.division_id = division.division_id";
	$groupArray[] = "task.division_id";
	$groupArray[] = "division.name";
	$orderArray[] = "sum(cost_value) Desc";
} elseif ($requestData['search_type'] == "2") {
	$columnArray[] = "task.task_id as key_id";
	$columnArray[] = "concat(project.name, ' - ', task.title) as name";
	$joinArray[] = "Inner Join project On task.project_id = project.project_id";
	$groupArray[] = "task.task_id";
	$groupArray[] = "project.name";
	$groupArray[] = "task.title";
	$orderArray[] = "task.start_date";
}
$paramArray = array($infoLoginUser['team_id'], $requestData['start_date']." 00:00:00", $requestData['end_date']." 23:59:59");
if (is_array($requestData['user']) && count($requestData['user']) > 0) {
	$whereArray[] = "task.user_id in (".substr(str_repeat('?,', count($requestData['user'])), 0, count($requestData['user']) * 2 - 1).")";
	$paramArray = array_merge($paramArray, $requestData['user']);
}

$tmpData = $objDB->findData(
	"task",
	array(
		"column" => $columnArray,
		"join" => $joinArray,
		"where" => $whereArray,
		"group" => $groupArray,
		"order" => $orderArray,
		"param" => $paramArray,
	)
);

$resData = array();
$beforeKeyId = "";
foreach ($tmpData as $key => $val) {
	if ($resData[$val['key_id']]) {
		$resData[$val['key_id']]['total_cost']+= $val['total_cost'];
		$resData[$val['key_id']]['total_money']+= floor($val['total_cost'] * $val['monthly_cost'] / (MONTHLY_WORK_DAYS * 8 * 60));
	} else {
		$resData[$val['key_id']] = $val;
		$resData[$val['key_id']]['total_money'] = floor($val['total_cost'] * $val['monthly_cost'] / (MONTHLY_WORK_DAYS * 8 * 60));
	}
	$beforeKeyId = $val['key_id'];
}
$totalTimeValue = 0;
$totalMoneyValue = 0;
$resData = array_merge($resData);
foreach ($resData as $val) {
	$totalTimeValue+= $val['total_cost'];
	$totalMoneyValue+= $val['total_money'];
}

// スタッフ一覧
$resStaff = $objDB->findData(
	"user",
	array(
		"where" => array("team_id = ?", "delete_flg = 0", "stop_flg = 0"),
		"param" => array($infoLoginUser['team_id']),
	)
);

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
<link rel="stylesheet" href="../../css/style.css">
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

<div class="boxB">
	<div class="container">
		<div class="wrap">
			<h3>工数確認 - プロジェクト別</h3>
			<form action="workload" method="post">
			<div style="margin-bottom:10px;">
			<select name="search_type">
				<option value="0" <?php if ($search_type == "0") echo "selected"; ?>>プロジェクト別</option>
				<option value="1" <?php if ($search_type == "1") echo "selected"; ?>>事業別</option>
				<option value="2" <?php if ($search_type == "2") echo "selected"; ?>>タスク別</option>
			</select>
			</div>
			<input type="text" name="start_date" value="<?php echo h($start_date) ?>" class="input-period"> ～ <input type="text" name="end_date" value="<?php echo h($end_date) ?>" class="input-period">
			<div style="font-size:12px;">
<?php foreach ($resStaff as $key => $val) { ?>
			<label><input type="checkbox" name="user[]" value="<?php echo h($val['user_id']) ?>" <?php if (inArray($val['user_id'], $user)) echo "checked"; ?>><?php echo h($val['name']) ?></label>
<?php } ?>
			</div>
			<input type="submit" value="設定する" class="btn-period">
			</form>
			<canvas id="myChart"></canvas>
			<div class="txt">
			<table class="graph">
<?php foreach ($resData as $key => $val) { ?>
			<tr>
			<th><?php echo h($val['name']) ?></th>
			<td style="text-align:right"><?php echo h(floor($val['total_cost'] / 60)) ?>時間<?php echo h(digitNum($val['total_cost'] % 60, 2)) ?>分</td>
			<td style="text-align:right"><?php echo h(number_format($val['total_money'])) ?>円</td>
			</tr>
<?php } ?>
			<tr>
			<th>合計</th>
			<td style="text-align:right"><?php echo h(floor($totalTimeValue / 60)) ?>時間<?php echo h(digitNum($totalTimeValue % 60, 2)) ?>分</td>
			<td style="text-align:right"><?php echo h(number_format($totalMoneyValue)) ?>円</td>
			</tr>
			</table>
			</div>
		</div>
	</div>
</div>

<footer>
<?php require_once TEMPLATE_DIR.'footer.php' ?>
</footer>

<!--js-->
<script src="js/scripts.js"></script>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: [<?php foreach ($resData as $key => $val) { ?>'<?php echo h($val['name']) ?>'<?php if (count($resData) > ($key + 1)) { ?>,<?php } ?><?php } ?>],
    datasets: [{
      label: '工数(分)',
      data: [<?php foreach ($resData as $key => $val) { ?>'<?php echo h($val['total_cost']) ?>'<?php if (count($resData) > ($key + 1)) { ?>,<?php } ?><?php } ?>],
      backgroundColor: "rgba(247,193,71,0.8)"
    }]
  }
});
</script>

<!--/js-->
	
</div>
<!--/wrap-->

</body>
</html>
