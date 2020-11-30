<?php

/*
// ファイル名と拡張子に分ける
*/
function getExpArray($filename) {
	$exp = explode(".", $filename);
	$last = $exp[(count($exp) - 1)];
	unset($exp[(count($exp) - 1)]);
	return array(implode(".", $exp), $last);
}

/*
// 指定されたディレクトリのファイルを返す
*/
function viewDirFiles($dir) {
	if ($handle = opendir($dir)) {
		$fileArray = array();
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				array_push($fileArray, $file);
			}
		}
		closedir($handle);
		return $fileArray;
	}
	return false;
}

/*
// ファイルのダウンロード
*/
function downloadFile($file, $name = "") {
	if ($name == "") {
		$path = explode("/", $file);
		$name = $path[(count($path) - 1)];
	}
	header("Content-Disposition: attachment; filename=".basename($file));
	header("Content-length:".filesize(basename($file)));
	header("Content-type: application/octet-stream");
	readfile(basename($name));
}

/*
// 画像の出力
*/
function outputImage($file, $mimetype = "") {
	// ファイル出力
	if ($mimetype == "") $mimetype = exif_imagetype($file);

	if ($mimetype == 2) {
		$image = imagecreatefromjpeg($file);
		header("Content-Type: image/jpeg");
		imagejpeg($image);
		@imagedestroy($image);
	} elseif ($mimetype == 1) {
		$image = imagecreatefromgif($file);
		header("Content-Type: image/gif");
		imagegif($image);
		@imagedestroy($image);
	} elseif ($mimetype == 3) {
		$image = imagecreatefrompng($file);

		imagealphablending($image, false);
		imagesavealpha($image, true);
		$fillcolor = imagecolorallocatealpha($image, 0, 0, 0, 127);
		imagefill($image, 0, 0, $fillcolor);

		header("Content-Type: image/png");
		imagepng($image);
		@imagedestroy($image);
	}
}

function fgetcsv_reg (&$handle, $length = null, $d = ',', $e = '"') {
	$d = preg_quote($d);
	$e = preg_quote($e);
	$_line = "";
	$eof = false; // Added for PHP Warning.
	while ( $eof != true ) {
		$_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
		$itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
		if ($itemcnt % 2 == 0) $eof = true;
	}
	$_csv_line = preg_replace('/(?:\\r\\n|[\\r\\n])?$/', $d, trim($_line));
	$_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';

	preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);

	$_csv_data = $_csv_matches[1];

	for ( $_csv_i=0; $_csv_i<count($_csv_data); $_csv_i++ ) {
		$_csv_data[$_csv_i] = preg_replace('/^'.$e.'(.*)'.$e.'$/s', '$1', $_csv_data[$_csv_i]);
		$_csv_data[$_csv_i] = str_replace($e.$e, $e, $_csv_data[$_csv_i]);
	}
	return empty($_line) ? false : $_csv_data;
}

function optQuotes($str) {
	return mb_convert_encoding(str_replace('"', '""', $str), "SJIS-win", APP_ENC);
}

?>