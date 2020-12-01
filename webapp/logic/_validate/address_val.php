<?php

/*
// レポート送信
*/
function addressVal(&$requestData, &$arrParam, &$safeFlg = true) {
	// 文字サポート
	$requestData = applyTrim(
		$requestData,
		array(
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
			"address" => "!empty",
		)
	);
	$errMsg = array_merge($errMsg,
		checkLength(
			$requestData,
			$arrParam,
			array(
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