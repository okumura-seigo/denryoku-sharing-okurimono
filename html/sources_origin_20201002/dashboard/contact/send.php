<?php

$cntact_data = $_POST;

foreach ($cntact_data as $key => $val) {
	$send_data[$key] = htmlspecialchars($val, ENT_QUOTES);
}

mb_language("Japanese");
mb_internal_encoding("UTF-8");

if(mb_send_mail(ADM_MAIL,$send_data['title'],$send_data['content'])){
	echo "メールを送信しました";
	header('Location:./complete');
} else {
	echo "メールの送信に失敗しました";
};
?>


