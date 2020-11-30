<?php

function getTableName($systemId) {
	if (is_numeric($systemId)) {
		$infoSystem = findByIdSystem($systemId);
		if ($infoSystem['system_table'] == "") {
			return DB_TABLE_HEADER."data".$systemId;
		} else {
			return $infoSystem['system_table'];
		}
	} else {
		$tableName = $systemId;
	}
	return $tableName;
}

function getColumn($systemId) {
	$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".optSql($systemId)."'"));
	
	$column = array();
	foreach ($resParam as $key => $val) {
		$column[] = optParamName($val['param_column']);
	}
	
	return $column;
}

function getIdColumn($systemId) {
	$tableName = getTableName($systemId);
	$expTableName = explode('___', $tableName);
	$column = $expTableName[(count($expTableName) - 1)].'_id';

	return $column;
}

function optParamName($param) {
	return str_replace(DB_COLUMN_HEADER."", "", $param);
}

function paramFormType($infoParam, $requestData) {
	$formType = '';
	if (!isset($requestData[optParamName($infoParam['param_column'])])) $requestData[optParamName($infoParam['param_column'])] = '';

	switch ($infoParam['param_type']) {
		// 数値（入力型）
		case 1:
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'" value="'.$requestData[optParamName($infoParam['param_column'])].'" size="20" id="'.optParamName($infoParam['param_column']).'" />';
			break;
		// 数値(単一選択型:セレクト)
		case 2:
			$formType.= '<select name="'.optParamName($infoParam['param_column']).'" id="'.optParamName($infoParam['param_column']).'">';
			$formType.= '<option value="0"></option>';
			foreach (explode("\n", $infoParam['param_info']) as $key => $val) {
				$val = trim($val);
				if (strpos($val, "::") !== false) {
					$expVal = explode("::", $val);
					$name = $expVal[0];
					$val = $expVal[1];
				} else {
					$name = $val;
				}
				$formType.= '<option value="'.escapeHtml($val).'"';
				if ($requestData[optParamName($infoParam['param_column'])] == $val) $formType.= ' selected ';
				$formType.= '>'.escapeHtml($name).'</option>';
			}
			$formType.= '</select>';
			break;
		// 数値(単一選択型:ラジオ)
		case 15:
			foreach (explode("\n", $infoParam['param_info']) as $key => $val) {
				$val = trim($val);
				if (strpos($val, "::") !== false) {
					$expVal = explode("::", $val);
					$name = $expVal[0];
					$val = $expVal[1];
				} else {
					$name = $val;
				}
				$formType.= '<input type="radio" name="'.optParamName($infoParam['param_column']).'" value="'.escapeHtml($val).'"';
				if ($requestData[optParamName($infoParam['param_column'])] == $val) $formType.= ' checked ';
				$formType.= ' class="'.optParamName($infoParam['param_column']).'" id="'.optParamName($infoParam['param_column'].$key).'">'.escapeHtml($name);
				$formType.= '<br />';
			}
			break;
		// 数値(hidden型)
		case 17:
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'" value="'.escapeHtml($requestData[optParamName($infoParam['param_column'])]).'" size="20" id="'.optParamName($infoParam['param_column']).'" />';
			break;
		// 小数点有数値
		case 3:
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'" value="'.escapeHtml($requestData[optParamName($infoParam['param_column'])]).'" size="20" id="'.optParamName($infoParam['param_column']).'" />';
			break;
		// 文字列(入力型)
		case 4:
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'" value="'.escapeHtml($requestData[optParamName($infoParam['param_column'])]).'" size="40" id="'.optParamName($infoParam['param_column']).'" />';
			break;
		// 文字列(単一選択型:セレクト)
		case 5:
			$formType.= '<select name="'.optParamName($infoParam['param_column']).'" id="'.optParamName($infoParam['param_column']).'">';
			$formType.= '<option value=""></option>';
			foreach (explode("\n", $infoParam['param_info']) as $key => $val) {
				$val = trim($val);
				if (strpos($val, "::") !== false) {
					$expVal = explode("::", $val);
					$name = $expVal[0];
					$val = $expVal[1];
				} else {
					$name = $val;
				}
				$formType.= '<option value="'.escapeHtml($val).'"';
				if ($requestData[optParamName($infoParam['param_column'])] == $val) $formType.= ' selected ';
				$formType.= ' class="'.optParamName($infoParam['param_column']).'" id="'.optParamName($infoParam['param_column'].$key).'">'.escapeHtml($name).'</option>';
			}
			$formType.= '</select>';
			break;
		// 文字列(単一選択型:ラジオ)
		case 16:
			foreach (explode("\n", $infoParam['param_info']) as $key => $val) {
				$val = trim($val);
				if (strpos($val, "::") !== false) {
					$expVal = explode("::", $val);
					$name = $expVal[0];
					$val = $expVal[1];
				} else {
					$name = $val;
				}
				$formType.= '<input type="radio" name="'.optParamName($infoParam['param_column']).'" value="'.escapeHtml($val).'"';
				if ($requestData[optParamName($infoParam['param_column'])] == $val) $formType.= ' checked ';
				$formType.= ' class="'.optParamName($infoParam['param_column']).'" id="'.optParamName($infoParam['param_column'].$key).'">'.escapeHtml($name);
				$formType.= '<br />';
			}
			break;
		// 文字列(hidden型)
		case 18:
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'" value="'.$requestData[optParamName($infoParam['param_column'])].'" size="20" id="'.optParamName($infoParam['param_column']).'" />';
			break;
		// 文字列(パスワード型)
		case 20:
			$formType.= '<input type="password" name="'.optParamName($infoParam['param_column']).'" value="" size="40" id="'.optParamName($infoParam['param_column']).'" /><br>';
			$formType.= '※確認用にもう一度入力してください<br>';
			$formType.= '<input type="password" name="_'.optParamName($infoParam['param_column']).'" value="" size="40" id="'.optParamName("_".$infoParam['param_column']).'" />';
			break;
		// テキスト(入力型)
		case 6:
			$formType.= '<textarea name="'.optParamName($infoParam['param_column']).'" cols="60" rows="8" id="'.optParamName($infoParam['param_column']).'">'.$requestData[optParamName($infoParam['param_column'])].'</textarea>';
			break;
		// テキスト(単一選択型:セレクト)
		case 7:
			$formType.= '<select name="'.optParamName($infoParam['param_column']).'" id="'.optParamName($infoParam['param_column']).'">';
			$formType.= '<option value=""></option>';
			foreach (explode("\n", $infoParam['param_info']) as $key => $val) {
				$val = trim($val);
				if (strpos($val, "::") !== false) {
					$expVal = explode("::", $val);
					$name = $expVal[0];
					$val = $expVal[1];
				} else {
					$name = $val;
				}
				$formType.= '<option value="'.escapeHtml($val).'"';
				if ($requestData[optParamName($infoParam['param_column'])] == $val) $formType.= ' selected ';
				$formType.= '>'.escapeHtml($name).'</option>';
			}
			$formType.= '</select>';
			break;
		// テキスト(複数選択型)
		case 8:
			$formType.= '<input type="hidden"  name="'.optParamName($infoParam['param_column']).'" value="">';
			foreach (explode("\n", $infoParam['param_info']) as $key => $val) {
				$val = trim($val);
				if (strpos($val, "::") !== false) {
					$expVal = explode("::", $val);
					$name = $expVal[0];
					$val = $expVal[1];
				} else {
					$name = $val;
				}
				$formType.= '<input type="checkbox" name="'.optParamName($infoParam['param_column']).'[]" value="'.escapeHtml($val).'"';
				if (is_array($requestData[optParamName($infoParam['param_column'])]) && in_array($val, $requestData[optParamName($infoParam['param_column'])]) !== false) $formType.= ' checked ';
				$formType.= ' class="'.optParamName($infoParam['param_column']).'" id="'.optParamName($infoParam['param_column'].$key).'">'.$name;
				$formType.= '<br />';
			}
			break;
		// テキスト(wysiwyg型)
		case 14:
			$formType.= '<textarea name="'.optParamName($infoParam['param_column']).'" id="'.optParamName($infoParam['param_column']).'" cols="60" rows="15" class="nicwys">'.$requestData[optParamName($infoParam['param_column'])].'</textarea>';
			break;
		// 日付
		case 9:
			if (!isset($requestData[optParamName($infoParam['param_column']).'_y'])) $requestData[optParamName($infoParam['param_column']).'_y'] = '';
			if (!isset($requestData[optParamName($infoParam['param_column']).'_m'])) $requestData[optParamName($infoParam['param_column']).'_m'] = '';
			if (!isset($requestData[optParamName($infoParam['param_column']).'_d'])) $requestData[optParamName($infoParam['param_column']).'_d'] = '';
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'_y" value="'.$requestData[optParamName($infoParam['param_column'])."_y"].'" size="4" maxlength="4" id="'.optParamName($infoParam['param_column']).'_y" />';
			$formType.= '年';
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'_m" value="'.$requestData[optParamName($infoParam['param_column'])."_m"].'" size="2" maxlength="2" id="'.optParamName($infoParam['param_column']).'_m" />';
			$formType.= '月';
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'_d" value="'.$requestData[optParamName($infoParam['param_column'])."_d"].'" size="2" maxlength="2" id="'.optParamName($infoParam['param_column']).'_d" />';
			$formType.= '日';
			break;
		// タイムスタンプ
		case 10:
			if (!isset($requestData[optParamName($infoParam['param_column']).'_y'])) $requestData[optParamName($infoParam['param_column']).'_y'] = '';
			if (!isset($requestData[optParamName($infoParam['param_column']).'_m'])) $requestData[optParamName($infoParam['param_column']).'_m'] = '';
			if (!isset($requestData[optParamName($infoParam['param_column']).'_d'])) $requestData[optParamName($infoParam['param_column']).'_d'] = '';
			if (!isset($requestData[optParamName($infoParam['param_column']).'_h'])) $requestData[optParamName($infoParam['param_column']).'_h'] = '';
			if (!isset($requestData[optParamName($infoParam['param_column']).'_i'])) $requestData[optParamName($infoParam['param_column']).'_i'] = '';
			if (!isset($requestData[optParamName($infoParam['param_column']).'_s'])) $requestData[optParamName($infoParam['param_column']).'_s'] = '';
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'_y" value="'.$requestData[optParamName($infoParam['param_column'])."_y"].'" size="4" maxlength="4" id="'.optParamName($infoParam['param_column']).'_y" />';
			$formType.= '年';
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'_m" value="'.$requestData[optParamName($infoParam['param_column'])."_m"].'" size="2" maxlength="2" id="'.optParamName($infoParam['param_column']).'_m" />';
			$formType.= '月';
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'_d" value="'.$requestData[optParamName($infoParam['param_column'])."_d"].'" size="2" maxlength="2" id="'.optParamName($infoParam['param_column']).'_d" />';
			$formType.= '日';
			$formType.= '&nbsp;';
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'_h" value="'.$requestData[optParamName($infoParam['param_column'])."_h"].'" size="2" maxlength="2" id="'.optParamName($infoParam['param_column']).'_h" />';
			$formType.= '時';
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'_i" value="'.$requestData[optParamName($infoParam['param_column'])."_i"].'" size="2" maxlength="2" id="'.optParamName($infoParam['param_column']).'_i" />';
			$formType.= '分';
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'_s" value="'.$requestData[optParamName($infoParam['param_column'])."_s"].'" size="2" maxlength="2" id="'.optParamName($infoParam['param_column']).'_s" />';
			$formType.= '秒';
			break;
		// 画像
		case 11:
			$formType.= '<input type="file" name="'.optParamName($infoParam['param_column']).'" value="'.$requestData[optParamName($infoParam['param_column']).""].'" id="'.optParamName($infoParam['param_column']).'" />';
			break;
		// ファイル
		case 12:
			$formType.= '<input type="file" name="'.optParamName($infoParam['param_column']).'" id="'.optParamName($infoParam['param_column']).'" />';
			break;
		// 外部キー(単一選択型:セレクト)
		case 13:
			$tmpSystem = findByIdSystem(trim($infoParam['param_info']));
			$tmpOrder = ($tmpSystem['system_list_sort'] != "") ? array($tmpSystem['system_list_sort']) : array();
			$tmpData = findEZData(trim($infoParam['param_info']), array(), $tmpOrder);
			$tmpParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".optSql(trim($infoParam['param_info']))."'"), array("param_sort", "param_id"), 1);
			
			$formType.= '<select name="'.optParamName($infoParam['param_column']).'" id="'.optParamName($infoParam['param_column']).'">';
			$formType.= '<option value="0"></option>';
			foreach ($tmpData as $key => $val) {
				$formType.= '<option value="'.escapeHtml($val[$tmpSystem['system_table'].'_id']).'"';
				if ($requestData[optParamName($infoParam['param_column'])] == $val[$tmpSystem['system_table'].'_id']) $formType.= ' selected ';
				$formType.= '>'.escapeHtml($val[$tmpParam[0]['param_column']]).' (ID:'.$val[$tmpSystem['system_table'].'_id'].')</option>';
			}
			$formType.= '</select>';
			break;
		// 外部キー(hidden型:セレクト)
		case 19:
			$tmpSystem = findByIdSystem(trim($infoParam['param_info']));
			$tmpOrder = ($tmpSystem['system_list_sort'] != "") ? array($tmpSystem['system_list_sort']) : array();
			$tmpData = findEZData(trim($infoParam['param_info']), array(), $tmpOrder);
			$tmpParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".optSql(trim($infoParam['param_info']))."'"), array("param_sort", "param_id"), 1);
			
			$formType.= '<select name="'.optParamName($infoParam['param_column']).'" id="'.optParamName($infoParam['param_column']).'">';
			$formType.= '<option value="0"></option>';
			foreach ($tmpData as $key => $val) {
				$formType.= '<option value="'.escapeHtml($val[$tmpSystem['system_table'].'_id']).'"';
				if ($requestData[optParamName($infoParam['param_column'])] == $val[DB_COLUMN_HEADER.'id']) $formType.= ' selected ';
				$formType.= '>'.escapeHtml($val[$tmpParam[0]['param_column']]).'</option>';
			}
			$formType.= '</select>';
			break;
	}
	// 追加フォーム
	if (trim($infoParam['param_system_note']) != "") {
		$formType = str_replace("{form}", $formType, $infoParam['param_system_note']);
		$formType = str_replace("{name}", optParamName($infoParam['param_column']), $formType);
		$formType = str_replace("{value}", $requestData[optParamName($infoParam['param_column'])], $formType);
		
		if (strpos($infoParam['param_system_note'], "{checked:") !== false) {
			preg_match_all("/\{checked:.*?\}/is", $infoParam['param_system_note'], $match);
			foreach ($match[0] as $key => $val) {
				$formVal = str_replace("{checked:", "", str_replace("}", "", $val));
				if ($requestData[optParamName($infoParam['param_column'])] == $formVal) {
					$formType = str_replace($val, "checked", $formType);
				} else {
					$formType = str_replace($val, "", $formType);
				}
			}
		}
		if (strpos($infoParam['param_system_note'], "{selected:") !== false) {
			preg_match_all("/\{selected:.*?\}/is", $infoParam['param_system_note'], $match);
			foreach ($match[0] as $key => $val) {
				$formVal = str_replace("{selected:", "", str_replace("}", "", $val));
				if ($requestData[optParamName($infoParam['param_column'])] == $formVal) {
					$formType = str_replace($val, "selected", $formType);
				} else {
					$formType = str_replace($val, "", $formType);
				}
			}
		}
		if (strpos($infoParam['param_system_note'], "{array_checked:") !== false) {
			preg_match_all("/\{array_checked:.*?\}/is", $infoParam['param_system_note'], $match);
			foreach ($match[0] as $key => $val) {
				$formVal = str_replace("{array_checked:", "", str_replace("}", "", $val));
				if (is_array($requestData[optParamName($infoParam['param_column'])]) && in_array($formVal, $requestData[optParamName($infoParam['param_column'])])) {
					$formType = str_replace($val, "checked", $formType);
				} else {
					$formType = str_replace($val, "", $formType);
				}
			}
		}
	}
	
	return $formType;
}

function makeParamFormType($infoParam) {
	$formType = '';
	switch ($infoParam['param_type']) {
		// 数値（入力型）
		case 1:
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'" value="<?php echo escapeHtml($'.optParamName($infoParam['param_column']).') ?>" size="20" maxlength="100" />';
			break;
		// 数値(単一選択型:セレクト)
		case 2:
			$formType.= '<select name="'.optParamName($infoParam['param_column']).'">';
			$formType.= '<option value=""></option>';
			foreach (explode("\n", $infoParam['param_info']) as $key => $val) {
				$val = trim($val);
				if (strpos($val, "::") !== false) {
					$expVal = explode("::", $val);
					$name = $expVal[0];
					$val = $expVal[1];
				} else {
					$name = $val;
				}
				$formType.= '<option value="'.escapeHtml($val).'" <?php if ($'.optParamName($infoParam['param_column']).' == \''.escapeHtml($val).'\') echo "selected"; ?>>'.escapeHtml($name).'</option>';
			}
			$formType.= '</select>';
			break;
		// 数値(単一選択型:ラジオ)
		case 15:
			foreach (explode("\n", $infoParam['param_info']) as $key => $val) {
				$val = trim($val);
				if (strpos($val, "::") !== false) {
					$expVal = explode("::", $val);
					$name = $expVal[0];
					$val = $expVal[1];
				} else {
					$name = $val;
				}
				$formType.= '<input type="radio" name="'.optParamName($infoParam['param_column']).'" value="'.escapeHtml($val).'" <?php if ($'.optParamName($infoParam['param_column']).' == \''.escapeHtml($val).'\') echo "checked"; ?>>'.escapeHtml($name).'<br />';
			}
			break;
		// 数値(hidden型)
		case 17:
			$formType.= '<?php echo escapeHtml($'.optParamName($infoParam['param_column']).') ?><input type="hidden" name="'.optParamName($infoParam['param_column']).'" value="<?php echo escapeHtml($'.optParamName($infoParam['param_column']).') ?>" />';
			break;
		// 小数点有数値
		case 3:
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'" value="<?php echo escapeHtml($'.optParamName($infoParam['param_column']).') ?>" size="20" maxlength="100" />';
			break;
		// 文字列(入力型)
		case 4:
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'" value="<?php echo escapeHtml($'.optParamName($infoParam['param_column']).') ?>" size="40" maxlength="100" />';
			break;
		// 文字列(単一選択型:セレクト)
		case 5:
			$formType.= '<select name="'.optParamName($infoParam['param_column']).'">';
			$formType.= '<option value=""></option>';
			foreach (explode("\n", $infoParam['param_info']) as $key => $val) {
				$val = trim($val);
				if (strpos($val, "::") !== false) {
					$expVal = explode("::", $val);
					$name = $expVal[0];
					$val = $expVal[1];
				} else {
					$name = $val;
				}
				$formType.= '<option value="'.escapeHtml($val).'" <?php if ($'.optParamName($infoParam['param_column']).' == \''.escapeHtml($val).'\') echo "selected"; ?>>'.escapeHtml($name).'</option>';
			}
			$formType.= '</select>';
			break;
		// 文字列(単一選択型:ラジオ)
		case 16:
			foreach (explode("\n", $infoParam['param_info']) as $key => $val) {
				$val = trim($val);
				if (strpos($val, "::") !== false) {
					$expVal = explode("::", $val);
					$name = $expVal[0];
					$val = $expVal[1];
				} else {
					$name = $val;
				}
				$formType.= '<input type="radio" name="'.optParamName($infoParam['param_column']).'" value="'.escapeHtml($val).'" <?php if ($'.optParamName($infoParam['param_column']).' == \''.escapeHtml($val).'\') echo "checked"; ?>>'.escapeHtml($name).'<br />';
			}
			break;
		// 文字列(hidden型)
		case 18:
			$formType.= '<?php echo escapeHtml($'.optParamName($infoParam['param_column']).') ?><input type="hidden" name="'.optParamName($infoParam['param_column']).'" value="<?php echo escapeHtml($'.optParamName($infoParam['param_column']).') ?>" />';
			break;
		// 文字列(パスワード型)
		case 20:
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'" value="<?php echo escapeHtml($'.optParamName($infoParam['param_column']).') ?>" size="40" maxlength="100" />';
			break;
		// テキスト(入力型)
		case 6:
			$formType.= '<textarea name="'.optParamName($infoParam['param_column']).'" cols="60" rows="8"><?php echo escapeHtml($'.optParamName($infoParam['param_column']).') ?></textarea>';
			break;
		// テキスト(単一選択型)
		case 7:
			$formType.= '<select name="'.optParamName($infoParam['param_column']).'">';
			$formType.= '<option value=""></option>';
			foreach (explode("\n", $infoParam['param_info']) as $key => $val) {
				$val = trim($val);
				if (strpos($val, "::") !== false) {
					$expVal = explode("::", $val);
					$name = $expVal[0];
					$val = $expVal[1];
				} else {
					$name = $val;
				}
				$formType.= '<option value="'.escapeHtml($val).'" <?php if ($'.optParamName($infoParam['param_column']).' == \''.escapeHtml($val).'\') echo "selected"; ?>>'.escapeHtml($name).'</option>';
			}
			$formType.= '</select>';
			break;
		// テキスト(複数選択型)
		case 8:
			foreach (explode("\n", $infoParam['param_info']) as $key => $val) {
				$val = trim($val);
				if (strpos($val, "::") !== false) {
					$expVal = explode("::", $val);
					$name = $expVal[0];
					$val = $expVal[1];
				} else {
					$name = $val;
				}
				$formType.= '<input type="checkbox" name="'.optParamName($infoParam['param_column']).'[]" value="'.escapeHtml($val).'" <?php if (is_array($'.optParamName($infoParam['param_column']).')) && in_array(\''.escapeHtml($val).'\', $'.optParamName($infoParam['param_column']).') !== false) echo "checked"; ?>>'.$name;
				$formType.= '<br />';
			}
			break;
		// テキスト(wysiwyg型)
		case 14:
			$formType.= '<textarea name="'.optParamName($infoParam['param_column']).'" cols="60" rows="8"><?php echo escapeHtml($'.optParamName($infoParam['param_column']).') ?></textarea>';
			break;
		// 日付
		case 9:
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'_y" value="<?php echo escapeHtml($'.optParamName($infoParam['param_column'])."_y".') ?>" size="4" maxlength="4" />';
			$formType.= '年';
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'_m" value="<?php echo escapeHtml($'.optParamName($infoParam['param_column'])."_m".') ?>" size="2" maxlength="2" />';
			$formType.= '月';
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'_d" value="<?php echo escapeHtml($'.optParamName($infoParam['param_column'])."_d".') ?>" size="2" maxlength="2" />';
			$formType.= '日';
			break;
		// タイムスタンプ
		case 10:
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'_y" value="<?php echo escapeHtml($'.optParamName($infoParam['param_column'])."_y".') ?>" size="4" maxlength="4" />';
			$formType.= '年';
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'_m" value="<?php echo escapeHtml($'.optParamName($infoParam['param_column'])."_m".') ?>" size="2" maxlength="2" />';
			$formType.= '月';
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'_d" value="<?php echo escapeHtml($'.optParamName($infoParam['param_column'])."_d".') ?>" size="2" maxlength="2" />';
			$formType.= '日';
			$formType.= '&nbsp;';
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'_h" value="<?php echo escapeHtml($'.optParamName($infoParam['param_column'])."_h".') ?>" size="2" maxlength="2" />';
			$formType.= '時';
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'_i" value="<?php echo escapeHtml($'.optParamName($infoParam['param_column'])."_i".') ?>" size="2" maxlength="2" />';
			$formType.= '分';
			$formType.= '<input type="text" name="'.optParamName($infoParam['param_column']).'_s" value="<?php echo escapeHtml($'.optParamName($infoParam['param_column'])."_s".') ?>" size="2" maxlength="2" />';
			$formType.= '秒';
			break;
		// 画像
		case 11:
			$formType.= '<input type="file" name="'.optParamName($infoParam['param_column']).'" />';
			break;
		// ファイル
		case 12:
			$formType.= '<input type="file" name="'.optParamName($infoParam['param_column']).'" />';
			break;
		// 外部キー(単一選択型:セレクト)
		case 13:
			$tmpSystem = findByIdSystem(trim($infoParam['param_info']));
			$tmpParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".optSql($tmpSystem['system_id'])."'"), array("param_sort", "param_id"), 1);
			
			$formType.= '<select name="'.optParamName($infoParam['param_column']).'">';
			$formType.= '<option value=""></option>';
			$formType.= '<?php echo selectBoxCms('.$tmpSystem['system_id'].', array(), array("';
			if ($tmpSystem['system_list_sort'] != "") {
				$formType.= $tmpSystem['system_list_sort'];
			} else {
				$formType.= DB_COLUMN_HEADER."id Desc";
			}
			$formType.= '"), DB_COLUMN_HEADER."id", "'.$tmpParam[0]['param_column'].'", $'.optParamName($infoParam['param_column']).') ?>';
			$formType.= '</select>';
			break;
		// 外部キー(hidden型)
		case 19:
			$tmpParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".optSql(trim($infoParam['param_info']))."'"), array("param_sort", "param_id"), 1);

			$formType.= '<?php echo escapeHtml(getBoxCms('.trim($infoParam['param_info']).', DB_COLUMN_HEADER."id", "'.$tmpParam[0]['param_column'].'", $'.optParamName($infoParam['param_column']).')) ?><input type="hidden" name="'.optParamName($infoParam['param_column']).'" value="<?php echo escapeHtml($'.optParamName($infoParam['param_column']).') ?>" />';
			break;
	}
	return $formType;
}

function paramTable($infoParam) {
	$paramTable = array();
	foreach (explode("\n", $infoParam['param_info']) as $key => $val) {
		$val = trim($val);
		if (strpos($val, "::") !== false) {
			$expVal = explode("::", $val);
			$name = $expVal[0];
			$val = $expVal[1];
		} else {
			$name = $val;
		}
		$paramTable[$val] = $name;
	}
	$paramTable[0] = '';
	
	return $paramTable;
}

function outputConfirmData($infoParam, $requestData) {
	$outputType = '';
	if (!isset($requestData[optParamName($infoParam['param_column'])])) $requestData[optParamName($infoParam['param_column'])] = '';

	switch ($infoParam['param_type']) {
		// テキスト(複数選択型)
		case 8:
			$paramTable = paramTable($infoParam);
			$outputData = array();
			if (is_array($requestData[optParamName($infoParam['param_column'])])) {
				foreach ($requestData[optParamName($infoParam['param_column'])] as $val) {
					$outputData[] = $paramTable[$val];
				}
			}
			$outputType.= nl2br(escapeHtml(implode("\n", $outputData)));
			break;
		// テキスト(wysiwyg型)
		case 14:
			$outputType.= $requestData[optParamName($infoParam['param_column'])];
			break;
		// 画像
		case 11:
			if ($requestData[optParamName($infoParam['param_column'])]) {
				$outputType.= '<a href="image_view_tmp.php?f='.$requestData[optParamName($infoParam['param_column'])].'" target="_blank"><img src="image_view_tmp.php?f='.$requestData[optParamName($infoParam['param_column'])].'" width="'.IMAGE_M_WIDTH.'" /></a>';
			}
			break;
		// ファイル
		case 12:
			if ($requestData[optParamName($infoParam['param_column'])]) {
				$outputType.= '<a href="download_file_tmp.php?data='.$requestData[optParamName($infoParam['param_column'])].'" target="_blank">ファイル</a>';
			}
			break;
		// 外部キー(単一選択型:セレクト)
		case 13:
			$tmpSystem = findByIdSystem($infoParam['param_info']);
			$tmpParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".optSql(trim($infoParam['param_info']))."'"), array("param_sort", "param_id"), 1);
			$outputType.= escapeHtml(getBoxCms(trim($infoParam['param_info']), $tmpSystem['system_table']."_id", $tmpParam[0]['param_column'], $requestData[optParamName($infoParam['param_column'])]));
			break;
		// 外部キー(hidden型)
		case 19:
			$tmpSystem = findByIdSystem($infoParam['param_info']);
			$tmpParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".optSql(trim($infoParam['param_info']))."'"), array("param_sort", "param_id"), 1);
			$outputType.= escapeHtml(getBoxCms(trim($infoParam['param_info']), DB_COLUMN_HEADER."_id", $tmpParam[0]['param_column'], $requestData[optParamName($infoParam['param_column'])]));
			break;
		// 選択式
		case 2:
		case 5:
		case 7:
		case 15:
		case 16:
			$paramTable = paramTable($infoParam);
			$outputType.= nl2br(escapeHtml($paramTable[$requestData[optParamName($infoParam['param_column'])]]));
			break;
		case 20 :
			$outputType.= escapeHtml(secretStr($requestData[optParamName($infoParam['param_column'])]));
			break;
		default :
			$outputType.= nl2br(escapeHtml($requestData[optParamName($infoParam['param_column'])]));
			break;
	}

	return $outputType;
}

function outputData($infoParam, $infoData) {
	$outputType = '';
	switch ($infoParam['param_type']) {
		// テキスト(複数選択型)
		case 8:
			$paramTable = paramTable($infoParam);
			$outputData = array();
			if ($infoData[($infoParam['param_column'])]) {
				foreach (unserialize($infoData[($infoParam['param_column'])]) as $val) {
					$outputData[] = $paramTable[$val];
				}
			}
			$outputType.= nl2br(escapeHtml(implode("\n", $outputData)));
			break;
		// テキスト(wysiwyg型)
		case 14:
			$outputType.= $infoData[($infoParam['param_column'])];
			break;
		// 画像
		case 11:
			if ($infoData[($infoParam['param_column'])]) {
				$outputType.= '<a href="'.UPLOAD_FILE_URL.$infoData[($infoParam['param_column'])].'" target="_blank"><img src="'.UPLOAD_FILE_URL.$infoData[($infoParam['param_column'])].'" width="'.IMAGE_M_WIDTH.'" /></a>';
			}
			break;
		// ファイル
		case 12:
			if ($infoData[($infoParam['param_column'])]) {
				$outputType.= '<a href="download_file.php?sid='.$infoParam['system_id'].'&id='.$infoData[DB_COLUMN_HEADER.'id'].'&data='.optParamName($infoParam['param_column']).'" target="_blank">ファイル</a>';
			}
			break;
		// 外部キー(単一選択型:セレクト)
		case 13:
			$tmpSystem = findByIdSystem($infoParam['param_info']);
			$tmpParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".optSql(trim($infoParam['param_info']))."'"), array("param_sort", "param_id"), 1);
			$outputType.= escapeHtml(getBoxCms(trim($infoParam['param_info']), $tmpSystem['system_table']."_id", $tmpParam[0]['param_column'], $infoData[($infoParam['param_column'])]));
			break;
		// 外部キー(hidden型)
		case 19:
			$tmpSystem = findByIdSystem($infoParam['param_info']);
			$tmpParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".optSql(trim($infoParam['param_info']))."'"), array("param_sort", "param_id"), 1);
			$outputType.= escapeHtml(getBoxCms(trim($infoParam['param_info']), $tmpSystem['system_table']."_id", $tmpParam[0]['param_column'], $infoData[($infoParam['param_column'])]));
			break;
		// 選択式
		case 2:
		case 5:
		case 7:
		case 15:
		case 16:
			$paramTable = paramTable($infoParam);
			$outputType.= nl2br(escapeHtml($paramTable[$infoData[($infoParam['param_column'])]]));
			break;
		default :
			$outputType.= nl2br(escapeHtml($infoData[($infoParam['param_column'])]));
			break;
	}
	return $outputType;
}

function makeOutputData($infoParam) {
	$outputType = '';
	switch ($infoParam['param_type']) {
		// テキスト(複数選択型)
		case 8:
			$outputType.= "<?php echo nl2br(escapeHtml(implode(\"\\n\", $".optParamName($infoParam['param_column'])."))) ?>";
			break;
		// テキスト(wysiwyg型)
		case 14:
			$outputType.= "<?php echo nl2br(escapeHtml($".optParamName($infoParam['param_column']).")) ?>";
			break;
		// 画像
		case 11:
			if (GD_UPLOAD == true) {
				$outputType.= '<?php if ($'.optParamName($infoParam['param_column']).' != \'\') { ?><a href="<?php echo escapeHtml(UPLOAD_FILE_TEMP_URL.$'.optParamName($infoParam['param_column']).') ?>" target="_blank"><img src="<?php echo escapeHtml(UPLOAD_FILE_TEMP_URL."m".$'.optParamName($infoParam['param_column']).') ?>" /></a><?php } ?>';
			} else {
				$outputType.= '<?php if ($'.optParamName($infoParam['param_column']).' != \'\') { ?><a href="<?php echo escapeHtml(UPLOAD_FILE_TEMP_URL.$'.optParamName($infoParam['param_column']).') ?>" target="_blank"><img src="<?php echo escapeHtml(UPLOAD_FILE_TEMP_URL.$'.optParamName($infoParam['param_column']).') ?>" width="'.IMAGE_M_WIDTH.'" /></a><?php } ?>';
			}
			break;
		// ファイル
		case 12:
			$outputType.= '<?php if ($'.optParamName($infoParam['param_column']).' != \'\') { ?><a href="<?php echo escapeHtml(UPLOAD_FILE_TEMP_URL.$'.optParamName($infoParam['param_column']).') ?>" target="_blank">ファイル</a><?php } ?>';
			break;
		// 外部キー(単一選択型:セレクト)
		case 13:
			$tmpParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".optSql(trim($infoParam['param_info']))."'"), array("param_sort", "param_id"), 1);
			$outputType.= "<?php echo escapeHtml(getBoxCms(".trim($infoParam['param_info']).", \"".DB_COLUMN_HEADER."id\", \"".$tmpParam[0]['param_column']."\", $".optParamName($infoParam['param_column']).")) ?>";
			break;
		// 外部キー(hidden型)
		case 19:
			$tmpParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".optSql(trim($infoParam['param_info']))."'"), array("param_sort", "param_id"), 1);
			$outputType.= "<?php echo escapeHtml(getBoxCms(".trim($infoParam['param_info']).", \"".DB_COLUMN_HEADER."id\", \"".$tmpParam[0]['param_column']."\", $".optParamName($infoParam['param_column']).")) ?>";
			break;
		default :
			$outputType.= "<?php echo nl2br(escapeHtml($".optParamName($infoParam['param_column']).")) ?>";
			break;
	}
	return $outputType;
}

function listOutputData($infoParam) {
	switch ($infoParam['param_type']) {
		case 8:
			$outputData = '<?php echo outputHtml($val[\''.$infoParam['param_column'].'\'], MULTI) ?>';
			break;
		case 11:
			$outputData = '<?php if ($val[\''.$infoParam['param_column'].'\'] != "") { ?><a href="<?php echo UPLOAD_FILE_URL.outputHtml($val[\''.$infoParam['param_column'].'\'], IMAGE) ?>" target="_blank"><img src="<?php echo UPLOAD_FILE_URL.outputHtml($val[\''.$infoParam['param_column'].'\'], IMAGE_MINI) ?>" /></a><?php } ?>';
			break;
		case 14:
			$outputData = '<?php echo outputHtml($val[\''.$infoParam['param_column'].'\'], HTML) ?>';
			break;
		// ファイル
		case 12:
			$outputData = '<?php if ($val[\''.optParamName($infoParam['param_column']).'\'] != \'\') { ?><a href="<?php echo escapeHtml(UPLOAD_FILE_URL.$val[\''.$infoParam['param_column'].'\']) ?>" target="_blank">ファイル</a><?php } ?>';
			break;
		// 外部キー(単一選択型:セレクト)
		case 13:
			$tmpParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".optSql(trim($infoParam['param_info']))."'"), array("param_sort", "param_id"), 1);
			$outputData = '<?php echo escapeHtml(getBoxCms('.trim($infoParam['param_info']).', "'.DB_COLUMN_HEADER.'id", "'.$tmpParam[0]['param_column'].'", $val[\''.$infoParam['param_column'].'\'])) ?>';
			break;
		// 外部キー(hidden型)
		case 19:
			$tmpParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".optSql(trim($infoParam['param_info']))."'"), array("param_sort", "param_id"), 1);
			$outputData = '<?php echo escapeHtml(getBoxCms('.trim($infoParam['param_info']).', "'.DB_COLUMN_HEADER.'id", "'.$tmpParam[0]['param_column'].'", $val[\''.$infoParam['param_column'].'\'])) ?>';
			break;
		default:
			$outputData = '<?php echo outputHtml($val[\''.$infoParam['param_column'].'\'], SINGLE) ?>';
			break;
	}
	
	return $outputData;
}

function detailOutputData($infoParam) {
	switch ($infoParam['param_type']) {
		case 8:
			$outputData = '<?php echo outputHtml($infoData[\''.$infoParam['param_column'].'\'], MULTI) ?>';
			break;
		case 11:
			$outputData = '<?php if ($infoData[\''.$infoParam['param_column'].'\'] != "") { ?><a href="<?php echo UPLOAD_FILE_URL.outputHtml($infoData[\''.$infoParam['param_column'].'\'], IMAGE) ?>" target="_blank"><img src="<?php echo UPLOAD_FILE_URL.outputHtml($infoData[\''.$infoParam['param_column'].'\'], IMAGE_MINI) ?>" /></a><?php } ?>';
			break;
		case 14:
			$outputData = '<?php echo outputHtml($infoData[\''.$infoParam['param_column'].'\'], HTML) ?>';
			break;
		// ファイル
		case 12:
			$outputData = '<?php if ($infoData[\''.optParamName($infoParam['param_column']).'\'] != \'\') { ?><a href="<?php echo escapeHtml(UPLOAD_FILE_URL.$infoData[\''.$infoParam['param_column'].'\']) ?>" target="_blank">ファイル</a><?php } ?>';
			break;
		// 外部キー(単一選択型:セレクト)
		case 13:
			$tmpParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".optSql(trim($infoParam['param_info']))."'"), array("param_sort", "param_id"), 1);
			$outputData = '<?php echo escapeHtml(getBoxCms('.trim($infoParam['param_info']).', "'.DB_COLUMN_HEADER.'id", "'.$tmpParam[0]['param_column'].'", $infoData[\''.$infoParam['param_column'].'\'])) ?>';
			break;
		// 外部キー(hidden型)
		case 19:
			$tmpParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".optSql(trim($infoParam['param_info']))."'"), array("param_sort", "param_id"), 1);
			$outputData = '<?php echo escapeHtml(getBoxCms('.trim($infoParam['param_info']).', "'.DB_COLUMN_HEADER.'id", "'.$tmpParam[0]['param_column'].'", $infoData[\''.$infoParam['param_column'].'\'])) ?>';
			break;
		default:
			$outputData = '<?php echo outputHtml($infoData[\''.$infoParam['param_column'].'\'], SINGLE) ?>';
			break;
	}
	
	return $outputData;
}

function outputHtml($str, $type = 'SINGLE', $option = "") {
	$outputType = '';
	switch ($type) {
		case 'MULTI':
			$str = unserialize($str);
			if ($option == "") {
				$outputType.= nl2br(escapeHtml(implode("\n", $str)));
			} else {
				$outputType.= escapeHtml(implode($option, $str));
			}
			break;
		case 'IMAGE':
			$outputType.= nl2br(escapeHtml($str));
			break;
		case 'IMAGE_MINI':
			$outputType.= nl2br(escapeHtml("m".$str));
			break;
		case 'HTML':
			$outputType.= $str;
			break;
		default :
			$outputType.= nl2br(escapeHtml($str));
			break;
	}
	return $outputType;
}

function uploadFileCms($file, $newPath, $newHeight = "", $newWidth = "") {
	if (IMAGE_MAGICK != "" && (file_exists(IMAGE_MAGICK) || file_exists(str_replace("local/", "", IMAGE_MAGICK)))) {
		$size = getimagesize($file);
		if ($newHeight > $size[1] && $newWidth > $size[0]) {
			$newHeight = $size[1];
			$newWidth = $size[0];
		} 
		uploadImageMagick($file, $newPath, $newHeight, $newWidth);
	} else {
		uploadImage($file, $newPath, $newHeight, $newWidth);
	}
}

function setParamCms($arrParam, $resParam) {
	foreach ($resParam as $key => $val) {
		switch ($val['param_type']) {
			// 日付
			case 9:
				$arrParam[optParamName($val['param_column'])] = $val['param_name'];
				$arrParam[optParamName($val['param_column'])."_y"] = $val['param_name'].mb_convert_encoding("(年)", APP_ENC, "UTF-8");
				$arrParam[optParamName($val['param_column'])."_m"] = $val['param_name'].mb_convert_encoding("(月)", APP_ENC, "UTF-8");
				$arrParam[optParamName($val['param_column'])."_d"] = $val['param_name'].mb_convert_encoding("(日)", APP_ENC, "UTF-8");
				break;
			// タイムスタンプ
			case 10:
				$arrParam[optParamName($val['param_column'])] = $val['param_name'];
				$arrParam[optParamName($val['param_column'])."_y"] = $val['param_name'].mb_convert_encoding("(年)", APP_ENC, "UTF-8");
				$arrParam[optParamName($val['param_column'])."_m"] = $val['param_name'].mb_convert_encoding("(月)", APP_ENC, "UTF-8");
				$arrParam[optParamName($val['param_column'])."_d"] = $val['param_name'].mb_convert_encoding("(日)", APP_ENC, "UTF-8");
				$arrParam[optParamName($val['param_column'])."_h"] = $val['param_name'].mb_convert_encoding("(時)", APP_ENC, "UTF-8");
				$arrParam[optParamName($val['param_column'])."_i"] = $val['param_name'].mb_convert_encoding("(分)", APP_ENC, "UTF-8");
				$arrParam[optParamName($val['param_column'])."_s"] = $val['param_name'].mb_convert_encoding("(秒)", APP_ENC, "UTF-8");
				break;
			// パスワード
			case 20:
				$arrParam[optParamName($val['param_column'])] = $val['param_name'];
				$arrParam["_".optParamName($val['param_column'])] = $val['param_name'].mb_convert_encoding("(確認用)", APP_ENC, "UTF-8");
				break;
			default :
				$arrParam[optParamName($val['param_column'])] = $val['param_name'];
				break;
		}
	}
	return $arrParam;
}
	
// チェック
function checkSet() {
		ob_start();
		include WEB_APP.'config/secret.php';
		$secret = ob_get_contents();
		ob_end_clean();
		if (md5(DMN_NAME) != substr($secret, 0, 32)) exit;
}

// 外部キー(単一選択型:セレクト)表示
function selectBoxCms($systemId, $where, $order, $value, $name, $request = "") {
	if (!is_numeric($systemId)) {
		$infoSystem = findEZSystem(array("system_table = '".optSql($systemId)."'"));
		$systemId = $infoSystem[0]['system_id'];
		if ($systemId == "") exit;
	}
	if ($where == "") $where = array();
	if ($order == "") $order = array();
	$resData = findEZData($systemId, $where, $order);
	$tag = "";
	for ($i = 0;$i < count($resData);$i++) {
		$tag.= "<option value=\"".escapeHtml($resData[$i][$value])."\" ";
		if ($resData[$i][DB_COLUMN_HEADER.'id'] == $request) {
			$tag.= "selected";
		}
		$tag.= ">".escapeHtml($resData[$i][$name])."</option>";
	}
	return $tag;
}

// 外部キー(単一選択型:セレクト)確認
function getBoxCms($systemId, $value, $name, $request) {
	$foreignName = '';

	if (!is_numeric($systemId)) {
		$infoSystem = findEZSystem(array("system_table = '".optSql($systemId)."'"));
		$systemId = $infoSystem[0]['system_id'];
		if ($systemId == "") exit;
	}
	$infoData = findEZData($systemId, array(optSql($value)." = '".optSql($request)."'"));
	if (isset($infoData[0][$name])) $foreignName = $infoData[0][$name];

	return $foreignName;
}

// CMS管理者
function checkSecretCode() {
	$getCode = file_get_contents(WEB_APP.'config/secret.php');
	if (!isset($_REQUEST[$getCode])) {
		viewCms404();
		exit;
	}
	$cookiePath = explode("/", $_SERVER['PHP_SELF']);
	unset($cookiePath[(count($cookiePath) - 1)]);
	setcookie("loginSystemSec", "1", time() + 3600 * 24 * 365, implode("/", $cookiePath)."/", DMN_NAME);
	setcookie("loginSystemAuth", $_REQUEST['auth'], time() + 3600 * 24 * 365, implode("/", $cookiePath)."/", DMN_NAME);
}

function viewCms404() {
	exit(file_get_contents(APP_DIR.'executor/tmp/404.html'));
}

function viewCms403() {
	exit(file_get_contents(APP_DIR.'executor/tmp/403.html'));
}

?>