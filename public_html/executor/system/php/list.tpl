<?php

// CMSID設定
$cmsId = "{!cms!}";

/* 設定ここから *******************************************/
# 検索設定
$searchParam = array(
	{!sort!}
);
/* 設定ここまで *******************************************/

# パラメータ設定
$arrParam = array(
	"q" => "クエリ",
	"o" => "並び替え",
	"st" => "並び順",
	"lim" => "ページャー",
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
$requestData = getParam(array_merge($arrParam, $searchParam), $formState);
if (!checkData($requestData['lim'], "natural")) $requestData['lim'] = 0;

// DB取得
list($resData, $maxRows, $pager) = getCmsList($cmsId, $requestData, $searchParam);

// 出力設定
extract($requestData);

?>