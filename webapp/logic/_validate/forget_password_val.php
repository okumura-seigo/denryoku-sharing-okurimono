<?php

/*
// レポート送信
*/
function forgetPasswordVal(&$requestData, &$arrParam, &$safeFlg = true) {
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
			"passwd" => "alnum",
			"passwd_con" => "alnum",
		)
	);
	$errMsg = array_merge($errMsg,
		checkLength(
			$requestData,
			$arrParam,
			array(
				"passwd" => "32",
			),
			array(
				"passwd" => "4",
			)
		)
	);
	
	if ($requestData['passwd'] != $requestData['passwd_con']) $errMsg[] = "新しいパスワードが確認用と一致していません";

	// セーフフラグ
	if (count($errMsg) > 0 && $safeFlg == false) {
		header("Location: ".HOME_URL);
		exit;
	}
	
	return $errMsg;
}

?>