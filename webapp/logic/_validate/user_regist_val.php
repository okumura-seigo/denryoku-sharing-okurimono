<?php

/*
// レポート送信
*/
function userRegistVal(&$requestData, &$arrParam, &$safeFlg = true) {
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
			"email" => "email",
			"passwd" => "alnum",
			"name1" => "!empty",
			"name2" => "!empty",
			"furi1" => "kata",
			"furi2" => "kata",
			"pref" => "!empty",
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
	
	$objDB = loadQueryModule();
	$countNum = $objDB->findCountData(
		"user",
		array(
			"where" => array("email = ?", "delete_flg = 0"),
			"param" => array($requestData['email']),
		)
	);
	if ($countNum > 0) $errMsg[] = "このメールアドレスはすでに登録されています";
	if ($requestData['passwd'] != $requestData['passwd_con']) $errMsg[] = "パスワードが確認用と一致していません";
	if ($requestData['checkbox'] !== "1") $errMsg[] = "利用規約とプライバシーポリシーに同意してください";

	// セーフフラグ
	if (count($errMsg) > 0 && $safeFlg == false) {
		header("Location: ".HOME_URL);
		exit;
	}
	
	return $errMsg;
}

?>