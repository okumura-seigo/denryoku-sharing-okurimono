<?php

// 設定ファイルの呼び出し
require_once dirname(__FILE__)."../webapp/config/cfg.inc.php";
// 共通ユーザー定義関数の呼び出し
require_once WEB_APP."public.php";

// 一時画像の削除
$tmpFile = viewDirFiles(UPLOAD_FILE_TEMP_DIR);
foreach ($tmpFile as $val) {
	if ($val != ".htaccess" && date("Ymd", filemtime(UPLOAD_FILE_TEMP_DIR.$val)) < date("Ymd", strtotime("-1 day"))) {
		unlink(UPLOAD_FILE_TEMP_DIR.$val);
	}
}

?>