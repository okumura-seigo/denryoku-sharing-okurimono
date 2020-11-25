<?php

// ライブラリ読み込み
require_once WEB_APP."user.php";
require_once WEB_APP."mypage.php";

$EditProfile = new EditProfile();
$user_datas = $EditProfile->getProfile($edit_datas);
$name =  $user_datas['name1']." ".$user_datas['name2']."(".$user_datas['furi1']." ".$user_datas['furi2'].")";
$email = $user_datas['email'];

if (count($_POST) > 0) {
  $contact_data = $_POST;
}

