<?php

function systemAdd01Val(&$requestData, &$arrParam, &$safeFlg = true) {
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
			"title" => "!empty",
		)
	);
	$errMsg = array_merge($errMsg,
		checkLength(
			$requestData,
			$arrParam,
			array(
				"title" => "100",
			),
			array(
			)
		)
	);
	
	$total = 0;
	foreach ($requestData['info'] as $key => $val) {
		if (!is_numeric($val)) {
			$errMsg[] = mb_convert_encoding("項目情報が正しく入力されておりません", APP_ENC, "UTF-8");
			continue;
		}
		$total+= $val;
	}
	if (count($errMsg) == 0) {
		if ($total == 0) $errMsg[] = mb_convert_encoding("項目情報が正しく入力されておりません", APP_ENC, "UTF-8");
	}
	
	// セーフフラグ
	if (count($errMsg) > 0 && $safeFlg == false) {
		header("Location: ".HOME_URL);
		exit;
	}
	
	return $errMsg;
}

?>