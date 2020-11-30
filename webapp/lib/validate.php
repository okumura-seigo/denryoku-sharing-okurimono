<?php

/*
// セレクトボックスで年を表示する（開始年度, [復元値], [終了年度]）
*/
function selectBoxYear($StartYear, $Value = 1970, $EndYear = 0) {
	$tag = "";
	if (empty($EndYear)) {
		$EndYear = date("Y");
	}
	for ($i = $StartYear;$i <= $EndYear;$i++) {
		$tag.="<option value=\"".$i."\" ";
		if ($i == $Value) {
			$tag.="selected";
		}
		$tag.=">".$i."</option>\n";
	}
	return $tag;
}

/*
// セレクトボックスに月の<option>タグを出力(復元値)
*/
function selectBoxMonth($val) {
	$tag = "";
	for ($i = 1;$i <= 12;$i++) {
		$tag.="<option value=\"".$i."\" ";
		if (digitNum($i, 2) == $val) {
			$tag.="selected";
		}
		$tag.=">".digitNum($i,2)."</option>\n";
	}
	return $tag;
}

/*
// セレクトボックスに日の<option>タグを出力(復元値)
*/
function selectBoxDay($val) {
	$tag = "";
	for ($i = 1;$i <= 31;$i++) {
		$tag.="<option value=\"".$i."\" ";
		if (digitNum($i, 2) == $val) {
			$tag.="selected";
		}
		$tag.=">".digitNum($i,2)."</option>\n";
	}
	return $tag;
}

/*
// セレクトボックスに時の<option>タグを出力(復元値)
*/
function selectBoxHour($val) {
	$tag = "";
	for ($i = 0;$i <= 23;$i++) {
		$tag.="<option value=\"".$i."\" ";
		if (digitNum($i, 2) == $val) {
			$tag.="selected";
		}
		$tag.=">".digitNum($i,2)."</option>\n";
	}
	return $tag;
}

/*
// セレクトボックスに分の<option>タグを出力(復元値)
*/
function selectBoxMinute($val, $sep = 15) {
	$tag = "";
	for ($i = 0;$i < 60;$i+=$sep) {
		$tag.="<option value=\"".$i."\" ";
		if (digitNum($i, 2) == $val) {
			$tag.="selected";
		}
		$tag.=">".digitNum($i,2)."</option>\n";
	}
	return $tag;
}

/*
// セレクトボックスで年を表示するSmarty用（開始年度, [復元値], [終了年度]）
*/
function selectBoxYearSmarty($format, $Value = 1970) {
	// format -> 'Y-5:2008' etc...
	$parts = explode(":", $format);
	if (is_numeric($parts[0])) {
		$start = $parts[0];
	} else {
		$start = date("Y") + str_replace("Y", "", $parts[0]);
	}
	if (is_numeric($parts[1])) {
		$end = $parts[1];
	} else {
		$end = date("Y") + str_replace("Y", "", $parts[1]);
	}

	$tag = selectBoxYear($start, $Value, $end);

	return $tag;
}

/*
// データベースの内容をセレクトボックスに表示(SQL, valueカラム, nameカラム, [復元値])
*/
function selectBoxDB($sql, $value, $name, $ret = "") {
	global $con;
	$objDB = DBObject($con);
	$objDB->Query($sql);
	$objDB->OptResult();
	$tag = "";
	for ($i = 0;$i < $objDB->rows;$i++) {
		$tag.="<option value=\"".$objDB->getResult($i,$value)."\" ";
		if ($objDB->getResult($i,$value) == $ret) {
			$tag.="selected";
		}
		$tag.=">".$objDB->getResult($i,$name)."</option>";
	}
	return $tag;
}

/*
// hiddenタグの自動生成(配列)
*/
function putHidden($array, $flg = "val") {
	$tag = "";
	foreach($array as $key => $val){
		if ($flg == "val") {
			global $$val;
			$tag.="<input type=\"hidden\" name=\"".$val."\" value=\"".htmlspecialchars($$val)."\">\n";
		} else {
			global $$key;
			$tag.="<input type=\"hidden\" name=\"".$key."\" value=\"".htmlspecialchars($$key)."\">\n";
		}
	}
	return $tag;
}

/*
// リダイレクト
*/
function redirectUrl($url, $code = "302") {
	if ($code == "301") header("HTTP/1.1 301 Moved Permanently");
	header("Location: ".$url);
	exit;
}

?>