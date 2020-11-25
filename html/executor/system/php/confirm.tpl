<?php

// CMSID設定
$cmsId = "{!cms!}";

# パラメータ設定
$arrParam = array(
{!param_array!}
);
// 設定ファイル読み込み
require_once '{!path!}config/cfg.inc.php';
// ライブラリ読み込み
require_once WEB_APP."public.php";

// フォーム状態の取得
$formState = getFormState();
// データ取得
$requestData = getParam($arrParam, $formState);

// エラーチェック
$errMsg = userCmsAddVal($requestData, $arrParam, $cmsId);
if (count($errMsg) > 0) {
	require_once 'form.php';
	exit;
}

// 出力設定
extract($requestData);

?>