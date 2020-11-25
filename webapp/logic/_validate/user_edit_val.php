<?php

/*
// レポート送信
*/
function userEditVal(&$requestData, &$arrParam, &$safeFlg = true) {
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
			// "email" => "email",
			"name1" => "!empty",
			"name2" => "!empty",
			"furi1" => "kata",
			"furi2" => "kata",
			"pref" => "!empty",

		)
	);

	// $objDB = loadQueryModule();
	// $countNum = $objDB->findCountData(
	// 	"user",
	// 	array(
	// 		"where" => array("email = ?", "delete_flg = 0"),
	// 		"param" => array($requestData['email']),
	// 	)
	// );
	// if ($countNum > 0) $errMsg[] = "このメールアドレスはすでに登録されています";
	// if (!checkData($user_datas['email'], 'email')) $errMsg[] = "メールアドレスが正しく入力されておりません";
	// if (!$user_datas['sex']) $errMsg[] = "性別が選択されておりません";
	// if (!checkdate($user_datas['birthday_m'], $user_datas['birthday_d'], $user_datas['birthday_y'])) $errMsg[] = "生年月日が正しく入力されておりません";
	// if (!$user_datas['post']) $errMsg[] = "郵便番号が正しく入力されておりません";
	// if (!$user_datas['address1']) $errMsg[] = "市区町村が正しく入力されておりません";
	// if (!$user_datas['address2']) $errMsg[] = "町名・番地が正しく入力されておりません";
	if ( $requestData["tel1"]){
		if (!is_numeric($requestData["tel1"])) $errMsg[] = "電話番号1が正しく入力されておりません";
	}
	if ( $requestData['tel2']){
		if (!is_numeric($requestData['tel2'])) $errMsg[] = "電話番号2が正しく入力されておりません";
	}
	// if (!$user_datas['works']) $errMsg[] = "職業が選択されておりません";
	// if (!$user_datas['works_type']) $errMsg[] = "業種が選択されておりません";
	// if (!$user_datas['final_education']) $errMsg[] = "最終学歴が選択されておりません";
	// if (!$user_datas['spouse']) $errMsg[] = "配偶者が選択されておりません";
	// if (!$user_datas['childeren']) $errMsg[] = "子どもが選択されておりません";
	// if (!$user_datas['number_of_people_living_together']) $errMsg[] = "同居人数(ご本人除く)が正しく入力されておりません";
	// if (!$user_datas['private_car']) $errMsg[] = "自家用車が選択されておりません";
	// if (!$user_datas['car_license']) $errMsg[] = "自動車運転免許が選択されておりません";
	// if (!$user_datas['householde_income']) $errMsg[] = "世帯年収(税込)が選択されておりません";
	// if (!$user_datas['housing_form']) $errMsg[] = "住居形態が選択されておりません";


	// セーフフラグ
	if (count($errMsg) > 0 && $safeFlg == false) {
		header("Location: ".HOME_URL);
		exit;
	}
	
	return $errMsg;
}

?>