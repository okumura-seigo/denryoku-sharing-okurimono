<?php

// モジュールローダー
function loadLogic($filename) {
	require_once LOGIC_DIR.$filename.'.php';
}

function loadLibrary($filename) {
	require_once LIB_DIR.$filename.'.php';
}

function loadValidate($filename) {
	require_once VALIDATE_DIR.$filename.'.php';
}

function actionValidate($filename, &$requestData, $arrParam, $safeFlg = true) {
	loadValidate($filename);
	return call_user_func_array(lcfirst(strtr(ucwords(strtr($filename, array('_' => ' '))), array(' ' => ''))), array(&$requestData, &$arrParam, &$safeFlg));
}

function actionMail($filename, $requestData, $to, $from, $fromname, $headerData = "") {
    loadLibrary('mail');
    extract($requestData);
    require MAIL_DIR.$filename.'.php';
    sendMail($to, $mailSubject, $mailContent, $from, $fromname, $headerData);
}

function loadQueryModule($dsn = '', $new = false) {
	require_once LIB_DIR.'dbd/DBD_Query.php';
	return DBD_Query::singleQuery($dsn, $new);
}

function displayError() {
	ini_set("display_errors", "on");
	error_reporting(E_ALL);
}

function view404() {
	header("HTTP/1.0 404 Not Found");
	require SYSTEM_PAGE_DIR.'404.html';
}

function viewErrorPage() {
	require SYSTEM_PAGE_DIR.'error.html';
}

?>