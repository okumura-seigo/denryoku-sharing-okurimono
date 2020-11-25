<?php

require_once 'cms.php';

function cmsAddVal(&$requestData, $arrParam, $resParam, $compFlg = false) {
	$errMsg = array();
	$objDB = DBD_Query::singleQuery();

	foreach ($resParam as $key => $val) {
		if ($val['param_type'] == "1" && $val['param_type'] == "2" && $val['param_type'] == "3") {
			// 文字サポート
			$requestData = applyTrim(
				$requestData,
				array(
					optParamName($val['param_column'])
				)
			);
			$requestData = applyKana(
				$requestData,
				array(
					optParamName($val['param_column']) => "a",
				)
			);
		}
		if ($val['param_type'] == "9") {
			if (isset($requestData[optParamName($val['param_column'])."_y"]) || isset($requestData[optParamName($val['param_column'])."_m"]) || isset($requestData[optParamName($val['param_column'])."_d"])) {
				$requestData[optParamName($val['param_column'])] = $requestData[optParamName($val['param_column'])."_y"]."-".$requestData[optParamName($val['param_column'])."_m"]."-".$requestData[optParamName($val['param_column'])."_d"];
				if ($requestData[optParamName($val['param_column'])] == "--") $requestData[optParamName($val['param_column'])] = "";
			}
		}
		if ($val['param_type'] == "10") {
			if (isset($requestData[optParamName($val['param_column'])."_y"]) || isset($requestData[optParamName($val['param_column'])."_m"]) || isset($requestData[optParamName($val['param_column'])."_d"]) || isset($requestData[optParamName($val['param_column'])."_h"]) || isset($requestData[optParamName($val['param_column'])."_i"]) || isset($requestData[optParamName($val['param_column'])."_s"])) {
				$requestData[optParamName($val['param_column'])] = $requestData[optParamName($val['param_column'])."_y"]."-".$requestData[optParamName($val['param_column'])."_m"]."-".$requestData[optParamName($val['param_column'])."_d"]." ".$requestData[optParamName($val['param_column'])."_h"].":".$requestData[optParamName($val['param_column'])."_i"].":".$requestData[optParamName($val['param_column'])."_s"];
				if ($requestData[optParamName($val['param_column'])] == "-- ::") $requestData[optParamName($val['param_column'])] = "";
			}
		}
		
		// エラーチェック
		if ($val['param_type'] >= 1 && $val['param_type'] <= 3 || $val['param_type'] == "15" || $val['param_type'] == "17" || $val['param_type'] == "13" || $val['param_type'] == "19") {
			$errMsg = array_merge($errMsg,
				checkDataType(
					$requestData,
					$arrParam,
					array(
						optParamName($val['param_column']) => "digitE",
					)
				)
			);
		}
		if ($val['param_type'] == "9") {
			$errMsg = array_merge($errMsg,
				checkDataType(
					$requestData,
					$arrParam,
					array(
						optParamName($val['param_column']) => "dateE",
					)
				)
			);
		}
		if ($val['param_type'] == "10") {
			$errMsg = array_merge($errMsg,
				checkDataType(
					$requestData,
					$arrParam,
					array(
						optParamName($val['param_column']) => "timestampE",
					)
				)
			);
		}
		
		// 長さチェック
		if ($val['param_type'] >= 1 && $val['param_type'] <= 3 || $val['param_type'] == "13" || $val['param_type'] == "15" || $val['param_type'] == "17" || $val['param_type'] == "19") {
			$errMsg = array_merge($errMsg,
				checkLength(
					$requestData,
					$arrParam,
					array(
						optParamName($val['param_column']) => "255",
					),
					array(
					)
				)
			);
		}
		if ($val['param_type'] == "4" || $val['param_type'] == "5" || $val['param_type'] == "16" || $val['param_type'] == "18") {
			$errMsg = array_merge($errMsg,
				checkLength(
					$requestData,
					$arrParam,
					array(
						optParamName($val['param_column']) => "255",
					),
					array(
					)
				)
			);
		}
		if ($val['param_type'] == "6" || $val['param_type'] == "7" || $val['param_type'] == "8" || $val['param_type'] == "14") {
			$errMsg = array_merge($errMsg,
				checkLength(
					$requestData,
					$arrParam,
					array(
						optParamName($val['param_column']) => "240000",
					),
					array(
					)
				)
			);
		}
		if ($val['param_type'] == "11" && isset($_FILES[optParamName($val['param_column'])]) && $_FILES[optParamName($val['param_column'])]['tmp_name'] != "" && $compFlg == false) {
			$expFile = getExpArray($_FILES[optParamName($val['param_column'])]['name']);
			if (strtolower($expFile[1]) != "gif" && strtolower($expFile[1]) != "jpg" && strtolower($expFile[1]) != "jpeg" && strtolower($expFile[1]) != "png" && strtolower($expFile[1]) != "bmp") {
				$errMsg[] = mb_convert_encoding("ファイルの形式が正しくありません", APP_ENC, "UTF-8");
			}
		}
		if ($val['param_type'] == "12" && isset($_FILES[optParamName($val['param_column'])]) && $_FILES[optParamName($val['param_column'])]['tmp_name'] != "" && $compFlg == false) {
			$expFile = getExpArray($_FILES[optParamName($val['param_column'])]['name']);
		}
		if ($val['param_type'] == "20" && $requestData[optParamName($val['param_column'])] != $requestData["_".optParamName($val['param_column'])]) {
			$errMsg[] = mb_convert_encoding($val['param_name']."が確認用と一致しておりません", APP_ENC, "UTF-8");
		}

		if (is_numeric($val['param_unique'])) {
			$infoSystem = $objDB->findByIdData('system', $val['system_id']);
			$existsDeleteFlg = false;
			foreach ($resParam as $flgCheck) if ($flgCheck['param_column'] == "delete_flg") $existsDeleteFlg = true;
			$whereArray = array($val['param_column']." = ?");
			if ($existsDeleteFlg == true) $whereArray[] = "delete_flg = 0";
			$paramArray = array($requestData[optParamName($val['param_column'])]);
			if (isset($requestData['id']) && is_numeric($requestData['id'])) {
				$whereArray[] = $infoSystem['system_table']."_id != ?";
				$paramArray[] = $requestData['id'];
			}
			$infoUnique = $objDB->findRowData($infoSystem['system_table'], array("where" => $whereArray, "param" => $paramArray));
			if (count($infoUnique) > 0) $errMsg[] = mb_convert_encoding($val['param_name']."が既に登録されております", APP_ENC, "UTF-8");
		}
		
		// 処理
		if (count($errMsg) == 0) {
			if ($val['param_type'] == "11" && isset($_FILES[optParamName($val['param_column'])]) && $_FILES[optParamName($val['param_column'])]['tmp_name'] != "" && $compFlg == false || $val['param_type'] == "11" && $requestData[optParamName($val['param_column'])] != "" && $compFlg == true) {
				if ($compFlg == false) {
					$randstr = md5(date("sHimYd").uniqid("", true));
					uploadFileCms($_FILES[optParamName($val['param_column'])]['tmp_name'], UPLOAD_FILE_TEMP_DIR.$randstr.".".$expFile[1], IMAGE_HEIGHT, IMAGE_WIDTH);
					if (defined('IMAGE_MINI_FLG') && IMAGE_MINI_FLG == true) {
						uploadFileCms(UPLOAD_FILE_TEMP_DIR.$randstr.".".$expFile[1], UPLOAD_FILE_TEMP_DIR."m".$randstr.".".$expFile[1], IMAGE_M_HEIGHT, IMAGE_M_WIDTH);
					}
					$requestData[optParamName($val['param_column'])] = $randstr.".".$expFile[1];
				} else {
					$tmpFilePath = $requestData[optParamName($val['param_column'])];
					$requestData[optParamName($val['param_column'])] = date("Ym")."/".$requestData[optParamName($val['param_column'])];
					if (!is_dir(UPLOAD_FILE_DIR.date("Ym"))) {
						mkdir(UPLOAD_FILE_DIR.date("Ym"));
						chmod(UPLOAD_FILE_DIR.date("Ym"), 0777);
					}
					if (IMAGE_MINI_FLG == true) {
						if (!is_dir(UPLOAD_FILE_DIR."m".date("Ym"))) {
							mkdir(UPLOAD_FILE_DIR."m".date("Ym"));
							chmod(UPLOAD_FILE_DIR."m".date("Ym"), 0777);
						}
					}
					copy(UPLOAD_FILE_TEMP_DIR.$tmpFilePath, UPLOAD_FILE_DIR.$requestData[optParamName($val['param_column'])]);
					unlink(UPLOAD_FILE_TEMP_DIR.$tmpFilePath);
					if (IMAGE_MINI_FLG == true) {
						copy(UPLOAD_FILE_TEMP_DIR."m".$tmpFilePath, UPLOAD_FILE_DIR."m".$requestData[optParamName($val['param_column'])]);
						unlink(UPLOAD_FILE_TEMP_DIR."m".$tmpFilePath);
					}
				}
			}
			
			if ($val['param_type'] == "12" && isset($_FILES[optParamName($val['param_column'])]) && $_FILES[optParamName($val['param_column'])]['tmp_name'] != "" && $compFlg == false || $val['param_type'] == "12" && isset($requestData[optParamName($val['param_column'])]) && $requestData[optParamName($val['param_column'])] != "" && $compFlg == true) {
				if ($compFlg == false) {
					$randstr = md5(date("sHimYd").uniqid("", true));
					move_uploaded_file($_FILES[optParamName($val['param_column'])]['tmp_name'], UPLOAD_FILE_TEMP_DIR.$randstr.".".$expFile[1]);
					$requestData[optParamName($val['param_column'])] = $randstr.".".$expFile[1];
				} else {
					$tmpFilePath = $requestData[optParamName($val['param_column'])];
					$requestData[optParamName($val['param_column'])] = date("Ym")."/".$requestData[optParamName($val['param_column'])];
					if (!is_dir(UPLOAD_FILE_DIR.date("Ym"))) {
						mkdir(UPLOAD_FILE_DIR.date("Ym"));
						chmod(UPLOAD_FILE_DIR.date("Ym"), 0777);
					}
					copy(UPLOAD_FILE_TEMP_DIR.$tmpFilePath, UPLOAD_FILE_DIR.$requestData[optParamName($val['param_column'])]);
					unlink(UPLOAD_FILE_TEMP_DIR.$tmpFilePath);
				}
			}

			if ($val['param_type'] == "20" && $compFlg == true) {
				if ($requestData[optParamName($val['param_column'])]) {
					$requestData[optParamName($val['param_column'])] = password_hash($requestData[optParamName($val['param_column'])], PASSWORD_DEFAULT);
				} else {
					unset($requestData[optParamName($val['param_column'])]);
				}
			}
		}
	}

	return $errMsg;
}

function userCmsAddVal(&$requestData, $arrParam, $cmsId, $fileupload = false) {
	$errMsg = array();
	$objDB = DBD_Query::singleQuery();

	$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($cmsId)."'"));

	foreach ($resParam as $key => $val) {
		if ($val['param_column'] == 'stop_flg' || $val['param_column'] == 'delete_flg' || $val['param_column'] == 'insert_datetime' || $val['param_column'] == 'update_datetime') continue;

		if ($val['param_type'] == "1" && $val['param_type'] == "2" && $val['param_type'] == "3") {
			// 文字サポート
			$requestData = applyTrim(
				$requestData,
				array(
					optParamName($val['param_column'])
				)
			);
			$requestData = applyKana(
				$requestData,
				array(
					optParamName($val['param_column']) => "a",
				)
			);
		}
		if ($val['param_type'] == "9") {
			if (isset($requestData[optParamName($val['param_column'])."_y"]) || isset($requestData[optParamName($val['param_column'])."_m"]) || isset($requestData[optParamName($val['param_column'])."_d"])) {				$requestData[optParamName($val['param_column'])] = $requestData[optParamName($val['param_column'])."_y"]."-".$requestData[optParamName($val['param_column'])."_m"]."-".$requestData[optParamName($val['param_column'])."_d"];
				$requestData[optParamName($val['param_column'])] = $requestData[optParamName($val['param_column'])."_y"]."-".$requestData[optParamName($val['param_column'])."_m"]."-".$requestData[optParamName($val['param_column'])."_d"];
				if ($requestData[optParamName($val['param_column'])] == "--") $requestData[optParamName($val['param_column'])] = "";
			}
		}
		if ($val['param_type'] == "10") {
			if (isset($requestData[optParamName($val['param_column'])."_y"]) || isset($requestData[optParamName($val['param_column'])."_m"]) || isset($requestData[optParamName($val['param_column'])."_d"]) || isset($requestData[optParamName($val['param_column'])."_h"]) || isset($requestData[optParamName($val['param_column'])."_i"]) || isset($requestData[optParamName($val['param_column'])."_s"])) {
				$requestData[optParamName($val['param_column'])] = $requestData[optParamName($val['param_column'])."_y"]."-".$requestData[optParamName($val['param_column'])."_m"]."-".$requestData[optParamName($val['param_column'])."_d"]." ".$requestData[optParamName($val['param_column'])."_h"].":".$requestData[optParamName($val['param_column'])."_i"].":".$requestData[optParamName($val['param_column'])."_s"];
				if ($requestData[optParamName($val['param_column'])] == "-- ::") $requestData[optParamName($val['param_column'])] = "";
			}
		}

		// エラーチェック
		if ($val['param_validate'] != "") {
			if (isset($requestData['id']) && is_numeric($requestData['id']) && $val['param_type'] == "20" && !$requestData[optParamName($val['param_column'])]) {
			} else {
				$errMsg = array_merge($errMsg,
					checkDataType(
						$requestData,
						$arrParam,
						array(
							optParamName($val['param_column']) => $val['param_validate'],
						)
					)
				);
			}
		} else {
			if ($val['param_type'] >= 1 && $val['param_type'] <= 3 || $val['param_type'] == "15" || $val['param_type'] == "17" || $val['param_type'] == "13" || $val['param_type'] == "19") {
				$errMsg = array_merge($errMsg,
					checkDataType(
						$requestData,
						$arrParam,
						array(
							optParamName($val['param_column']) => "digitE",
						)
					)
				);
			}
			if ($val['param_type'] == "9") {
				$errMsg = array_merge($errMsg,
					checkDataType(
						$requestData,
						$arrParam,
						array(
							optParamName($val['param_column']) => "dateE",
						)
					)
				);
			}
			if ($val['param_type'] == "10") {
				$errMsg = array_merge($errMsg,
					checkDataType(
						$requestData,
						$arrParam,
						array(
							optParamName($val['param_column']) => "timestampE",
						)
					)
				);
			}
		}
		
		// 長さチェック
		if (isset($requestData[optParamName($val['param_column'])]) && !is_array($requestData[optParamName($val['param_column'])]) && strlen($requestData[optParamName($val['param_column'])]) > 0) {
			if ($val['param_max'] != "" || $val['param_min'] != "") {
				$maxArray = $minArray = array();
				if (is_numeric($val['param_max'])) {
					$maxArray[optParamName($val['param_column'])] = $val['param_max'];
				} else {
					$maxArray = array();
				}
				if (is_numeric($val['param_min'])) {
					$minArray[optParamName($val['param_column'])] = $val['param_min'];
				} else {
					$minArray = array();
				}

				$errMsg = array_merge($errMsg,
					checkLength(
						$requestData,
						$arrParam,
						$maxArray,
						$minArray
					)
				);
			} else {
				if ($val['param_type'] >= 1 && $val['param_type'] <= 3 || $val['param_type'] == "15" || $val['param_type'] == "17" || $val['param_type'] == "13" || $val['param_type'] == "19") {
					$errMsg = array_merge($errMsg,
						checkLength(
							$requestData,
							$arrParam,
							array(
								optParamName($val['param_column']) => "255",
							),
							array(
							)
						)
					);
				}
				if ($val['param_type'] == "4" || $val['param_type'] == "5" || $val['param_type'] == "16" || $val['param_type'] == "18") {
					$errMsg = array_merge($errMsg,
						checkLength(
							$requestData,
							$arrParam,
							array(
								optParamName($val['param_column']) => "255",
							),
							array(
							)
						)
					);
				}
				if ($val['param_type'] == "6" || $val['param_type'] == "7" || $val['param_type'] == "8" || $val['param_type'] == "14") {
					$errMsg = array_merge($errMsg,
						checkLength(
							$requestData,
							$arrParam,
							array(
								optParamName($val['param_column']) => "240000",
							),
							array(
							)
						)
					);
				}
			}
		}

		if ($val['param_type'] == "11" && isset($_FILES[optParamName($val['param_column'])]) && $_FILES[optParamName($val['param_column'])]['tmp_name'] != "") {
			$expFile = getExpArray($_FILES[optParamName($val['param_column'])]['name']);
			if (strtolower($expFile[1]) != "gif" && strtolower($expFile[1]) != "jpg" && strtolower($expFile[1]) != "jpeg" && strtolower($expFile[1]) != "png" && strtolower($expFile[1]) != "bmp") {
				$errMsg[] = mb_convert_encoding("ファイルの形式が正しくありません", APP_ENC, "UTF-8");
			}
		}
		if ($val['param_type'] == "12" && isset($_FILES[optParamName($val['param_column'])]) && $_FILES[optParamName($val['param_column'])]['tmp_name'] != "") {
			$expFile = getExpArray($_FILES[optParamName($val['param_column'])]['name']);
		}
		
		// ファイルアップロード
		if ($fileupload != false) {
			if (count($errMsg) == 0) {
				if ($val['param_type'] == "11" && $_FILES[optParamName($val['param_column'])]['tmp_name'] != "" && $compFlg == false || $val['param_type'] == "11" && $requestData[optParamName($val['param_column'])] != "" && $compFlg == true) {
					if ($compFlg == false) {
						$randstr = md5(date("sHimYd").uniqid("", true));
						uploadFileCms($_FILES[optParamName($val['param_column'])]['tmp_name'], UPLOAD_FILE_TEMP_DIR.$randstr.".".$expFile[1], IMAGE_HEIGHT, IMAGE_WIDTH);
//						uploadFileCms($_FILES[optParamName($val['param_column'])]['tmp_name'], UPLOAD_FILE_TEMP_DIR."m".$randstr.".".$expFile[1], IMAGE_M_HEIGHT, IMAGE_M_WIDTH);
						$requestData[optParamName($val['param_column'])] = $randstr.".".$expFile[1];
					} else {
						$tmpFilePath = $requestData[optParamName($val['param_column'])];
						$requestData[optParamName($val['param_column'])] = date("Ym")."/".$requestData[optParamName($val['param_column'])];
						if (!is_dir(UPLOAD_FILE_DIR.date("Ym"))) {
							mkdir(UPLOAD_FILE_DIR.date("Ym"));
							chmod(UPLOAD_FILE_DIR.date("Ym"), 0777);
						}
						copy(UPLOAD_FILE_TEMP_DIR.$tmpFilePath, UPLOAD_FILE_DIR.$requestData[optParamName($val['param_column'])]);
//						copy(UPLOAD_FILE_TEMP_DIR."m".$requestData[optParamName($val['param_column'])], UPLOAD_FILE_DIR."m".$requestData[optParamName($val['param_column'])]);
						unlink(UPLOAD_FILE_TEMP_DIR.$tmpFilePath);
					}
				}
				
				if ($val['param_type'] == "12" && $_FILES[optParamName($val['param_column'])]['tmp_name'] != "" && $compFlg == false || $val['param_type'] == "12" && $requestData[optParamName($val['param_column'])] != "" && $compFlg == true) {
					if ($compFlg == false) {
						$randstr = md5(date("sHimYd").uniqid("", true));
						move_uploaded_file($_FILES[optParamName($val['param_column'])]['tmp_name'], UPLOAD_FILE_TEMP_DIR.$randstr.".".$expFile[1]);
						$requestData[optParamName($val['param_column'])] = $randstr.".".$expFile[1];
					} else {
						$tmpFilePath = $requestData[optParamName($val['param_column'])];
						$requestData[optParamName($val['param_column'])] = date("Ym")."/".$requestData[optParamName($val['param_column'])];
						if (!is_dir(UPLOAD_FILE_DIR.date("Ym"))) {
							mkdir(UPLOAD_FILE_DIR.date("Ym"));
							chmod(UPLOAD_FILE_DIR.date("Ym"), 0777);
						}
						copy(UPLOAD_FILE_TEMP_DIR.$tmpFilePath, UPLOAD_FILE_DIR.$requestData[optParamName($val['param_column'])]);
						unlink(UPLOAD_FILE_TEMP_DIR.$tmpFilePath);
					}
				}
			}
		}
	}

	return $errMsg;
}

?>