<?php

$contact_data = $_POST;

// メール内容読み込み
require_once WEB_APP.'mail/contact/admin_mail.php';;
require_once WEB_APP.'mail/contact/customer_mail.php';;

if (!$contact_data['title']) $errMsg[] = "お問い合わせ件名が入力されておりません";
if (!$contact_data['content']) $errMsg[] = "お問い合わせ内容が入力されておりません";
if (!$contact_data['checkbox']) $errMsg[] = "プライバシーポリシーに同意してください";
if (count($errMsg) == 0) {

	mb_language("Japanese");
	mb_internal_encoding("UTF-8");

	if(mb_send_mail(ADM_MAIL, $admin_title, $admin_content) && mb_send_mail($contact_data['email'], $customer_title, $customer_content)){
		header('Location:./complete');
	}else{
		$errMsg[] = "メールの送信に失敗しました。お手数ですが再度お試しください。";
		require_once BOOT_PHP_DIR.'mypage/contact/index.php';
		exit;
	};

}else{

	require_once BOOT_PHP_DIR.'mypage/contact/index.php';
	exit;
	
}
?>


