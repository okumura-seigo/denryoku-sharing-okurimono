<?php

/*
// メール送信(送信先メールアドレス, タイトル, 本文, 送信元, 送信者, [その他ヘッダー]);
*/
function sendMail($to, $subject, $body, $from, $fromname, $headerData = "") {
	require_once 'Mail.php';
	require_once 'Mail/mime.php';

	if (APP_ENC == "UTF-8") {
		mb_language("uni");
	} else {
		mb_language("Japanese");
	}
	if (APP_ENC == "SJIS" || APP_ENC == "SJIS-win") mb_internal_encoding("Shift_JIS");
	if (APP_ENC == "EUC" || APP_ENC == "EUC-win") mb_internal_encoding("EUC-JP");
	if (APP_ENC == "UTF-8") mb_internal_encoding("UTF-8");

	$params = array(
		'sendmail_args' => 'f',
	);
	$mail = Mail::factory("mail", $params);

	$body = mb_convert_encoding($body, MAIL_ENC, APP_ENC);

	$mime = new Mail_Mime("\n");
	$mime->setTxtBody($body);

	$body_encode = array(
	  "head_charset" => MAIL_ENC,
	  "text_charset" => MAIL_ENC,
	);

	$body = $mime->get($body_encode);

	$headers = array(
	  "To" => $to,
	  "From" => mb_encode_mimeheader(mb_convert_encoding($fromname, MAIL_ENC, APP_ENC))."<".mb_convert_encoding($from, MAIL_ENC, APP_ENC).">",
	  "Subject" => mb_encode_mimeheader(mb_convert_encoding($subject, MAIL_ENC, APP_ENC)),
	  "Return-Path" => RETURN_PATH,
	);
	if (is_array($headerData)) $headers = array_merge($headers, $headerData);
	$header = $mime->headers($headers);

	$res = $mail->send($to, $header, $body);

	return $res;
}

/*
// メールフォーマット
*/
function optMailformat($content, $array) {
	$content = str_replace('{HOME_URL}', HOME_URL, $content);
	$content = str_replace('{SITE_NAME}', SITE_NAME, $content);
	if (is_array($array)) foreach ($array as $key => $val) $content = str_replace('{$'.$key.'}', $val, $content);
	return $content;
}

?>