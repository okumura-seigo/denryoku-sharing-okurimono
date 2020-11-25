<?php

# パラメータ設定
$arrParam = array(
	"loginid" => "ログインID",
	"passwd" => "パスワード",
	"save" => "ログイン保持",
	"action" => "アクション",
);

// ライブラリ読み込み
require_once WEB_APP."public.php";

// データ取得
$requestData = getRequestData($arrParam, 'POST');

// ログイン
if ($requestData['action'] == "login") {
	if (checkData($requestData['loginid'], "email") && checkData($requestData['passwd'], "alnum")) {
		$infoData = $objDB->findRowData(
			"user",
			array(
				"where" => array("email = ?", "stop_flg = 0", "delete_flg = 0"),
				"param" => array($requestData['loginid'], $requestData['passwd']),
			)
		);
		if (password_verify($requestData['passwd'], $infoData['passwd'])) {
			if ($requestData['save'] == "1") {
				setcookie("ul", encryptData(sha1($infoData['user_id'])."_".$infoData['user_id']."_".md5($infoData['insert_datetime'])."_".sha1(date("YmdHis"))), time() + 3600 * 24 * 365, "/", DMN_NAME);
			}
			$_SESSION['loginUserId'] = $infoData['user_id'];
			redirectUrl(HOME_URL."mypage/");
		}
	}
}
$errMsg = array("ログインIDもしくはメールアドレスが正しくありません");

// 出力設定
require_once 'login.php';
exit;

?>