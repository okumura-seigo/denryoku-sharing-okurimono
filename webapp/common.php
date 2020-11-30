<?php

// デバッグモード設定
if (!defined('DEBUG_MODE')) define('DEBUG_MODE', 0);

// 言語設定
mb_internal_encoding(APP_ENC);
mb_regex_encoding(APP_ENC);

// SSLリダイレクト
if (HOME_URL != SSL_URL) {
	require_once WEB_APP.'config/ssl.inc.php';
	if(getenv('SERVER_PORT') == '443' && array_search($_SERVER['PHP_SELF'], $sslArray) === false){
		header("Location: http://".$_SERVER['SERVER_NAME']."".$_SERVER['REQUEST_URI']);
		exit;
	}

	if(getenv('SERVER_PORT') != '443' && array_search($_SERVER['PHP_SELF'], $sslArray) !== false){
		header("Location: https://".$_SERVER['SERVER_NAME']."".$_SERVER['REQUEST_URI']);
		exit;
	}
}

// 文字コード対策
if (APP_FORCE_ENC != '') {
	if ($_REQUEST['noenc'] != "1") {
		function convert_encode($arr) {
				if (is_array($arr)) {
					return array_map('convert_encode', $arr);
				} else {
					return mb_convert_encoding($arr, APP_ENC, FORCE_ENCODE);
				}
		}

		$_GET     = convert_encode($_GET);
		$_POST    = convert_encode($_POST);
		$_REQUEST = convert_encode($_REQUEST);
	}
}

// NULLバイト対策
function null_byte_slashes($arr)
{
	if (is_array($arr)) {
		return array_map('null_byte_slashes', $arr);
	} else {
		return str_replace("\0",'',$arr);
	}
}

$_GET     = null_byte_slashes($_GET);
$_POST    = null_byte_slashes($_POST);
$_REQUEST = null_byte_slashes($_REQUEST);

// magic_quotes_gpc対策
if (get_magic_quotes_gpc()) {
function strip_magic_slashes($arr)
{
		if (is_array($arr)) {
			return array_map('strip_magic_slashes', $arr);
		} else {
			return stripslashes($arr);
		}
}

$_GET     = strip_magic_slashes($_GET);
$_POST    = strip_magic_slashes($_POST);
$_REQUEST = strip_magic_slashes($_REQUEST);
}

// セッションスタート
if (SESSION_USED == true) {
	ini_set('session.gc_divisor', 1);
	ini_set('session.gc_maxlifetime', SESSION_TIMEOUT * 60);
	ini_set('session.gc_probability', 1);
	ini_set('session.gc_divisor', 1);
	ini_set('session.save_handler', SESSION_SAVE_HANDLER);
	session_save_path( SESSION_SAVE_DIR );
	session_cache_expire(SESSION_TIMEOUT);
	session_cache_limiter('private, must-revalidate');
	session_start();
	define("SESSION_BEFORE_ID", session_id());
//	if (SESSION_FIXED == false) session_regenerate_id(true);
}

// デバッグ用
if (DEBUG_MODE == 1 || isset($debugMode) && $debugMode == '1') {
	header('Content-Type: text/html; charset='.APP_ENC);
	echo '* * * DebugMode * * *<br>';
	echo '<form action="'.$_SERVER['PHP_SELF'].'" method="'.$_SERVER['REQUEST_METHOD'].'">';
	echo 'REQUEST_METHOD : '.$_SERVER['REQUEST_METHOD'].'<br>';
	if ($_SERVER['REQUEST_METHOD']=="POST") {
		foreach($_POST as $key => $val) {
			if (!is_array($val)) {
				echo $key.' : <textarea name="'.htmlspecialchars($key, ENT_QUOTES).'" rows="1" style="height:1.25em;">'.(htmlspecialchars($val, ENT_QUOTES)).'</textarea><br>';
			} else {
				echo $key.' : <textarea name="'.htmlspecialchars($key, ENT_QUOTES).'" rows="1" style="height:1.25em;">'.(print_r($val, true)).'</textarea><br>';
			}
		}
	} else {
		foreach($_GET as $key => $val) {
			if (!is_array($val)) {
				echo $key.' : <textarea name="'.htmlspecialchars($key, ENT_QUOTES).'" rows="1" style="height:1.25em;">'.(htmlspecialchars($val, ENT_QUOTES)).'</textarea><br>';
			} else {
				echo $key.' : <textarea name="'.htmlspecialchars($key, ENT_QUOTES).'" rows="1" style="height:1.25em;">'.(print_r($val, true)).'</textarea><br>';
			}
		}
	}
	if (count($_POST) > 0 || count($_GET) > 0) {
		echo '<br><input type="submit" value="DebugSend">';
	}
	echo '</form>';
	echo '<hr>';
}

// ロードモジュール
require_once LIB_DIR.'common.php';

define('MODULE_LIB_REQUEST', true);
define('MODULE_LIB_VIEW', true);
if (MODULE_LIB_REQUEST) loadLibrary('request');
if (DB_TYPE != '') {
	if (DB_FULL_CONNECT === true) $objDB = loadQueryModule();
	if (DB_FULL_CONNECT === true && SDB_HOST !== '') $objDBSlave = loadQueryModule('SLAVE');
}
if (MODULE_LIB_VIEW) loadLibrary('view');
if (MODULE_LIB_VIEW) loadLibrary('form');
loadLibrary('data');
loadLibrary('error');

// 互換性
loadLogic('compatible');

// HTTPヘッダ
//header("Content-type: text/html; charset=utf-8");
//header('X-FRAME-OPTIONS: DENY');

// エラー処理
//register_shutdown_function("cmsShutdownHandler");
//set_error_handler("cmsErrorHandler");

?>