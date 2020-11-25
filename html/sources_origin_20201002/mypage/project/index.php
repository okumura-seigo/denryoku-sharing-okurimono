<?php

// ライブラリ読み込み
require_once WEB_APP."user.php";

// プロジェクト参加履歴
$resProjectHistory = $objDB->findData(
  'project_history',
  array(
    "join" => array("Inner Join project On project_history.project_id = project.project_id"),
    "where" => array("project_history.user_id = ?"),
    "order" => array("project_history.update_datetime Desc"),
    "param" => array($infoLoginUser['user_id']),
  )
);

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
            <span>プロジェクト参加履歴</span>
          </li>
        </ol>
      </div>
      <section class="l-contents__section">
        <div class="l-contents__inner">
          <h2 class="c-title__page">プロジェクト参加履歴</h2>
          <table class="c-table">
            <tr>
              <th class="u-w8em">開始日</th>
              <th>プロジェクト</th>
              <th class="u-w8em">ステータス</th>
<!--              <th class="u-w4em"></th>-->
            </tr>
<?php foreach ($resProjectHistory as $key => $val) { ?>
              <tr>
                <td class="u-center"><?php echo h($val['start_day']) ?></td>
                <td><a href="detail?id=<?php echo h($val['project_history_id']) ?>"><?php echo h($val['project_name']) ?></td>
                <td class="u-center"><?php echo h($val['project_history_status']) ?></td>
              </tr>
<?php } ?>
          </table>
          <div class="c-pagination__wrap">
            <ul class="c-pagination">
              <?php
                $max_count = ceil((int)$resProjectHistory / 5);
                for($i=1; $max_count>=$i; $i++) {
              ?> 
                  <form name="pager<?php echo $i ?>" action="" method="post">
                    <input type="hidden" name="page_no" value="'<?php $i ?>">
                  </form>
              <?php  } ?>
              <?php
                for($i=1; $max_count>=$i; $i++) {
                  if($i >= 2) {
                    echo ' | ';
                  }
                  if($i == $page_no) {
              ?>
                    <span><?php echo $i ?></span>
                <?php
                  } else {
                ?>
                    <a href="" onClick="document.pager<?php echo $i ?>.submit();return false;"><?php echo $i ?></a>
              <?php
                  }
                } ?>
            </ul>
          </div>
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