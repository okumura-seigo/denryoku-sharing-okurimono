<?php

$admin_title = '［電力シェアリング］お問い合せが届きました。';
$admin_content ='

※このメールはシステムより自動で送信しております。

以下の内容でお問い合わせが申し込まれました。

内容を確認後、担当者の方は回答をお願います。

---------

【お名前】
'.$contact_data['name'].'

【メールアドレス】
'.$contact_data['email'].'

【問い合わせ件名】
'.$contact_data['title'].'

【問い合わせ内容】
'.$contact_data['content'].'
---------

------------------------
電力シェアリング会員プログラム
https://○○○.○○○
';
?>