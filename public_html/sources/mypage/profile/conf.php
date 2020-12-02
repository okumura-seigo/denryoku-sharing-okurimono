<?php

# パラメータ設定
$arrParam = array(
  "email" => "メールアドレス",
  "name1" => "お名前(姓)",
  "name2" => "お名前(名)",
  "furi1" => "フリガナ(セイ)",
  "furi2" => "フリガナ(メイ)",
  "sex" => "性別",
  "birthday_y" => "生年",
  "birthday_m" => "生月",
  "birthday_d" => "生日",
  "post" => "郵便番号",
  "pref" => "都道府県",
  "address1" => "市区町村",
  "address2" => "町名・番地",
  "address3" => "建物名",
  "tel1" => "電話番号1",
  "tel2" => "電話番号2",
  "works" => "職業",
  "works_type" => "業種",
  "final_education" => "最終学歴",
  "spouse" => "配偶者",
  "childeren" => "子ども",
  "number_of_people_living_together" => "同居人数(ご本人除く)",
  "private_car" => "自家用車",
  "car_license" => "自動車運転免許",
  "householde_income" => "世帯年収（税込）",
  "housing_form" => "住居形態",
  "image" => "プロフィール画像",
);

// ライブラリ読み込み
require_once WEB_APP."user.php";
require_once WEB_APP."mypage.php";

// データ取得
$requestData = getRequestData($arrParam);
$user_datas = $requestData;

// エラーチェック
$errMsg = actionValidate("user_edit_val", $requestData, $arrParam);
if (count($errMsg) > 0) {
	require_once BOOT_PHP_DIR.'mypage/profile/index.php';
	require_once BOOT_HTML_DIR.'mypage/profile/index.html';
	exit;
}

// 画像アップロード
if ($_FILES['image']['tmp_name']) {
	function getExpArray($filename) {
		$exp = explode(".", $filename);
		$last = $exp[(count($exp) - 1)];
		unset($exp[(count($exp) - 1)]);
		return array(implode(".", $exp), $last);
	}

	$fileName = getExpArray($_FILES['image']['name']);
	$requestData['image'] = digitNum($infoLoginUser['user_id'], 6).md5(serialize($requestData)).".".$fileName[1];
	uploadImageMagick($_FILES['image']['tmp_name'], UPLOAD_FILE_TEMP_DIR.$requestData['image'], IMAGE_HEIGHT, IMAGE_WIDTH);
}

// 出力設定
extract($requestData);
