<?php

# パラメータ設定
$arrParam = array(
	"date" => "日付",
	"project" => "プロジェクトID",
);
// ライブラリ読み込み
require_once WEB_APP."user.php";
require_once WEB_APP."mypage.php";

// データ取得
$requestData = getRequestData($arrParam);
if ($requestData['date'] == "") $requestData['date'] = date("Y/m/d");

// ポイント明細
$whereArray = array("user_id = ?", "insert_datetime >= ?", "insert_datetime <= ?");
$paramArray = array($infoLoginUser['user_id'], $requestData['date']." 00:00:00", $requestData['date']." 23:59:59");
if (is_numeric($requestData['project'])) {
	$infoProject = $objDB->findByIdData('project', $requestData['project']);
	$idsArray = array();
	foreach (explode(",", $infoProject['generator_ids']) as $val) {
		if (is_numeric($val)) $idsArray[] = $val;
	}
	$whereArray[] = "generator_id in (0".str_repeat(", ?", count($idsArray)).")";
	$paramArray = array_merge($paramArray, $idsArray);
}
$resTrade = $objDB->findData(
	'trade',
	array(
		"where" => $whereArray,
		"order" => array("insert_datetime"),
		"param" => $paramArray,
	)
);

$resTradeDetail = array();
/*
$key = 0;
for ($i = 0;$i <= 23;$i++) {
	for ($j = 0;$j < 60;$j+=30) {
		$total = 0;
			if ($resTrade[$key]['insert_datetime'] < date('Y-m-d', strtotime($requestData['date']))." ".digitNum($i, 2).":".digitNum($j + 30, 2).":00") {
				$index = date('Y-m-d', strtotime($requestData['date']))." ".digitNum($i, 2).":".digitNum($j, 2).":00";
				$total+= $resTradeDetail[$index]['execution_amount'];
				if (!isset($resTradeDetail[$index])) {
					$resTradeDetail[$index] = $resTrade[$key];
				} else {
					$resTradeDetail[$index]['execution_amount']+= $resTrade[$key]['execution_amount'];
				}
				$resTradeDetail[$index]['execution_amount_total'] = $total;			
				$key++;
			} else {
				break;
			}
	}
}
*/
$total = 0;
foreach ($resTrade as $key => $val) {
	$index = date('Y-m-d H:', strtotime($val['insert_datetime']));
	$minute = (substr($val['insert_datetime'], 14, 2) < 30) ? "00-29" : "30-59";
	$total+= $val['execution_amount'];
	if (!isset($resTradeDetail[$index])) {
		$resTradeDetail[$index.$minute] = $val;
	} else {
		$resTradeDetail[$index.$minute]['execution_amount']+= $resTrade[$key]['execution_amount'];
	}
	$resTradeDetail[$index.$minute]['execution_amount_total'] = $total;
}

// 出力設定
extract($requestData);
