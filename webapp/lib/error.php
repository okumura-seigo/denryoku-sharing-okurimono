<?php

function cmsErrorHandler($errno, $errstr, $errfile, $errline, $errcontext) {

}

function cmsShutdownHandler() {
	if (DEBUG_MODE == 0) {
		$error = error_get_last();
 		if(($error['type'] === E_ERROR) || ($error['type'] === E_USER_ERROR) || ($error['type'] === E_USER_NOTICE)) {
			cmsErrorMail(error_get_last());
		}
	}
}

function cmsErrorMail($error) {
	loadLibrary('mail');

	$file = $_SERVER['REQUEST_URI'];
	$server = print_r($_SERVER, true);
	$errorInfo = print_r($error, true);
	$errSQL = $error;

	if (isset($error['message']) && strpos($error['message'], 'MDB2') !== false) {
		$objDB = DBT_Base::startTracking();
		echo $objDB->getLastSql();
	}

	$errorSub = ERR_MAIL_TITLE.' '.DMN_NAME;
	$errorCon =<<<TEMP

System Exception

$errorInfo

------------------------------------------------------
■FilePath
$file
------------------------------------------------------
■SQL
$errSQL
------------------------------------------------------

$server

TEMP;
	sendMail(ERR_MAIL, $errorSub, $errorCon, ERR_MAIL, ERR_MAIL_TITLE);
}

?>