<?php

$customer_title = '［電力シェアリング］ふるさとからの贈り物を受け付けました';
$customer_content ='

※このメールはシステムより自動で送信しております。

このたびは、ふるさとからの贈り物をご利用いただき、ありがとうございます。
以下の内容でふるさとからの贈り物を受け付けました。

---------

【発送内容】
お申込み名産品：【'.$contact_data['generator_name'].'】 '.$contact_data['item_name'].'
必要ポイント：'.$contact_data['item_point'].'

お届け先情報
お名前：'.$contact_data['name1'].' '.$contact_data['name2'].'
ご住所：〒'.$contact_data['post'].'  '.$contact_data['pref'].''.$contact_data['address1'].''.$contact_data['address2'].''.$contact_data['address3'].'
お電話番号：'.$contact_data['tel'].'

---------

※本メールは送信専用のアドレスで自動送信しておりますので、
ご返信いただいてもお問い合わせにお答えできません。

------------------------
電力シェアリング会員プログラム
https://○○○.○○○
';
?>