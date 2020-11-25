<?php

/*
// レポート送信
*/
function sendReportVal(&$requestData, &$arrParam, &$safeFlg = true) {
	// 文字サポート
	$requestData = applyTrim(
		$requestData,
		array(
			"subject",
		)
	);
	$requestData = applyKana(
		$requestData,
		array(
		)
	);

	// エラーチェック
	$errMsg = checkDataType(
		$requestData,
		$arrParam,
		array(
			"subject" => "!empty",
			"body" => "!empty",
		)
	);
	$errMsg = array_merge($errMsg,
		checkLength(
			$requestData,
			$arrParam,
			array(
				"subject" => "100",
				"body" => "4000",
			),
			array(
			)
		)
	);
	
	// セーフフラグ
	if (count($errMsg) > 0 && $safeFlg == false) {
		header("Location: ".HOME_URL);
		exit;
	}
	
	return $errMsg;
}

?>