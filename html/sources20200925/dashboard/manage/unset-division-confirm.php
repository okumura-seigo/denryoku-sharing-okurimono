<?php

# パラメータ設定
$arrParam = array(
	"division_id" => "部門ID",
	"division_name" => "新しい部門",
);
// ライブラリ読み込み
require_once WEB_APP."manager.php";

// データ取得
$requestData = getRequestData($arrParam);

// 未設定プロジェクト
$resProject = $objDB->findData(
	"project",
	array(
		"where" => array("team_id = ?", "division_id = 0", "stop_flg = 0", "delete_flg = 0"),
		"param" => array($infoLoginUser['team_id']),
		"order" => array("new_task_date"),
	)
);

// 部門
$resDivision = $objDB->findData(
	"division",
	array(
		"where" => array("team_id = ?", "stop_flg = 0", "delete_flg = 0"),
		"param" => array($infoLoginUser['team_id']),
		"order" => array("division_id"),
	)
);
$divisionListArray = array();
foreach ($resDivision as $key => $val) $divisionListArray[$val['division_id']] = $val;

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
			<h3>未設定プロジェクト一覧</h3>
			<form action="unset-division-confirm" method="post">
			<table class="graph">
<?php foreach ($resProject as $key => $val) { ?>
			<tr>
			<th><?php echo h($val['name']) ?></th>
			<td>
<?php if ($division_id[$val['project_id']]) { ?>
				<?php echo h($divisionListArray[$division_id[$val['project_id']]]['name']) ?>
<?php } elseif ($division_name[$val['project_id']]) { ?>
				<?php echo h($division_name[$val['project_id']]) ?>
<?php } else { ?>
				未設定
<?php } ?>
			</td>
			</tr>
<?php } ?>
			</table>
			<input type="submit" value="設定する">
			</form>
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
