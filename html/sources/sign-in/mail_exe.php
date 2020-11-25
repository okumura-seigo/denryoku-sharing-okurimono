<?php
define("DEBUG_MODE", 1);
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
		$name = $infoData['name1']." ".$infoData['name2']." ";
		$token = md5(serialize($_SERVER).date("YmdHis"));
		$userId = $infoData['user_id'];

		$objDB->insertData(
			'user_password',
			array(
				"user_id" => $userId,
				"token" => $token,
			)
		);

		require_once WEB_APP."mail/password_setting.php";
		if(mb_send_mail($requestData['loginid'], $mail_title, $mail_content)){
			header('Location:./mail-complete');
			exit;
		}	
	}	
}
$errMsg = array("入力いただいたメールアドレスは登録されていません");

//出力設定
require_once 'mail.php';
exit;

?>