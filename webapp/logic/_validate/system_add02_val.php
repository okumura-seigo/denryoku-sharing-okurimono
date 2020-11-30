<?php

function systemAdd02Val(&$requestData, &$arrParam, &$safeFlg = true) {
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
	foreach ($requestData['param_info'] as $key => $val) $requestData['param_info'][$key] = trim($val);
	foreach ($requestData['param_sort'] as $key => $val) if (!is_numeric($val)) $requestData['param_sort'][$key] = $key;
	
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
	foreach ($requestData['param_name'] as $key => $val) {
		if (!$val) {
			$errMsg[] = mb_convert_encoding("詳細項目情報の名前が正しく入力されておりません", APP_ENC, "UTF-8");
			continue;
		}
	}
	
	// セーフフラグ
	if (count($errMsg) > 0 && $safeFlg == false) {
		header("Location: ".HOME_URL);
		exit;
	}
	
	return $errMsg;
}

?>