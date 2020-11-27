<?php 

// ライブラリ読み込み
require_once WEB_APP."public.php";

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
  <link href="../css/style.css" rel="stylesheet" media="all">
</head>

<body>
  <header class="l-header">
    <?php require_once TEMPLATE_DIR.'header.php' ?>
  </header>
  <div class="l-contents__wrap">
    <main class="l-contents">
      <div class="l-breadcrumbs">
        <ol class="l-breadcrumbs__inner">
          <li class="l-breadcrumbs__item">
            <a href="<?php echo h(HOME_URL) ?>">電力シェアリング TOP</a>
          </li>
          <li class="l-breadcrumbs__item">
            <a href="<?php echo h(HOME_URL) ?>sign-in/">マイページログイン</a>
          </li>
          <li class="l-breadcrumbs__item">
            <span>ログイン出来ない場合のお手続き</span>
          </li>
        </ol>
      </div>
      <section class="l-contents__section">
        <div class="l-contents__inner">
          <h2 class="c-title__page">パスワード再設定のためのメールを送信しました</h2>
          <p>
            <!-- ご登録いただいているメールアドレス（○○○@○○○.○○○）宛にパスワードを再設定いただくためのメールを送信しました。 -->
            ご登録いただいているメールアドレス宛にパスワードを再設定いただくためのメールを送信しました。
            <br>
            メールをご確認の上、お手続きくださいますようお願いいたします。
          </p>
        </div>
        <!-- 貝原私設。設計などから外れていれば削除。ここから(↓) -->
        <div class="c-button__wrap">
            <a href="<?php echo h(HOME_URL) ?>sign-in/" class="c-button">ログイン画面へ</a>
        </div>
        <!-- ここまで -->
      </section>
    </main>
    <footer class="l-footer">
      <?php require_once TEMPLATE_DIR.'footer.php' ?>
    </footer>
  </div>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="../js/common.js"></script>
</body>

</html>