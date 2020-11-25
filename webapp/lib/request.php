<?php

function getFormState($arrState = array("form", "confirm", "execute"), $method = "POST") {
	$formstate = "";
	if ($method == "POST") {
		foreach ($arrState as $val) {
			if (isset($_POST[$val]) || isset($_POST[$val.'_x'])) $formstate = $val;
		}
	} elseif ($method == "GET") {
		foreach ($arrState as $val) {
			if (isset($_GET[$val]) || isset($_GET[$val.'_x'])) $formstate = $val;
		}
	} else {
		foreach ($arrState as $val) {
			if (isset($_REQUEST[$val]) || isset($_REQUEST[$val.'_x'])) $formstate = $val;
		}
	}
	if ($formstate == "") $formstate = "form";

	return $formstate;
}

function getParam($arrParam, $formstate = "", $arrMethod = "") {
	if ($formstate == "") {
		$formstate = "page";
		$arrMethod = array("page" => $_SERVER['REQUEST_METHOD']);
	}
	if ($arrMethod == "") $arrMethod = array("form" => $_SERVER['REQUEST_METHOD'], "confirm" => "POST", "execute" => "POST");
	$request = array();
	$METHOD = $arrMethod[$formstate];

	if (count($arrParam) > 0) {
		if ($METHOD == "POST") {
			foreach ($arrParam as $key => $val) {
				$request[$key] = (isset($_POST[$key])) ? $_POST[$key] : '';
			}
		} else if ($METHOD == "GET") {
			foreach ($arrParam as $key => $val) {
				$request[$key] = (isset($_GET[$key])) ? $_GET[$key] : '';
			}
		}
	}
	return $request;
}

function checkFirstAction($arrParam, $firstParam = "", $method = "") {
	if ($firstParam == "") $firstParam = array();
	if ($method == "") $method = $_SERVER['REQUEST_METHOD'];

	$firstActionFlg = true;
	if (count($arrParam) > 0) {
		if ($method == "POST") {
			foreach ($arrParam as $key => $val) {
				if (!in_array($key, $firstParam)) {
					if (isset($_POST[$key])) {
						$firstActionFlg = false;
						break;
					}
				}
			}
		} elseif ($method == "GET") {
			foreach ($arrParam as $key => $val) {
				if (!in_array($key, $firstParam)) {
					if (isset($_GET[$key])) {
						$firstActionFlg = false;
						break;
					}
				}
			}
		} elseif ($method == "REQUEST") {
			foreach ($arrParam as $key => $val) {
				if (!in_array($key, $firstParam)) {
					if (isset($_REQUEST[$key])) {
						$firstActionFlg = false;
						break;
					}
				}
			}
		}
	}

	return $firstActionFlg;
}

function getRequestData($arrParam, $method = "") {
	return getParamData($arrParam, $method);
}

function getParamData($arrParam, $method = "") {
	if ($method == "") $method = $_SERVER['REQUEST_METHOD'];

	// 項目整理
	$needArray = array();
	$defaultArray = array();
	foreach ($arrParam as $key => $val) {
		if (is_array($val)) {
			$arrParam[$key] = array('name' => $val);
		}
		if (isset($val['need']) && $val['need'] == true) $needArray[] = $key;
		if (isset($val['default'])) $defaultArray[$key] = $val['default'];
	}

	// 外部パラメータの取得
	$request = array();
	if (count($arrParam) > 0) {
		if ($method == "POST") {
			foreach ($arrParam as $key => $val) {
				if (isset($_POST[$key])) $request[$key] = $_POST[$key];
				if (!isset($_POST[$key]) && $defaultArray[$key]) $request[$key] = $defaultArray[$key];
			}
			if (isset($_POST[CRYPT_PARAM])) {
				$decryptParam = decryptParam($_POST[CRYPT_PARAM]);
				foreach ($arrParam as $key => $val) {
					if (isset($decryptParam[$key])) $request[$key] = $decryptParam[$key];
					if (!isset($decryptParam[$key]) && $defaultArray[$key]) $request[$key] = $defaultArray[$key];
				}
			}
		} elseif ($method == "GET") {
			foreach ($arrParam as $key => $val) {
				if (isset($_GET[$key])) $request[$key] = $_GET[$key];
				if (!isset($_GET[$key]) && $defaultArray[$key]) $request[$key] = $defaultArray[$key];
			}
			if (isset($_GET[CRYPT_PARAM])) {
				$decryptParam = decryptParam($_GET[CRYPT_PARAM]);
				foreach ($arrParam as $key => $val) {
					if (isset($decryptParam[$key])) $request[$key] = $decryptParam[$key];
					if (!isset($decryptParam[$key]) && $defaultArray[$key]) $request[$key] = $defaultArray[$key];
				}
			}
		} elseif ($method == "REQUEST") {
			foreach ($arrParam as $key => $val) {
				if (isset($_REQUEST[$key])) $request[$key] = $_REQUEST[$key];
				if (!isset($_REQUEST[$key]) && $defaultArray[$key]) $request[$key] = $defaultArray[$key];
			}
			if (isset($_REQUEST[CRYPT_PARAM])) {
				$decryptParam = decryptParam($_REQUEST[CRYPT_PARAM]);
				foreach ($arrParam as $key => $val) {
					if (isset($decryptParam[$key])) $request[$key] = $decryptParam[$key];
					if (!isset($decryptParam[$key]) && $defaultArray[$key]) $request[$key] = $defaultArray[$key];
				}
			}
		}
	}
	
	// 初期値の設定
	if (count($needArray) == count($request)) {
		foreach ($arrParam as $key => $val) {
			if (isset($val['default'])) $request[$key] = $val['default'];
			if (isset($val['array'])) $request[$key] = initialArray($val['array']);
			if (!isset($request[$key])) $request[$key] = '';
		}
	} else {
		foreach ($arrParam as $key => $val) {
			if (isset($val['array'])) $request[$key] = initialArray($val['array']);
			if (!isset($request[$key])) $request[$key] = '';
		}
	}

	return $request;
}

function checkInitialRequestData($requestData, $parameterArray) {
	foreach ($parameterArray as $parameter => $parameterData) {
		if (isset($parameterData['need']) && $parameterData['need'] == true) {
			if (checkRequestData($requestData, $parameterArray, $parameter) == false) {
				return false;
			}
		}
	}

	return true;
}

function checkRequestData($requestData, $parameterArray, $parameter) {
	if (isset($parameterArray[$parameter]['type']) && !checkData($requestData[$parameter], $parameterArray[$parameter]['type'])) return false;
	if (isset($parameterArray[$parameter]['length'])) {
		$expLength = explode('-', $parameterArray[$parameter]['length']);
		if (is_numeric($expLength[0])) {
			if (!minLength($requestData[$parameter], $expLength[0])) return false;
		}
		if (is_numeric($expLength[1])) {
			if (!maxLength($requestData[$parameter], $expLength[1])) return false;
		}
	}

	return true;
}

function initialArray($info, $now = 0) {
	$exp1 = explode(':', $info);
	if (!isset($exp1[$now])) return '';

	if ($exp1[$now] == '') {
		// 空
		$args = array();
	} elseif (is_numeric(str_replace('-', '', $exp1[$now]))) {
		// 数値
		$exp2 = explode('-', $exp1[$now]);
		if (!isset($exp2[1])) $exp2[1] = $exp2[0];
		for ($index = $exp2[0];$index <= $exp2[1];$index++) {
			$args[$index] = initialArray($info, $now + 1);
		}
	} else {
		$exp2 = explode('|', $exp1[$now]);
		foreach ($exp2 as $index) {
			$args[$index] = initialArray($info, $now + 1);
		}
	}

	return $args;
}

?>