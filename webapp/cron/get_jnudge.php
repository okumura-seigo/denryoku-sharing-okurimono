<?php

# API接続先
define('API_JNUDGE_URI', 'https://jnudge.org/api/v1');
define('API_JNUDGE_KEY', 'ha18PVQrOAIcOa66U9aoD9obS5eOkdgd');

// 設定ファイルの呼び出し
require_once dirname(__FILE__)."/../../webapp/config/cfg.inc.php";
// 共通ユーザー定義関数の呼び出し
require_once WEB_APP."public.php";

// データ取得
$resUser = $objDB->findData(
	'user',
	array(
		"column" => array("user_id", "buyer_user_id"),
		"where" => array("buyer_user_id != ''", "stop_flg = 0", "delete_flg = 0"),
	)
);
$resGenerator = $objDB->findData(
	'generator',
	array(
		"column" => array("generator_id", "seller_user_id"),
		"where" => array("seller_user_id != ''", "delete_flg = 0"),
	)
);

// API接続
foreach ($resUser as $user) {
	foreach ($resGenerator as $generator) {
		$url = API_JNUDGE_URI;
		$rHeader = ["X-API-KEY: ".API_JNUDGE_KEY];
		$array = '{"jsonrpc": "2.0", "id": 1, "method": "get_execution_list", "params":{"buyer_user_id":'.$user['buyer_user_id'].', "seller_user_id": '.$generator['seller_user_id'].'}}'; //接続時に渡すデータの指定

		$conn = curl_init();

		curl_setopt($conn, CURLOPT_URL, $url);
		curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($conn, CURLOPT_HTTPHEADER, $rHeader);
		curl_setopt($conn, CURLOPT_POST, true);
		curl_setopt($conn, CURLOPT_POSTFIELDS, $array);

		$res = curl_exec($conn);
		curl_close($conn); 

		$tradeArray = json_decode($res, true);

		// 取得処理
		foreach ($tradeArray['result']['executions'] as $execution) {
			if ($execution['status'] == "4") {
				$infoData = $objDB->findData('trade', array("where" => array("execution_id = ?"), "param" => array($execution['execution_id'])));
				if (count($infoData) == 0) {
					$execution['user_id'] = $user['user_id'];
					$execution['generator_id'] = $generator['generator_id'];
					$objDB->insertData('trade', $execution);
				}
			}
		}
	}
}


