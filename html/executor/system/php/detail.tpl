<?php

// CMSID設定
$cmsId = "{!cms!}";

# パラメータ設定
$arrParam = array(
	"id" => "ID",
);
// 設定ファイル読み込み
require_once '{!path!}config/cfg.inc.php';
// ライブラリ読み込み
require_once WEB_APP."public.php";

// DB
$infoSystem = findByIdSystem($cmsId);
if (!is_numeric($infoSystem['system_id'])) exit;

// フォーム状態の取得
$formState = getFormState();
// データ取得
$requestData = getParam($arrParam, $formState);
if (!is_numeric($requestData['id'])) exit;

// DB取得
$infoData = findByIdData($cmsId, $requestData['id']);
if (!is_numeric($infoData['data_id'])) exit;

// 出力設定
extract($requestData);

?>