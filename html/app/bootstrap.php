<?php

// BOOTSTRAP設定
require 'bootstrap-config.inc.php';
// コード取得
require_once WEB_APP_CONFIG_DIR.'code.inc.php';



/********************************************************************/

if ($_GET[BOOT_ORIGIN_PARAM]) {
	$bootPhpFile = $_GET[BOOT_ORIGIN_PARAM];
	$bootHtmlFile = $_GET[BOOT_ORIGIN_PARAM];
} else {
	$bootPhpFile = (BOOT_DELETE_PATH == '') ? $_SERVER[BOOT_SERVER_PARAM] : str_replace(BOOT_DELETE_PATH, '', $_SERVER[BOOT_SERVER_PARAM]);
	$bootHtmlFile = (BOOT_DELETE_PATH == '') ? $_SERVER[BOOT_SERVER_PARAM] : str_replace(BOOT_DELETE_PATH, '', $_SERVER[BOOT_SERVER_PARAM]);
}

if (substr($bootPhpFile, -1) == "/") $bootPhpFile.= "index";
if (substr($bootHtmlFile, -1) == "/") $bootHtmlFile.= "index";

define( 'BOOT_PHP_FILE', substr($bootPhpFile, 1));
define( 'BOOT_HTML_FILE', substr($bootHtmlFile, 1));

// 静的コンテンツ
if (strpos($bootHtmlFile, '.') !== false && substr($bootHtmlFile, (strlen($bootHtmlFile) - strlen(BOOT_PHP_EXTENSION)), strlen(BOOT_PHP_EXTENSION)) != BOOT_PHP_EXTENSION) {
	$filePathInfo = pathinfo($bootHtmlFile);
	if (array_search($filePathInfo['extension'], $FILE_EXTENSION_CODE) !== false) {
		$mime = array_search($filePathInfo['extension'], $FILE_EXTENSION_CODE);
		header('Content-Type: '.$mime);
		require_once BOOT_HTML_DIR.BOOT_HTML_FILE;
		exit;
	}
	header("HTTP/1.0 404 Not Found");
	require 'page/404.html';
	exit;
}

if (file_exists(BOOT_PHP_DIR.BOOT_PHP_FILE.BOOT_PHP_EXTENSION)) {
	# PHPロジックあり
	require_once WEB_APP_CONFIG_DIR."cfg.inc.php";
	require_once BOOT_PHP_DIR.BOOT_PHP_FILE.BOOT_PHP_EXTENSION;
	if (file_exists(BOOT_HTML_DIR.BOOT_HTML_FILE.BOOT_HTML_EXTENSION)) require_once BOOT_HTML_DIR.BOOT_HTML_FILE.BOOT_HTML_EXTENSION;
	exit;
} elseif (file_exists(BOOT_HTML_DIR.BOOT_HTML_FILE.BOOT_HTML_EXTENSION)) {
	# HTMLビューあり
	require_once WEB_APP_CONFIG_DIR."cfg.inc.php";
	require_once WEB_APP."public.php";
	require_once BOOT_HTML_DIR.BOOT_HTML_FILE.BOOT_HTML_EXTENSION;
	exit;
}

header("HTTP/1.0 404 Not Found");
require 'page/404.html';
