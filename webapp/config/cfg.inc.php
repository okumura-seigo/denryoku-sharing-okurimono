<?php
##############################################################
# プロジェクト設定ファイル
# @auther kuroda
##############################################################

// システム設定ファイルの読み込み
require_once("const.inc.php");
require_once("code.inc.php");
require_once("cms.inc.php");


//echo realpath("./");


// ■ システムマネージャー設定項目 -------------------------------------------

/*
// システムマネージャーユーザ名(*)
*/
define( 'WA_USERNAME', 'system');

/*
// システムマネージャーパスワード(*)
*/
define( 'WA_PASSWORD', 'test');

// ■ CMSマネージャー設定項目 -------------------------------------------

/*
// CMSマネージャーユーザ名(*)
*/
define( 'CMS_WA_USERNAME', 'cms');

/*
// CMSマネージャーパスワード(*)
*/
define( 'CMS_WA_PASSWORD', 'test');

// ■暗号・複合 -----------------------------------------

/*
// 暗号パラメータ(*)
*/
define('CRYPT_PARAM', 'collect');

/*
// 暗号化キー(*)
*/
define('CRYPT_KEY', 'Cript system cryption ef5FP4Zs3D51fPd2');

// ■サイト設定項目 -----------------------------------------

/*
// セッションタイムアウト時間(分)
*/
define( 'SESSION_TIMEOUT', 120);

/*
// セッションクッキータイムアウト時間(分)
*/
define( 'SESSION_COOKIE_TIMEOUT', 120);

/*
// セッション保存場所(*)
*/
define( 'SESSION_SAVE_DIR', WEB_APP.'file/session' );

/*
// セッション保存形式(*)
*/
define( 'SESSION_SAVE_HANDLER', 'files' );

/*
// リスト表示数
*/
define( 'LIST_NUM', 20);

/*
// 管理用リスト表示数
*/
define( 'ADM_LIST_NUM', 20);



// ■サイトディレクトリ設定 --------------------------------

/*
// HTMLディレクトリ(*)
*/
if (!defined('HTML_DIR')) {
	define( 'HTML_DIR', APP_DIR . 'templates/');
}

/*
// BOOTディレクトリ(*)
*/
if (!defined('BOOT_HTML_DIR')) {
	define( 'BOOT_HTML_DIR', APP_DIR );
}

/*
// PAGEディレクトリ(*)
*/
define( 'SYSTEM_PAGE_DIR', APP_DIR . 'app/page/');

/*
// TEMPLATEディレクトリ(*)
*/
define( 'TEMPLATE_DIR', BOOT_HTML_DIR . 'template/');

/*
// IMAGESディレクトリ(*)
*/
define( 'IMAGES_DIR', BOOT_HTML_DIR . 'images/');

/*
// MODULEディレクトリ(*)
*/
define( 'MODULE_DIR', WEB_APP . 'module/');

/*
// COMPILEディレクトリ(*)
*/
define( 'COMPILE_DIR', WEB_APP . 'smarty/compile/');

/*
// PLUGINSディレクトリ(*)
*/
define( 'PLUGINS_DIR', WEB_APP . 'smarty/plugins/' );

/*
// CASHディレクトリ(*)
*/
define( 'CASH_DIR', WEB_APP . 'smarty/cash/' );

/*
// CONFIGディレクトリ(*)
*/
define( 'CONFIG_DIR', WEB_APP . 'config/' );

/*
// メールフォーマットディレクトリ(*)
*/
define( 'MAIL_DIR', WEB_APP . 'mail/');

/*
// LIBディレクトリ(*)
*/
define( 'LIB_DIR', WEB_APP . 'lib/');

/*
// ロジックディレクトリ(*)
*/
define( 'LOGIC_DIR', WEB_APP . 'logic/_logic/');

/*
// VALIDATEディレクトリ(*)
*/
define( 'VALIDATE_DIR', WEB_APP . 'logic/_validate/');

/*
// DBディレクトリ(*)
*/
define( 'DB_DIR', WEB_APP . 'file/db/');


// ■動作設定 ----------------------------------------------

/*
// デバッグ／リリースモードの設定(*)
//   開発中デバッグモードは記述不要。
//   完成リリースモードは0を指定。
//   ＜デバッグモード処理変更箇所＞
//   ・DBエラーの詳細表示
//   ・PHPエラー表示
*/
//define ( 'DEBUG_MODE', 0);

/*
// エラーの表示(*)
*/
ini_set('display_errors', 'off');
error_reporting(0);

/*
// 障害発生時の遷移ページ
*/
define ( 'ERR_PAGE', HOME_URL);

/*
// DBマッピング動作(*)
*/
define ( 'OR_MAPS', true);

