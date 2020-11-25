<?php

# パラメータ設定
$arrParam = array(
	"id" => "ID",
);

// ライブラリ読み込み
require_once WEB_APP."user.php";

// データ取得
$requestData = getRequestData($arrParam);

// 参加履歴
$infoProjectHistory = $objDB->findRowData(
	'project_history',
	array(
		"where" => array("user_id = ?", "project_history_id = ?", "stop_flg = 0", "delete_flg = 0"),
		"param" => array($infoLoginUser['user_id'], $requestData['id']),
	)
);
if (count($infoProjectHistory) == 0) redirectUrl(HOME_URL);
$infoProject = $objDB->findByIdData('project', $infoProjectHistory['project_id']);
if (count($infoProject) == 0) redirectUrl(HOME_URL);

// 出力設定
extract($requestData);

