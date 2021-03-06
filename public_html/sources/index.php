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
  <link href="css/style.css" rel="stylesheet" media="all">
</head>

<body>
  <header class="l-header">
    <?php require_once TEMPLATE_DIR.'header.php' ?>
  </header>
  <div class="l-contents__wrap">
    <main class="l-contents">
      <section class="p-top__sign-in">
        <div class="l-contents__inner">
          <h2 class="c-title__m">マイページログイン</h2>
          <form action="<?php echo h(HOME_URL) ?>sign-in/login_exe" class="c-form" method="POST">
            <table class="p-top__sign-in-table">
              <tr>
                <th>メールアドレス</th>
                <td>
                  <input type="email" class="c-form__input" name="loginid" placeholder="メールアドレス" value="<?php echo h($loginid) ?>">
                </td>
                <th>パスワード</th>
                <td>
                  <input type="password" class="c-form__input" name="passwd" placeholder="パスワード">
                </td>
                <td>
                  <input type="submit" class="c-form__button is-wide" value="ログイン">
                  <input type="hidden" name="<?php echo h(CRYPT_PARAM) ?>" value="<?php echo h(encryptParam(array("action" => "login", "time" => date("YmdHis"),))) ?>">
                </td>
              </tr>
            </table>
          </form>
          <div class="p-top__sign-in-link">
            <a href="<?php echo h(HOME_URL) ?>sign-in/mail">
              <i class="fas fa-angle-right"></i>
              ログインできない方はこちら
            </a>
          </div>
        </div>
      </section>
      <div class="p-top__eyecatch">
        <img src="images/eyecatch.jpg" alt="">
      </div>
    </main>
    <footer class="l-footer">
      <?php require_once TEMPLATE_DIR.'footer.php' ?>
    </footer>
  </div>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="js/common.js"></script>
</body>

</html>