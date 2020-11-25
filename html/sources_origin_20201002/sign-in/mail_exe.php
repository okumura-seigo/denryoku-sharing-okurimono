<?php

# パラメータ設定
$arrParam = array(
	"loginid" => "ログインID"
);

// ライブラリ読み込み
require_once WEB_APP."public.php";

// データ取得
$requestData = getRequestData($arrParam, 'POST');

// メールアドレスチェック
if (checkData($requestData['loginid'], "email")) {
	$infoData = $objDB->findRowData(
		"user",
		array(
			"where" => array("email = ?", "stop_flg = 0", "delete_flg = 0"),
			"param" => array($requestData['loginid']),
		)
	);
	// メール送信
	if(count($infoData) > 0){
		if(mb_send_mail($requestData['loginid'],"パスワード再設定URLのお知らせ","https://e-kuro.com/testplay/denryoku-sharing/html/sign-in/password")){
			echo "メールを送信しました";
			header('Location:./mail-complete');
			exit();
		}	
	}	

}
$errMsg = array("入力いただいたメールアドレスは登録されていません");

//出力設定
require_once 'mail.php';
exit;

?>