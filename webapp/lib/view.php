<?php

/*
// HTMLエスケープ
*/
function escapeHtml($param) {
	return htmlspecialchars($param, ENT_QUOTES);
}
function h($param) {
	return escapeHtml($param);
}
function esc($param) {
	return escapeHtml($param);
}

function pagerData($maxRows, $listNum, $page, $params = "", $data = "") {
	$pagerData = array();

	if ($array == "") $array = array();
	if ($data == "") {
		$param = "p";
		$number = 3;
	} else {
		extract($data);
	}
	$args = array();
	foreach ($params as $key => $val) {
		if (!is_array($val)) {
			if ($key != $param) $args[] = $key."=".urlencode($val);
		} else {
			foreach ($val as $key2 => $val2) $args[] = $key."[".$key2."]=".urlencode($val2);
		}
	}
	$cepStr1 = (count($args) == 0) ? '' : '?';
	$cepStr2 = (count($args) == 0) ? '' : '&';

	$pagerData['first'] = ($page == 1) ? '' : $_SERVER['PHP_SELF'].$cepStr1.implode("&", $args);
	$pagerData['prev'] = ($page == 1) ? '' : $_SERVER['PHP_SELF'].'?p='.($page - 1).$cepStr2.implode("&", $args);

	$startNum = $page - $number;
	if ($startNum < 1) $startNum = 1;
	$endNum = $page + $number;
	if ($endNum > floor($maxRows / $listNum)) $endNum = floor($maxRows / $listNum) + 1;
	for ($i = $startNum;$i <= (floor($maxRows / $listNum) + 1) && $i <= $endNum;$i++) $pagerData[$i] = ($i == $page) ? '' : $_SERVER['PHP_SELF'].'?p='.$i.$cepStr2.implode("&", $args);

	$pagerData['next'] = ($page == (floor($maxRows / $listNum) + 1)) ? '' : $_SERVER['PHP_SELF'].'?p='.($page + 1).$cepStr2.implode("&", $args);
	$pagerData['last'] = ($page == (floor($maxRows / $listNum) + 1)) ? '' : $_SERVER['PHP_SELF'].'?p='.(floor($maxRows / $listNum) + 1).$cepStr2.implode("&", $args);

	return $pagerData;
}

function pagerNoData($maxRows, $listNum, $page, $params = "", $data = "") {
	$pagerData = array();

	if ($array == "") $array = array();
	if ($data == "") {
		$param = "p";
		$number = 3;
	} else {
		extract($data);
	}

	$pagerData['first'] = ($page <= 1) ? '' : 1;
	$pagerData['prev'] = ($page <= 1) ? '' : $page - 1;

	$startNum = $page - $number;
	if ($startNum < 1) $startNum = 1;
	$endNum = $page + $number;
	if ($endNum > floor($maxRows / $listNum)) $endNum = floor($maxRows / $listNum) + 1;
	for ($i = $startNum;$i <= (floor($maxRows / $listNum) + 1) && $i <= $endNum;$i++) $pagerData[$i] = ($i == $page) ? '' : $i;

	$pagerData['next'] = ($page >= (floor($maxRows / $listNum) + 1)) ? '' : $page + 1;
	$pagerData['last'] = ($page >= (floor($maxRows / $listNum) + 1)) ? '' : floor($maxRows / $listNum) + 1;

	return $pagerData;
}

function viewExtractParam($requestData, $arrParam) {
	$viewData = array();
	foreach ($arrParam as $key => $val) {
		if (isset($requestData[$key])) {
			$viewData[$key] = $requestData[$key];
		} else {
			$viewData[$key] = '';
		}
	}

	return $viewData;
}

function viewImage($path, $noimage = NO_IMAGE) {
	if (file_exists($path) && !is_dir($path)) {
		$fileSrc = str_replace(HTML_DIR, '/', $path);
		return $fileSrc;
	}
	return $noimage;
}


?>