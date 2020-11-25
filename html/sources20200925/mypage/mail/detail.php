<?php

// ライブラリ読み込み
require_once WEB_APP."user.php";
require_once WEB_APP."mypage.php";

$my_page = new MyPage();
$my_page_datas = $my_page->getMyPageData();
$message_datas = $my_page->getMessageData();
$message_details = $my_page->getMessageDetailByMessageId($_POST['id']);

if ($message_details['read_flg'] == 0) {
  $objDB->updateData('message', array('read_flg' => '1'), $message_details['message_id']);
}

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
  <link href="../../../css/style.css" rel="stylesheet" media="all">
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
            <a href="<?php echo h(HOME_URL) ?>mypage/mail">受信ボックス</a>
          </li>
          <li class="l-breadcrumbs__item">
            <!-- <span>○○会員プログラム事務局からのメッセージ</span> -->
            <span><?php echo $message_details['title']; ?></span>
          </li>
        </ol>
      </div>
      <section class="l-contents__section">
        <div class="l-contents__inner">
          <h2 class="c-title__page">受信ボックス</h2>
          <table class="c-table">
            <tr>
              <th class="u-w10em">日付</th>
              <!-- <td>2020-07-01</td> -->
              <td><?php echo $message_details['insert_datetime']; ?></td>
            </tr>
            <tr>
              <th>件名</th>
              <!-- <td>○○会員プログラム事務局からのメッセージ</td> -->
              <td><?php echo $message_details['title']; ?></td>
            </tr>
            <tr>
              <th>内容</th>
              <!-- <td>○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○</td> -->
              <td><?php echo $message_details['content']; ?></td>
            </tr>
            <tr>
              <th>関連プロジェクト</th>
              <td><a href="../project/detail.html">長野塩尻グッドライフプロジェクト</a></td>
            </tr>
            <tr>
              <th>関連リンク</th>
              <td>
                <!-- <a href="#">www.abc.com</a><br>
                <a href="#">www.abc.com</a><br>
                <a href="#">www.abc.com</a> -->
                <?php
                  for ($i = 1;$i <= 3;$i++) {
                    if ($message_details['link'.$i]) {
                ?>
                      <a href="<?php echo $message_details['link'.$i] ?>" target="_blank"><?php echo $message_details['link'.$i] ?></a>
                      <br>
                <?php
                    }
                  }?>
              </td>
            </tr>
            <tr>
              <th>画像</th>
              <td>
                <?php
                  for ($i = 1;$i <= 3;$i++) {
                    if ($message_details['file'.$i]) {
                ?>
                      <img src="<?php UPLOAD_FILE_URL.$message_details['file'.$i] ?>">'
                      <br>
                <?php
                    }
                  }?>
              </td>
            </tr>
          </table>
        </div>
      </section>
    </main>
    <footer class="l-footer">
      <?php require_once TEMPLATE_DIR.'footer.php' ?>
    </footer>
  </div>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="../../../js/common.js"></script>
</body>

</html>