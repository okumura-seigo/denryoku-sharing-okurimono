<?php
##############################################################
# システム設定ファイル
# @auther kuroda
##############################################################

// ■ 全体設定項目 -------------------------------------------

/*
// アプリケーショントップディレクトリ(*)
*/
define( 'APP_DIR', '/var/www/e-kuro.com/html/testplay/denryoku-sharing/html/');

/*
// ウェブアプリケーションディレクトリ(*)
*/
if (!defined('WEB_APP')) {
	define( 'WEB_APP', '/var/www/e-kuro.com/html/testplay/denryoku-sharing/webapp/');
}

/*
// ドメイン名(*)
*/
define( 'DMN_NAME', 'e-kuro.com');

/*
// サイトトップページ(*)
*/
define( 'HOME_URL', 'https://e-kuro.com/testplay/denryoku-sharing/html/');
$HOME_URL = HOME_URL;

/*
// SSLトップページ
*/
define( 'SSL_URL', 'https://e-kuro.com/testplay/denryoku-sharing/html/');

/*
// サイトトップディレクトリ(*)
*/
if (!defined('HOME_DIR')) {
	define( 'HOME_DIR', APP_DIR );
}

/*
// アプリケーション文字エンコード
*/
if (!defined('APP_ENC')) {
	define( 'APP_ENC', 'UTF-8');
}

/*
// 強制変換文字コード
*/
define( 'APP_FORCE_ENC', '' );

/*
// サイト名(*)
*/
define( 'SITE_NAME', '電力シェアリング');
$SITE_NAME = SITE_NAME;

/*
// 一時保存ファイル格納ディレクトリ
*/
define( 'UPLOAD_FILE_TEMP_DIR', WEB_APP . 'file/temp/' );

/*
// 一時保存ファイル格納URL
*/
define( 'UPLOAD_FILE_TEMP_URL', HOME_URL . 'asset/temp_file?f=' );

/*
// 一時保存ファイル格納画像用URL
*/
define( 'UPLOAD_FILE_TEMP_IMAGE_URL', HOME_URL . 'asset/temp_image?f=' );

/*
// ファイル格納ディレクトリ
*/
define( 'UPLOAD_FILE_DIR', APP_DIR . 'templates/s/' );

/*
// ファイル格納URL
*/
define( 'UPLOAD_FILE_URL', HOME_URL . 's/' );

/*
// No Image画像
*/
define( 'NO_IMAGE', '/images/noimage.png' );

/*
// ImageMagick
*/
define( 'IMAGE_MAGICK', '/usr/bin/convert' );

/*
// GDアップロード(ImageMagickを使用しない場合)
*/
define( 'GD_UPLOAD', false );

/*
// 画像サイズ縦
*/
define( 'IMAGE_HEIGHT', '600' );

/*
// 画像サイズ横
*/
define( 'IMAGE_WIDTH', '800' );

/*
// ミニ画像サイズの使用
*/
define( 'IMAGE_MINI_FLG', true );

/*
// ミニ画像サイズ縦
*/
define( 'IMAGE_M_HEIGHT', '150' );

/*
// ミニ画像サイズ横
*/
define( 'IMAGE_M_WIDTH', '200' );


// ■ データベース設定項目 -----------------------------------

/*
// データベース種類選択(*)
*/
define( 'DB_TYPE', 'mysqli');

/*
// ホスト名(*)
*/
define( 'DB_HOST', 'localhost');

/*
// データベース名(*)
*/
define( 'DB_NAME', 'ekuro_denryoku');

/*
// データベースユーザ名(*)
*/
define( 'DB_USERNAME', 'ekuro01');

/*
// データベースパスワード(*)
*/
define( 'DB_PASSWORD', 'LkEoaD97Ec1');

/*
// データベースポート番号(*)
*/
define( 'DB_PORT', '3306' );

/*
// データベース文字エンコード
*/
define( 'DB_ENC', 'UTF-8');

/*
// CMSテーブル名ヘッダー
*/
define( 'DB_TABLE_HEADER', '');

/*
// CMSカラム名ヘッダー
*/
define( 'DB_COLUMN_HEADER', '');

/*
// Set Namesの利用フラグ
*/
$TEMP_DB_SET = true;



// ■ データベース設定項目(Slave) ----------------------------

/*
// ホスト名(*)
*/
define( 'SDB_HOST', '');

/*
// データベース名(*)
*/
define( 'SDB_NAME', '');

/*
// データベースユーザ名(*)
*/
define( 'SDB_USERNAME', '');

/*
// データベースパスワード(*)
*/
define( 'SDB_PASSWORD', '');

/*
// データベースポート番号(*)
*/
define( 'SDB_PORT', '' );



// ■ メール設定 ---------------------------------------------

/*
// メールアドレス
*/
define( 'ADM_MAIL', 'kuroda@e-kuro.com');

/*
// システムエラー用メールアドレス(*)
*/
define( 'ERR_MAIL', 'kuroda@e-kuro.com');

/*
// システムエラー用メールタイトル(*)
*/
define( 'ERR_MAIL_TITLE', '【システム障害】');

/*
// リターンパス用メールアドレス(*)
*/
define( 'RETURN_PATH', 'kuroda@e-kuro.com');

/*
// From用メールアドレス(*)
*/
define( 'FROM_MAIL', 'kuroda@e-kuro.com');

/*
// メール送信者(*)
*/
define( 'ADM_MAILER', 'GAME-SPORT');

/*
// メール文字エンコード
*/
define( 'MAIL_ENC', 'UTF-8');


// ■Smarty設定 ----------------------------------------------

/*
// Smartyの使用
*/
define( 'USE_SMARTY', false );

/*
// レフトデリミタ
*/
define( 'LEFT_DELIMITER', '{{' );

/*
// ライトデリミタ
*/
define( 'RIGHT_DELIMITER', '}}' );


