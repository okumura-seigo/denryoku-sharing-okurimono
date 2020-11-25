<?php

# パラメータ設定
$arrParam = array(
	"loginid" => "ID",
	"passwd" => "Password",
);
// 管理画面文字エンコード
if (!defined('APP_ENC')) define("APP_ENC", "UTF-8");
// 設定ファイル読み込み
require_once '../../../webapp/config/cfg.inc.php';
// ライブラリ読み込み
require_once WEB_APP."public.php";

// フォーム状態の取得
$formState = getFormState();
// データ取得
$requestData = getParam($arrParam, $formState);

$infoAdminTable = $objDB->findRowData(
	'system',
	array(
		"where" => array("system_table = 'administrator'"),
	)
);
if (is_numeric($infoAdminTable['system_id'])) {
	$adminNum = $objDB->countData('administrator', array("stop_flg = 0", "delete_flg = 0"));
}

if ($adminNum > 0) {
	$resDB = $objDB->findRowData(
		"administrator",
		array(
			"column" => array("administrator_id", "email", "passwd"),
			"where" => array("email = ?", "stop_flg = 0", "delete_flg = 0"),
			"param" => array($requestData['loginid']),
		)
	);
	if (isset($resDB['administrator_id'])) {
		if (password_verify($requestData['passwd'], $resDB['passwd'])) {
			// ログイン処理
			$_SESSION['loginCmsId'] = $resDB['administrator_id'];
			
			// 一時画像の削除
			loadLibrary('file');
			$tmpFile = viewDirFiles(UPLOAD_FILE_TEMP_DIR);
			foreach ($tmpFile as $val) {
				if ($val != ".htaccess" && date("Ymd", filemtime(UPLOAD_FILE_TEMP_DIR.$val)) < date("Ymd", strtotime("-1 day"))) {
					unlink(UPLOAD_FILE_TEMP_DIR.$val);
				}
			}
			
			// リダイレクト
			header('Location: ./');
			exit;
		} else {
			$errMsg = array("ログインIDまたはパスワードが一致しておりません");
			$errMsg = array("ID or Password do not match.");
		}
	} else {
		$errMsg = array("ログインIDまたはパスワードが一致しておりません");
		$errMsg = array("ID or Password do not match.");
	}
} else {
	if (CMS_WA_USERNAME == $requestData['loginid'] && CMS_WA_PASSWORD == $requestData['passwd']) {
		// ログイン処理
		$_SESSION['loginCmsId'] = -1;
		
		// 一時画像の削除
		loadLibrary('file');
		$tmpFile = viewDirFiles(UPLOAD_FILE_TEMP_DIR);
		foreach ($tmpFile as $val) {
			if ($val != ".htaccess" && date("Ymd", filemtime(UPLOAD_FILE_TEMP_DIR.$val)) < date("Ymd", strtotime("-1 day"))) {
				unlink(UPLOAD_FILE_TEMP_DIR.$val);
			}
		}
		
		// リダイレクト
		header('Location: ./');
		exit;
	} else {
		$errMsg = array("ログインIDまたはパスワードが一致しておりません");
		$errMsg = array("ID or Password do not match.");
	}
}

// 出力設定
extract($requestData);
require_once 'login.php';

?>
