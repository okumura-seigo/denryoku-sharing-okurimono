<?php

# パラメータ設定
$arrParam = array(
	"id" => "ID",
);

// ライブラリ読み込み
require_once WEB_APP."user.php";

// データ取得
$requestData = getRequestData($arrParam);

// 参加履歴
$infoMessage = $objDB->findRowData(
	'message',
	array(
		"where" => array("user_id = ?", "message_id = ?", "stop_flg = 0", "delete_flg = 0"),
		"param" => array($infoLoginUser['user_id'], $requestData['id']),
	)
);
if (count($infoMessage) == 0) redirectUrl(HOME_URL);

// 既読
if ($infoMessage['read_flg'] == 0) {
	$objDB->updateData('message', array('read_flg' => '1'), $infoMessage['message_id']);
}

// 出力設定
extract($requestData);

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>電力シェアリング</title>
  <meta name="description" content="">
  <meta property="og:type" content="website">
  <meta property="og:url" content="">
  <meta property="og:title" content="">
  <meta property="og:description" content="">
  <meta property="og:site_name" content="">
  <meta property="og:image" content="">
  <link rel="icon" href="./favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" href="apple-touch-icon.png">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css"
    integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" crossorigin="anonymous">
  <link href="../../css/style.css" rel="stylesheet" media="all">
</head>

<body>
  <header class="l-header">
    <?php require_once TEMPLATE_DIR.'mypage_header.php' ?>
  </header>
  <div class="l-contents__wrap">
    <main class="l-contents">
      <div class="l-breadcrumbs">
        <ol class="l-breadcrumbs__inner">
          <li class="l-breadcrumbs__item">
            <a href="<?php echo h(HOME_URL) ?>">電力シェアリング TOP</a>
          </li>
          <li class="l-breadcrumbs__item">
            <a href="<?php echo h(HOME_URL) ?>mypage/">マイページ TOP</a>
          </li>
          <li class="l-breadcrumbs__item">
            <a href="<?php echo h(HOME_URL) ?>mypage/mail/">受信ボックス</a>
          </li>
          <li class="l-breadcrumbs__item">
            <span><?php echo $infoMessage['title']; ?></span>
          </li>
        </ol>
      </div>
      <section class="l-contents__section">
        <div class="l-contents__inner">
          <h2 class="c-title__page">受信ボックス</h2>
          <table class="c-table">
            <tr>
              <th>件名</th>
              <td><?php echo h($infoMessage['title']) ?></td>
            </tr>
            <tr>
              <th class="u-w10em">日付</th>
              <td><?php echo h(substr($infoMessage['insert_datetime'], 0, -9)) ?></td>
            </tr>
            <tr>
              <th>内容</th>
              <td><?php echo nl2br(h($infoMessage['content'])) ?></td>
            </tr>
            <tr>
              <th>関連リンク</th>
              <td>
<?php for ($i = 1;$i <= 3;$i++) { ?>
<?php if ($infoMessage['link'.$i]) { ?>
                      <a href="<?php echo h($infoMessage['link'.$i]) ?>" target="_blank"><?php echo $infoMessage['link'.$i] ?></a>
                      <br>
<?php } ?>
<?php } ?>
              </td>
            </tr>
            <tr>
              <th>添付ファイル</th>
              <td>
<?php for ($i = 1;$i <= 3;$i++) { ?>
<?php if ($infoMessage['file'.$i]) { ?>
                      <a href="<?php echo h(UPLOAD_FILE_URL.$infoMessage['file'.$i]) ?>" target="_blank"><!-- <img src="<?php echo h(UPLOAD_FILE_URL.$infoMessage['file'.$i]) ?>" style="width:40%;"> --><?php echo h(UPLOAD_FILE_URL.$infoMessage['file'.$i]) ?></a>
                      <br>
<?php } ?>
<?php } ?>
              </td>
            </tr>
          </table>
        </div>
        <a href="./index" style="color: white;"><button class="c-form__button" type="submit">戻る</button></a>
      </section>
    </main>
    <footer class="l-footer">
      <?php require_once TEMPLATE_DIR.'footer.php' ?>
    </footer>
  </div>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="../../js/common.js"></script>
</body>

</html>