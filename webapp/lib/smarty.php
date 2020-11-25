<?php

class mySmarty extends Smarty {
	var $logic_dir;
	var $vo_dir;
	var $dao_dir;
	var $validate_dir;
	var $object_name;

	function mySmarty($object_name="") {
		$this->Smarty();
		// Smartyオブジェクトの名前を保持
		$this->object_name = $object_name;
		// Smartyで用いるディレクトリの設定
		$this->template_dir = HTML_DIR;
		$this->compile_dir = COMPILE_DIR;
		$this->plugins_dir = PLUGINS_DIR;
		$this->config_dir = CONFIG_DIR;
		$this->cache_dir = CASH_DIR;
		// MySmartyで用いるディレクトリの設定
//			$this->logic_dir = LOGIC_DIR;
		$this->list_dir = LIST_DIR;
		$this->vo_dir = VO_DIR;
		$this->dao_dir = DAO_DIR;
		$this->mail_dir = MAIL_DIR;
		$this->validate_dir = VALIDATE_DIR;
		// 入出力処理
		if (APP_ENC == "UTF-8") {
			$this->register_prefilter(array($this,'pre1'));
			$this->register_postfilter(array($this,'post1'));
		}
		// デリミタ設定
		$this->left_delimiter = LEFT_DELIMITER;
		$this->right_delimiter = RIGHT_DELIMITER;
	}

	// プリフィルタ
	function pre1($tpl_source, &$smarty)
	{
		return mb_convert_encoding($tpl_source, "EUC-JP", APP_ENC);
	}

	// ポストフィルタ
	function post1($tpl_source, &$smarty)
	{
		return mb_convert_encoding($tpl_source, APP_ENC, "EUC-JP");
	}
}

/*
// プラス計算
*/
function mathPlus($int, $plus) {
	return $int + $plus;
}

?>