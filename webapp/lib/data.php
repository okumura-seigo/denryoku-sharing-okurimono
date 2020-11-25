<?php

// テキスト暗号化
function encryptData($data){
	$method = 'aes-128-cbc';
	$options = 0;
	$key = md5(CRYPT_KEY);

	$ivLength = openssl_cipher_iv_length($method);
	$iv = openssl_random_pseudo_bytes($ivLength);
	$encryptData = openssl_encrypt($data, $method, $key);
	$encrypt = $encryptData;

	$tmpEncrypt = str_replace('=', '', $encrypt, $count);
	$hush = md5($tmpEncrypt);
	$char1 = substr($hush, 2, 1);
	$char2 = substr($hush, 12, 1);
	$char3 = substr($hush, 27, 1);
	$encryptData = $tmpEncrypt.$char1.$char2.$char3.$count;

	return $encryptData;
}

// パラメータ暗号化
function encryptParam($param){
	$param['unix_timestamp'] = time();
	$encryptData = encryptData(serialize($param));

	return $encryptData;
}

// テキスト複合化
function decryptData($data){
	$method = 'aes-128-cbc';
	$options = 0;
	$key = md5(CRYPT_KEY);

	$count = substr($data, -1);
	$tmpEncrypt = substr($data, 0, strlen($data) - 4);

	$hush = md5($tmpEncrypt);
	if (substr($data, -4, 1) === substr($hush, 2, 1) &&
		substr($data, -3, 1) === substr($hush, 12, 1) &&
		substr($data, -2, 1) === substr($hush, 27, 1)) {
		$encrypt = $tmpEncrypt.str_repeat("=", $count);
	} else {
		exit;
	}

	$ivLength = openssl_cipher_iv_length($method);
	$iv = openssl_random_pseudo_bytes($ivLength);
	$decryptData = openssl_decrypt($encrypt, $method, $key);

	return $decryptData;
}

// パラメータ複合化
function decryptParam($data){
	$decryptData = decryptData($data);
	$decryptArray = unserialize($decryptData);
	
	return $decryptArray;
}

/*
// データの一括チェック(RD配列, 配列, 配列)
*/
function checkDataType($requestData, $arrParam, $TypeArray) {
	/* validate */
	if (!is_array($requestData)) $requestData = array();
	if (!is_array($arrParam)) $arrParam = array();
	if (!is_array($TypeArray)) $TypeArray = array();

	/* main */
	$returnMsg = array();
	foreach($TypeArray as $key => $val) {
		$errMsg = "";
		$errFlg = "";
		$datacheck = explode("||", $val);
		foreach($datacheck as $val2) {
			if ($val2 != "") {
				$errFlg = 1;
				if (checkData($requestData[$key], trim($val2))) {
					$errFlg = "";
					break;
				}
			}
		}
		if (!empty($errFlg)) {
			$errMsg .= $arrParam[$key].mb_convert_encoding("が正しく入力されておりません", APP_ENC, "UTF-8");
		}
		if ($errMsg) $returnMsg[] = $errMsg;
	}
	return $returnMsg;
}

/*
// データチェック(データ, 基準)
*/
function checkData($data, $flg = "") {
	/* main */
	switch($flg) {
		case "empty":
			if (empty($data)) return true;	// 空
			break;
		case "!empty":
			if (!empty($data)) return true;	// 空
			break;
		case "digit":
			if (is_numeric($data)) return true;	// 数字
			break;
		case "digitE":
			if (is_numeric($data) || $data == "") return true;	// 数字
			break;
		case "natural":
			if (is_numeric($data) && $data > 0) return true;	// 自然数
			break;
		case "naturalE":
			if (is_numeric($data) && $data > 0 || $data == "") return true;	// 自然数
			break;
		case "alpah":
			if (preg_match('/^[a-zA-Z\']+$/',$data)) return true;	// 半角英字
			break;
		case "alpahE":
			if (preg_match('/^[a-zA-Z\']+$/',$data) || $data == "") return true;	// 半角英字
			break;
		case "alnum":
			if (preg_match('/^[0-9a-zA-Z\'\-]+$/',$data)) return true;	// 半角英数字
			break;
		case "alnumE":
			if (preg_match('/^[0-9a-zA-Z\'\-]+$/',$data) || $data == "") return true;	// 半角英数字
			break;
		case "hira":
			if (mb_ereg("^[".mb_convert_encoding("ぁ-んー 　", APP_ENC, "UTF-8")."]+$",$data)) return true;	// ひらがな
			break;
		case "hiraE":
			if (mb_ereg("^[".mb_convert_encoding("ぁ-んー 　", APP_ENC, "UTF-8")."]+$",$data) || $data == "") return true;	// ひらがな
			break;
		case "kata":
			if (mb_ereg("^[".mb_convert_encoding("ァ-ンーヴ 　", APP_ENC, "UTF-8")."]+$",$data)) return true;	// 全角カタカナ
			break;
		case "kataE":
			if (mb_ereg("^[".mb_convert_encoding("ァ-ンーヴ 　", APP_ENC, "UTF-8")."]+$",$data) || $data == "") return true;	// 全角カタカナ
			break;
		case "email":
			if (preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', $data)) { return true; }
			break;
		case "emailE":
			if (preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', $data) || $data == "") { return true; }
			break;
		case "url":
			if (preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $data)) return true;
			break;
		case "urlE":
			if (preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $data) || $data == "") return true;
			break;
		case "flg":
			if (DB_TYPE == "psql") {
				if ($data == "t" || $data == "f" || $data == "0" || $data == "1") return true;
			} elseif (DB_TYPE == "mysql") {
				if ($data == "0" || $data == "1") return true;
			}
			break;
		case "tel":
			if (preg_match("/^\d{2,5}\-?[0-9]{1,4}\-?\d{4}$/", $data)) return true;
			break;
		case "telE":
			if (preg_match("/^\d{2,5}\-?[0-9]{1,4}\-?\d{4}$/", $data) || str_replace("-", "", $data) == "") return true;
			break;
		case "post":
			if (preg_match('/^\d{3}\-?\d{4}$/', $data)) return true;
			break;
		case "postE":
			if (preg_match('/^\d{3}\-?\d{4}$/', $data) || str_replace("-", "", $data) == "") return true;
			break;
		case "date":
			$tmp = explode("-", str_replace("/", "-", $data));
			if ($tmp[0] != '' && isset($tmp[1]) && isset($tmp[2])) {
				if (is_numeric($tmp[0]) && is_numeric($tmp[1]) && is_numeric($tmp[2])) if (checkdate($tmp[1], $tmp[2], $tmp[0])) return true;
			}
			break;
		case "dateE":
			if ($data == "0000-00-00") return true;
			$tmp = explode("-", str_replace("/", "-", $data));
			if ($tmp[0] != '' && isset($tmp[1]) && isset($tmp[2])) {
				if (is_numeric($tmp[0]) && is_numeric($tmp[1]) && is_numeric($tmp[2]) || str_replace("-", "", $data) == "") if (checkdate($tmp[1], $tmp[2], $tmp[0]) || str_replace("-", "", $data) == "") return true;
			} elseif ($tmp[0] == '' && !isset($tmp[1]) && !isset($tmp[2])) {
				return true;
			}
			break;
		case "timestamp":
			$tmp = explode(" ", str_replace("/", "-", $data));
			if (isset($tmp[0])) $tmp1 = explode("-", $tmp[0]);
			if (isset($tmp[1])) $tmp2 = explode(":", $tmp[1]);
			if (isset($tmp1) && isset($tmp2) && is_numeric($tmp1[0]) && is_numeric($tmp1[1]) && is_numeric($tmp1[2]) && is_numeric($tmp2[0]) && is_numeric($tmp2[1]) && is_numeric($tmp2[2])) {
				foreach ($tmp1 as $tpKey => $tpVal) $tmp1[$tpKey] = (int)$tpVal;
				foreach ($tmp2 as $tpKey => $tpVal) $tmp2[$tpKey] = (int)$tpVal;
				if (checkdate($tmp1[1], $tmp1[2], $tmp1[0]) && $tmp2[0] < 24 && $tmp2[1] < 60 && $tmp2[2] < 60) return true;
			}
			break;
		case "timestampE":
			if (str_replace("/", "-", $data) == "") return true;
			$tmp = explode(" ", str_replace("/", "-", $data));
			if (isset($tmp[0])) $tmp1 = explode("-", $tmp[0]);
			if (isset($tmp[1])) $tmp2 = explode(":", $tmp[1]);
			if (isset($tmp1) && isset($tmp2) && is_numeric($tmp1[0]) && is_numeric($tmp1[1]) && is_numeric($tmp1[2]) && is_numeric($tmp2[0]) && is_numeric($tmp2[1]) && is_numeric($tmp2[2])) {
				foreach ($tmp1 as $tpKey => $tpVal) $tmp1[$tpKey] = (int)$tpVal;
				foreach ($tmp2 as $tpKey => $tpVal) $tmp2[$tpKey] = (int)$tpVal;
				if (checkdate($tmp1[1], $tmp1[2], $tmp1[0]) && $tmp2[0] < 24 && $tmp2[1] < 60 && $tmp2[2] < 60 || empty($tmp1[0]) && empty($tmp1[1]) && empty($tmp1[2]) && empty($tmp2[0]) && empty($tmp2[1]) && empty($tmp2[2])) return true;
			}
			break;
		case "path":
			if (preg_match('/^[a-zA-Z\-\_\/]+$/',$data)) return true;	// 半角英数字
			break;
		case "pathE":
			if (preg_match('/^[a-zA-Z\-\_\/]+$/',$data) || $data == "") return true;	// 半角英数字
			break;
		case "":
			return true;
		default:
			return false;
	}
	return false;
}

/*
// Trimの一括変換
*/
function applyTrim($requestData, $array, $flg = "key") {
	/* validate */
	if (!is_array($requestData)) $requestData = array();
	if (!is_array($array)) $array = array();

	foreach($array as $key => $val) {
		if ($flg == "val") {
			$requestData[$val] = trim($requestData[$val]);
		} else {
			$requestData[$key] = trim($requestData[$key]);
		}
	}

	return $requestData;
}

/*
// 数値をn桁の文字列にする(数値, n)
*/
function digitNum($int, $n) {
	$str = sprintf("%0".$n."d", $int);

	return $str;
}

function optDate($date, $sep = '.') {
	$date = date('Y'.$sep.'m'.$sep.'d', strtotime($date));

	return $date;
}

/*
// パスワード自動生成(桁数)
*/
function randStr($limit) {
	/* validate */
	if (!is_numeric($limit)) return false;

	srand(time());
	$passchars = '0123456789abcdefghikmnopqrstuwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
	$len = strlen($passchars);
	$i = 0;
	$pass = '';
	while ($i < $limit) {
		$pass .= substr($passchars, rand(0, $len - 1), 1);
		$i++;
	}
	return $pass;
}

/*
// 文字列サポートの一括変換
*/
function applyKana($requestData, $array, $enc = APP_ENC) {
	/* validate */
	if (!is_array($requestData)) $requestData = array();
	if (!is_array($array)) $array = array();

	foreach($array as $key => $val) {
		if (!empty($requestData[$key])) {
			$requestData[$key] = mb_convert_kana($requestData[$key], $array[$key], $enc);
		}
	}

	return $requestData;
}

/*
// emptyの場合に0を返す(値)
*/
function empty0($val) {
	if (empty($val)) $val = "0";
	return $val;
}

/*
// 長さの一括チェック(配列,配列,[配列])
*/
function checkLength($requestData, $arrParam, $MaxArray, $MinArray = "") {
	/* init */
	if ($MinArray == "") $MinArray = array();

	/* validate */
	if (!is_array($requestData)) $requestData = array();
	if (!is_array($arrParam)) $arrParam = array();

	$returnMsg = array();
	$lengthArray = array();

	foreach ($MaxArray as $key => $val) $lengthArray[$key][0] = $val;
	foreach ($MinArray as $key => $val) $lengthArray[$key][1] = $val;

	foreach ($lengthArray as $key => $val) {
		if (isset($requestData[$key]) && !is_array($requestData[$key])) {
			$errMsg = "";
			if (isset($val[0]) && $val[0] && isset($val[1]) && $val[1]) {
				if (!maxLength($requestData[$key], $MaxArray[$key]) || !minLength($requestData[$key], $MinArray[$key])) {
					if ($MaxArray[$key] == $MinArray[$key]) {
						$errMsg = $arrParam[$key].mb_convert_encoding("は", APP_ENC, "UTF-8").$MinArray[$key].mb_convert_encoding("文字で入力してください", APP_ENC, "UTF-8");
					} else {
						$errMsg = $arrParam[$key].mb_convert_encoding("は", APP_ENC, "UTF-8").$MinArray[$key].mb_convert_encoding("～", APP_ENC, "UTF-8").$MaxArray[$key].mb_convert_encoding("文字で入力してください", APP_ENC, "UTF-8");
					}
				}
			} elseif (isset($val[0]) && $val[0]) {
				if (!maxLength($requestData[$key], $MaxArray[$key])) {
					$errMsg = $arrParam[$key].mb_convert_encoding("は", APP_ENC, "UTF-8").$MaxArray[$key].mb_convert_encoding("文字以内で入力してください", APP_ENC, "UTF-8");
				}
			} elseif (isset($val[1]) && $val[1]) {
				if (!minLength($requestData[$key], $MinArray[$key])) {
					$errMsg = $arrParam[$key].mb_convert_encoding("は", APP_ENC, "UTF-8").$MinArray[$key].mb_convert_encoding("文字以上で入力してください", APP_ENC, "UTF-8");
				}
			}
			if ($errMsg) $returnMsg[] = $errMsg;
		}
	}
	return $returnMsg;
}

/*
// 文字列の最大長チェック
*/
function maxLength($val, $len) {
	/* validate */
	if (!is_numeric($len)) return false;

	if (mb_strlen($val) <= $len) {
		$res = true;
	} else {
		$res = false;
	}
	return $res;
}

/*
// 文字列の最大長チェック
*/
function minLength($val, $len) {
	/* validate */
	if (!is_numeric($len)) return false;

	if (mb_strlen($val) >= $len) {
		$res = true;
	} else {
		$res = false;
	}
	return $res;
}

/*
// 配列検索チェック
*/
function inArray($needle, $haystack, $strict = false) {
	if (is_array($haystack)) {
		return in_array($needle, $haystack, $strict);
	}
	return false;
}

/*
// 文字列を*で隠す
*/
function secretStr($val) {
	$str = "";
	for ($i = 0;$i < mb_strlen($val);$i++) {
		$str.="*";
	}
	return $str;
}

?>