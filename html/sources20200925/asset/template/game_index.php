<?php

// �Q�[�����
$expGame = explode('/', BOOT_HTML_FILE);
$infoGame = $objDB->findRowData(
	'game',
	array(
		"where" => array(
			"code = ?",
		),
		"param" => array(
			$expGame[0],
		),
	)
);

