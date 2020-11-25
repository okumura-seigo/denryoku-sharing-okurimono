<?php

/*
// ドキュメントルート(*)
*/
define( 'HOME_DIR', '/var/www/e-kuro.com/html/testplay/denryoku-sharing/html/' );

/*
// ウェブアプリケーションディレクトリ(*)
*/
define( 'WEB_APP', '/var/www/e-kuro.com/html/testplay/denryoku-sharing/webapp/');

/*
// ウェブアプリケーションCONFIGディレクトリ(*)
*/
define( 'WEB_APP_CONFIG_DIR', '/var/www/e-kuro.com/html/testplay/denryoku-sharing/webapp/config/');

/*
// ブートパス認識パラメータ
*/
define( 'BOOT_SERVER_PARAM', 'REDIRECT_URL' );

/*
// PHPディレクトリ(*)
*/
if ($_SERVER['REMOTE_ADDR'] == "160.16.69.206") {
define( 'BOOT_PHP_DIR', HOME_DIR . 'sources_test/');
} else {
define( 'BOOT_PHP_DIR', HOME_DIR . 'sources/');
}

/*
// PHP拡張子(*)
*/
define( 'BOOT_PHP_EXTENSION', '.php');

/*
// HTMLディレクトリ(*)
*/
define( 'BOOT_HTML_DIR', HOME_DIR . 'templates/');

/*
// HTML拡張子(*)
*/
define( 'BOOT_HTML_EXTENSION', '.html');

/*
// ファイルパス調整用(*)
*/
define( 'BOOT_DELETE_PATH', 'testplay/denryoku-sharing/html/');

/*
// オリジナルURL(*)
*/
define( 'BOOT_ORIGIN_PARAM', 'origin_file');



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

define( 'BOOT_PHP_FILE', substr($bootPhpFile, 1).BOOT_PHP_EXTENSION);
define( 'BOOT_HTML_FILE', substr($bootHtmlFile, 1).BOOT_HTML_EXTENSION);

if (strpos($bootHtmlFile, '.') !== false && substr($bootHtmlFile, (strlen($bootHtmlFile) - strlen(BOOT_PHP_EXTENSION)), strlen(BOOT_PHP_EXTENSION)) != BOOT_PHP_EXTENSION) {
	if (file_exists(BOOT_HTML_DIR.$bootHtmlFile)) {
		require_once BOOT_HTML_DIR.$bootHtmlFile;
		exit;
	}
}

if (file_exists(BOOT_PHP_DIR.BOOT_PHP_FILE)) {
	# PHPロジックあり
	require_once WEB_APP_CONFIG_DIR."cfg.inc.php";
	require_once BOOT_PHP_DIR.BOOT_PHP_FILE;
	if (file_exists(BOOT_HTML_DIR.BOOT_HTML_FILE)) require_once BOOT_HTML_DIR.BOOT_HTML_FILE;
	exit;
} elseif (file_exists(BOOT_HTML_DIR.BOOT_HTML_FILE)) {
	# HTMLビューあり
	require_once WEB_APP_CONFIG_DIR."cfg.inc.php";
	require_once WEB_APP."public.php";
	require_once BOOT_HTML_DIR.BOOT_HTML_FILE;
	exit;
}

header("HTTP/1.0 404 Not Found");
require 'page/404.html';
