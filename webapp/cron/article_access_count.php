<?php

// 設定ファイルの呼び出し
require_once dirname(__FILE__)."/../../webapp/config/cfg.inc.php";
// 共通ユーザー定義関数の呼び出し
require_once WEB_APP."public.php";

// アクセスカウンタ更新
$resNews = $objDB->findData('news', array('column' => array('news_id', 'access_count', 'access_log')));
foreach ($resNews as $key => $val) {
	$accessCount = 0;
	$accessLog = array();
	if ($val['access_log']) {
		$val['access_log'] = unserialize($val['access_log']);
		if (is_array($val['access_log'])) {
			foreach ($val['access_log'] as $cnt) {
				$accessCount+= $cnt;
			}
			$accessLog = $val['access_log'];
			$accessLog[date("w")] = 0;
		}
	}
	$objDB->query("Update news Set access_count = ?, access_log = ? Where news_id = ?", array($accessCount, serialize($accessLog), $val['news_id']), null, MDB2_PREPARE_MANIP);
}

