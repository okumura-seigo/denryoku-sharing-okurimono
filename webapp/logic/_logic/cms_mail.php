<?php

require_once 'cms.php';

function getCmsMail($systemId, $requestData) {
	
	$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".optSql($systemId)."'"), array("param_sort", "param_id"));
	$mailformat = "";
	foreach ($resParam as $infoParam) {
		$val = optParamName($infoParam['param_column']);
		$mailformat.= mb_convert_encoding("¡", APP_ENC, "SJIS").$infoParam['param_name']."\n";
		if ($infoParam['param_type'] == "11" || $infoParam['param_type'] == "12") {
			$mailformat.= UPLOAD_FILE_DIR.$requestData[$val];
		} elseif ($infoParam['param_type'] == "13") {
			$tmpData = findByIdData(trim($infoParam['param_info']), $requestData[$val]);
			$tmpParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".optSql(trim($infoParam['param_info']))."'"), array("param_sort", "param_id"), 1);
			$mailformat.= $tmpData[$tmpParam[0]['param_column']];
		} else {
			$mailformat.= $requestData[$val];
		}
		$mailformat.= "\n\n";
	}
	
	return $mailformat;
}

?>