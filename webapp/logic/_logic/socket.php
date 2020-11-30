<?php

function sendSockUrl($url, $timeout = 20) {
	$expUrl = parse_url($url);
	$paramUrl = explode("?", $url, 2);
	$hostname = $connectname = $expUrl['host'];
	$param = $expUrl['path']."?".$paramUrl[1];

	if ($expUrl['scheme'] == "http") {
		$port = 80;
	} elseif ($expUrl['scheme'] == "https") {
		$port = 443;
		$connectname = "ssl://".$hostname;
	} else {
		return false;
	}
	
	if ($expUrl['port'] != "") {
		$port = $expUrl['port'];
	}
	
	$fp = fsockopen($connectname, $port, $errno, $errstr, $timeout);
	
	if (!$fp) {
		return false;
	} else {
		$return = "";
		$out = "POST ".$param." HTTP/1.1\r\n";
		$out .= "Host: ".$hostname."\r\n";
		$out .= "Connection: Close\r\n\r\n";
		fwrite($fp, $out);
		while (!feof($fp)) {
			$return.= fgets($fp, 2048);
		}
		fclose($fp);
	}
	
	return $return;
}

?>