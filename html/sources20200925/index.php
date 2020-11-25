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
    <div class="l-header__contents">
      <div class="l-header__logo">
        <a href="<?php echo h(HOME_URL) ?>">電力シェアリング</a>
      </div>
      <nav class="l-global-nav">
        <ul class="l-global-nav__inner">
          <li class="l-global-nav__item">
            <a href="<?php echo h(HOME_URL) ?>about/" class="l-global-nav__link">
              <i class="fas fa-angle-right"></i>
              会員プログラムとは
            </a>
          </li>
          <li class="l-global-nav__item">
            <a href="<?php echo h(HOME_URL) ?>contact/" class="l-global-nav__link">
              <i class="fas fa-angle-right"></i>
              お問い合わせ
            </a>
          </li>
          <li class="l-global-nav__item">
            <a href="<?php echo h(HOME_URL) ?>user/regist" class="l-global-nav__button">新規会員登録</a>
          </li>
        </ul>
      </nav>
      <span class="l-global-nav__bg js-nav-close"></span>
      <span class="l-global-nav__btn-close js-nav-close">
        <i class="fas fa-times"></i>
      </span>
      <span class="l-global-nav__btn js-nav-open">
        <i class="fas fa-bars"></i>
      </span>
    </div>
  </header>
  <div class="l-contents__wrap">
    <main class="l-contents">
      <section class="p-top__sign-in">
        <div class="l-contents__inner">
          <h2 class="c-title__m">マイページログイン</h2>
          <form action="<?php echo h(HOME_URL) ?>user/login_exe" class="c-form" method="post">
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
                </td>
              </tr>
            </table>
          </form>
          <div class="p-top__sign-in-link">
            <a href="<?php echo h(HOME_URL) ?>sign-in/mail.html">
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
      <div class="l-footer__contents">
        <div class="l-footer__nav-wrap">
          <ul class="l-footer__nav">
            <li class="l-footer__nav-item">
              <a href="<?php echo h(HOME_URL) ?>company/" class="l-footer__nav-link">運営会社</a>
            </li>
            <li class="l-footer__nav-item">
              <a href="<?php echo h(HOME_URL) ?>terms/" class="l-footer__nav-link">利用規約</a>
            </li>
            <li class="l-footer__nav-item">
              <a href="<?php echo h(HOME_URL) ?>privacy-policy/" class="l-footer__nav-link">プライバシーポリシー</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="l-footer__copyright-wrap">
        <div class="l-footer__copyright">
          <small>&copy; 2020 companyname Inc.</small>
        </div>
      </div>
    </footer>
  </div>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="js/common.js"></script>
</body>

</html>