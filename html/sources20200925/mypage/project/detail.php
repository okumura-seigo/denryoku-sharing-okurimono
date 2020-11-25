<?php

// ライブラリ読み込み
require_once WEB_APP."user.php";
require_once WEB_APP."mypage.php";

$my_page = new MyPage();
$_POST['page_no'] ? $page_no = $_POST['page_no'] : $page_no = 1;
$project_historys = $my_page->getProjectHistoryData($page_no);


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
            <a href="<?php echo h(HOME_URL) ?>mypage/project/">プロジェクト参加履歴</a>
          </li>
          <li class="l-breadcrumbs__item">
            <!-- <span>長野塩尻グッドライフプロジェクト</span> -->
            <span><?php echo $project_historys['title']; ?></span>
          </li>
        </ol>
      </div>
      <section class="l-contents__section">
        <div class="l-contents__inner">
          <h2 class="c-title__page">プロジェクト参加履歴</h2>
          <table class="c-table">
            <tr>
              <th class="u-w10em">開始日</th>
              <!-- <td>2020-07-01</td> -->
              <td><?php echo $project_historys['start_day']; ?></td>
            </tr>
            <tr>
              <th>終了日</th>
              <!-- <td>2020-07-01</td> -->
              <td><?php echo $project_historys['end_day']; ?></td>
            </tr>
            <tr>
              <th>プロジェクト名</th>
              <!-- <td>長野塩尻グッドライフプロジェクト</td> -->
              <td><?php echo $project_historys['title']; ?></td>
            </tr>
            <tr>
              <th>概要</th>
              <!-- <td>○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○</td> -->
              <td><?php echo $project_historys['content']; ?></td>
            </tr>
            <tr>
              <th>添付ファイル</th>
              <!-- <td>○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○</td> -->
              <td><?php echo $project_historys['files']; ?></td>
            </tr>
            <tr>
              <th>関連リンク</th>
              <td>
                <!-- <a href="#">www.abc.com</a><br>
                <a href="#">www.abc.com</a><br>
                <a href="#">www.abc.com</a> -->
                 <?php
                  for ($i = 1;$i <= 3;$i++) {
                    if ($project_historys['link'.$i]) {
                ?>
                      <a href="<?php echo $project_historys['link'.$i] ?>" target="_blank"><?php echo $project_historys['link'.$i] ?></a>
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