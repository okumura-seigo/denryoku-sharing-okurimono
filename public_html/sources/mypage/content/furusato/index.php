<?php

// ���C�u�����ǂݍ���
require_once WEB_APP."user.php";

// ���Y�i�ꗗ
$resItem = $objDB->findData(
	'item',
	array(
		"column" => array("item.*", "generator.name as generator_name", "generator.image1 as generator_image1"),
		"where" => array("item.stop_flg = 0", "item.delete_flg = 0", "generator.delete_flg = 0"),
		"join" => array("Inner Join generator On item.generator_id = generator.generator_id"),
		"order" => array("item.update_datetime Desc"),
	)
);

// ���d�҈ꗗ
$resGenerator = $objDB->findData(
	'generator',
	array(
		"where" => array("delete_flg = 0"),
		"order" => array("update_datetime Desc"),
		"limit" => 3,
	)
);
