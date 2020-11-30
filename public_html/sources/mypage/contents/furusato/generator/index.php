<?php

// ライブラリ読み込み
require_once WEB_APP."user.php";

// 発電者一覧
$resGenerator = $objDB->findData(
	'generator',
	array(
		"where" => array("delete_flg = 0"),
		"order" => array("update_datetime Desc"),
	)
);
