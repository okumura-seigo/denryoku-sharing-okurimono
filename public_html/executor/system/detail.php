<?php

# パラメータ設定
$arrParam = array(
	"cms" => "ID",
);
// 管理画面文字エンコード
if (!defined('APP_ENC')) define("APP_ENC", "UTF-8");
// 設定ファイル読み込み
require_once '../../../webapp/config/cfg.inc.php';
// ライブラリ読み込み
require_once WEB_APP."system.php";

// フォーム状態の取得
$formState = getFormState();
// データ取得
$requestData = getParam($arrParam, $formState);

// DB取得
$infoSystem = findByIdSystem($requestData['cms']);
$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($requestData['cms'])."'"), array("param_sort Asc", "param_id Asc"));

// テンプレート取得
$fileList = viewDirFiles(realpath("./")."/php");
$tmpArray = array();
foreach ($fileList as $key => $val) {
	$tmpArray[$val] = file_get_contents(realpath("./")."/php/".$val);
}

$systemId = $infoSystem['system_id'];
foreach ($tmpArray as $key => $val) {
	$tmpArray[$key] = str_replace("{!cms!}", $systemId, $tmpArray[$key]);
	$tmpArray[$key] = str_replace("{!path!}", WEB_APP, $tmpArray[$key]);
	$sortArray = array();
	$resParam = findEZParam(array("param_stop_flg = 0", "param_delete_flg = 0", "system_id = '".$objDB->quote($systemId)."'"), array("param_sort Asc", "param_id Asc"));
	foreach ($resParam as $val2) {
		switch ($val['param_type']) {
			case 9:
				$sortArray[] = '"p'.$val2['param_id'].'" => "4",';
				break;
			case 1:
			case 3:
				$sortArray[] = '"p'.$val2['param_id'].'" => "3",';
				break;
			case 4:
			case 6:
			case 8:
			case 14:
				$sortArray[] = '"p'.$val2['param_id'].'" => "2",';
				break;
			case 11:
				break;
			default:
				$sortArray[] = '"p'.$val2['param_id'].'" => "1",';
				break;
		}
	}
	$sortCode = implode("\n\t", $sortArray);
	$tmpArray[$key] = str_replace("{!sort!}", $sortCode, $tmpArray[$key]);
	$arrParam = setParamCms(array(), $resParam);
	$paramCodeArray = array();
	foreach ($arrParam as $key2 => $val2) {
		$paramCodeArray[] = "\t".'"'.$key2.'" => "'.$val2.'",';
	}
	$paramCode = implode("\n", $paramCodeArray);
	$tmpArray[$key] = str_replace("{!param_array!}", $paramCode, $tmpArray[$key]);
}

$viewData = viewExtractParam($requestData, $arrParam);
extract($viewData);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="../js/jquery.js"></script>
<title>CMS管理</title>
<style>
* {
font-size: 12px;
}
</style>
</head>

<body>
<h1>■データ出力タグ</h1>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
  <tr>
    <th bgcolor="#FFFFCC">パラメータID</th>
    <th bgcolor="#FFFFCC">名前</th>
    <th bgcolor="#FFFFCC">カラム名</th>
    <th bgcolor="#FFFFCC">リンク情報</th>
    <th bgcolor="#FFFFCC">HTML情報</th>
  </tr>
<?php foreach ($resParam as $key => $val) { ?>
  <tr>
    <td align="center" bgcolor="#FFFFFF">p<?php echo escapeHtml($val['param_id']) ?></td>
    <td bgcolor="#FFFFFF"><?php echo escapeHtml($val['param_name']) ?></td>
    <td bgcolor="#FFFFFF"><?php echo escapeHtml($val['param_column']) ?></td>
    <td bgcolor="#FFFFFF">
		検索リンク：
		<a href="<?php echo escapeHtml(HOME_URL) ?><?php echo escapeHtml($infoSystem['system_folder']) ?>/list.php?p<?php echo escapeHtml($val['param_id']) ?>=[検索条件]" target="_blank">通常検索</a>
<?php if ($val['param_type'] == 1 || $val['param_type'] == 3 || $val['param_type'] == 9) { ?>
		<a href="<?php echo escapeHtml(HOME_URL) ?><?php echo escapeHtml($infoSystem['system_folder']) ?>/list.php?p<?php echo escapeHtml($val['param_id']) ?>=[下限]-[上限]" target="_blank">範囲検索</a>
<?php if ($val['param_type'] == 9) { ?>
		<br />○2010/01/01　×2010-01-01
<?php } ?>
<?php } ?>
		<br />
		並び替えリンク:
		<a href="<?php echo escapeHtml(HOME_URL) ?><?php echo escapeHtml($infoSystem['system_folder']) ?>/list.php?o=<?php echo escapeHtml($val['param_id']) ?>&st=1" target="_blank">昇順</a>
		<a href="<?php echo escapeHtml(HOME_URL) ?><?php echo escapeHtml($infoSystem['system_folder']) ?>/list.php?o=<?php echo escapeHtml($val['param_id']) ?>&st=2" target="_blank">降順</a>
    </td>
    <td bgcolor="#FFFFFF">
		一覧表示タグ
		<input type="text" value="<?php

		echo escapeHtml(listOutputData($val));

		?>" />
		<br />
		詳細表示タグ
		<input type="text" value="<?php

		echo escapeHtml(detailOutputData($val));

		?>" />
    </td>
  </tr>
<?php } ?>
</table>
<br />

<script type="text/javascript">
function changeDisplay(element) {
	if ($('#' + element).css('display') == 'none') {
		$('#' + element).css('display', '');
	} else {
		$('#' + element).css('display', 'none');
	}
}
</script>

<h3 id="t2"><a href="javascript:changeDisplay('d2');">■一覧テンプレート</a></h3>
<div id="d2" style="margin-bottom:30px; color:#333333; display:none;">
一覧テンプレートとして認識させるためには下記のコードを先頭行に入れてください。<br />
<textarea style="width:500px"><?php echo escapeHtml($tmpArray['list.tpl']) ?></textarea><br />
<br />
一覧のタグは必ず下記のコードの中に入れてください。<br />
詳細ページへのリンクはidパラメータを付与します。<br />
<textarea style="width:500px">&lt;?php foreach ($resData as $key => $val) { ?&gt;
{この部分は一覧表示のループ処理 この中に一覧表示タグを記述する}
<a href="detail.php?id=&lt;?php echo escapeHtml($val[DB_COLUMN_HEADER.'id']) ?&gt;">詳細情報</a>
&lt;?php } ?&gt;</textarea><br />
<br />
ページング機能は下記のコードを入れてください。<br />
このコードはループ処理の外に入れてください。<br />
<textarea style="width:500px">&lt;?php echo $pager ?&gt;</textarea>
</div>

<h3 id="t3"><a href="javascript:changeDisplay('d3');">■一覧の仕様</a></h3>
<div id="d3" style="margin-bottom:30px; color:#333333; display:none;">
各項目で検索が可能です。<br />
検索方法に関してはデータ出力タグ項目を見てください。<br />
また検索パラメータを組み合わせて、各項目の絞り込み検索が可能です。<br />
例）list.php?p1={p1に対する検索条件}&amp;p2={p2に対する検索条件}<br />
<br />
※一覧画面の検索方法を変えるには編集が必要です。<br />
検索設定部分はパラメータIDに対する検索方法を記述します。<br />
1が完全一致検索、2が部分一致検索、3が数字の範囲検索、4が日付の範囲検索となります。<br />
必要のない検索項目は削除もしくはコメントアウトしてください。
</div>

<h3 id="t4"><a href="javascript:changeDisplay('d4');">■詳細テンプレート</a></h3>
<div id="d4" style="margin-bottom:30px; color:#333333; display:none;">
詳細テンプレートとして認識させるためには下記のコードを先頭行に入れてください。<br />
<textarea style="width:500px"><?php echo escapeHtml($tmpArray['detail.tpl']) ?></textarea>
<br />
<br />
IDを表示する場合は下記のコードになります。<br />
<textarea name="textarea" style="width:500px">&lt;?php echo escapeHtml($id) ?&gt;</textarea>
</div>

<h3 id="t5"><a href="javascript:changeDisplay('d5');">■フォームテンプレートの仕様</a></h3>
<div id="d5" style="margin-bottom:30px; color:#333333; display:none;">
フォームテンプレートは複雑ですので、一度出力した後でのカスタマイズを推奨します
</div>

<h3 id="t6"><a href="javascript:changeDisplay('d6');">■CMSテンプレート</a></h3>
<div id="d6" style="margin-bottom:30px; color:#333333; display:none;">
一覧、詳細、フォームテンプレート以外で情報を表示する場合のテンプレートです。<br />
認識させるためには下記のコードを先頭行に入れてください。<br />
<textarea style="width:500px"><?php echo escapeHtml($tmpArray['index.tpl']) ?></textarea>
<br />
<br />
CMS情報を引き出すには下記のコードを入れてください。<br />
<textarea style="width:500px">&lt;?php foreach (findEZData({CMS ID}, array("<?php echo escapeHtml(DB_COLUMN_HEADER) ?>stop_flg = 0", "<?php echo escapeHtml(DB_COLUMN_HEADER) ?>delete_flg = 0"), array("<?php echo escapeHtml(DB_COLUMN_HEADER) ?>id Asc"), 5, 0) as $key => $val) { ?&gt;
{この部分は一覧表示のループ処理 この中に指定したCMSの一覧表示タグを記述する}
<a href="detail.php?id=&lt;?php echo escapeHtml($val[DB_COLUMN_HEADER.'id']) ?&gt;">詳細情報</a>
&lt;?php } ?&gt;</textarea><br />
<br />
■CMS選択<br />
{CMS ID}は、表示したいCMSIDを入力してください。<br />
※『<?php echo escapeHtml($infoSystem['system_title']) ?>』の情報を表示したい場合は<?php echo escapeHtml($cms) ?>を入れる<br />
<br />
■条件<br />
array("<?php echo escapeHtml(DB_COLUMN_HEADER) ?>stop_flg = 0", "<?php echo escapeHtml(DB_COLUMN_HEADER) ?>delete_flg = 0")の部分は条件を入れてください。<br />
それぞれの条件を"で括ったカンマ区切りで、カラム名に対して条件を指定します。<br />
例）data_string_a1カラムが「テスト」のものを表示したい場合<br />
array("<?php echo escapeHtml(DB_COLUMN_HEADER) ?>string_a1 = 'テスト'", "<?php echo escapeHtml(DB_COLUMN_HEADER) ?>stop_flg = 0", "<?php echo escapeHtml(DB_COLUMN_HEADER) ?>delete_flg = 0")<br />
<br />
■並び順<br />
array("<?php echo escapeHtml(DB_COLUMN_HEADER) ?>id Asc")の部分は並び順を入れてください。<br />
並び順を"で括ったカンマ区切りで、カラム名に対して半角スペース区切りで「Asc」（昇順）もしくは「Desc」（降順）を指定します。<br />
例）data_int_a1カラムの昇順を1番目のキーとし、重なった場合は2番目のキーとしてIDの降順を利用する場合<br />
array("<?php echo escapeHtml(DB_COLUMN_HEADER) ?>int_a1 Asc", "<?php echo escapeHtml(DB_COLUMN_HEADER) ?>id Desc")<br />
<br />
■最大数<br />
5の部分は表示する最大数を指定します。<br />
全ての情報を取得したい場合は""としてください。<br />
<br />
■開始行<br />
0の部分は、何件目から取得するかを指定します。<br />
</div>

<h3 id="t1"><a href="javascript:changeDisplay('d1');">■その他情報について</a></h3>
<div id="d1" style="margin-bottom:30px; color:#333333; display:none;">
各先頭行に追加するテンプレートコードのpublic.phpは利用するユーザー層によって変更します。<br />
<br />
PCページ：public.phpなど<br />
モバイルページ：mobile_public.phpなど<br />
スマホページ：public.phpなど
</div>

</body>
</html>
