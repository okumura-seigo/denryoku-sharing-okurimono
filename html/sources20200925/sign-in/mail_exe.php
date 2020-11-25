<?php

# パラメータ設定
$arrParam = array(
	"loginid" => "ログインID",
	// "passwd" => "パスワード",
	// "save" => "ログイン保持",
	// "action" => "アクション",
);

// ライブラリ読み込み
require_once WEB_APP."public.php";

// データ取得
$requestData = getRequestData($arrParam, 'POST');

// メールアドレスチェック
// if ($requestData['action'] == "login") {
	// if (checkData($requestData['loginid'], "email") && checkData($requestData['passwd'], "alnum")) {
	if (checkData($requestData['loginid'], "email") {
		$infoData = $objDB->findRowData(
			"user",
			array(
				"where" => array("email = ?", "stop_flg = 0", "delete_flg = 0"),
				// "param" => array($requestData['loginid'], $requestData['passwd']),
				"param" => array($requestData['loginid']),
			)
		);
		// メール送信
		// if(mb_send_mail('',$send_data['title'],$send_data['content'])){
		if(mb_send_mail('kaibara412372@gmail.com',$send_data['title'],$send_data['content'])){
			echo "メールを送信しました";
			header('Location:./mail-complete');
			exit();
		} else {
			echo "メールの送信に失敗しました";
			$errMsg = array("メールアドレスが登録されていません");
			header('Location:./mail');
			exit();
		};
	}
// }

?>