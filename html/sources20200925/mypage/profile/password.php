<?php

# パラメータ設定
$arrParam = array(
  "passwd_now" => "現在のパスワード",
  "passwd" => "新しいパスワード",
  "passwd_con" => "新しいパスワード（確認用）",
);

// ライブラリ読み込み
require_once WEB_APP."user.php";

// データ取得
$requestData = getRequestData($arrParam);

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
            <a href="<?php echo h(HOME_URL) ?>mypage/profile/">会員情報の照会・変更</a>
          </li>
          <li class="l-breadcrumbs__item">
            <span>パスワードの変更</span>
            <?php if (count($errMsg) > 0) { ?>
              <div style="color:#FF0000; padding:10px;">
              <?php foreach ($errMsg as $val) { ?>
                <?php echo h($val) ?><br>
              <?php } ?>
              </div>
            <?php } ?>
          </li>
        </ol>
      </div>
      <section class="l-contents__section">
        <div class="l-contents__inner">
          <h2 class="c-title__page">パスワードの変更</h2>
          <form action="passwd_con" class="c-form">
            <table class="c-form__table">
              <tr>
                <th>現在のパスワード</th>
                <td>
                  <input type="password" class="c-form__input" name="passwd_now" placeholder="現在のパスワードを入力" value="<?php echo h($passwd_now) ?>">
                </td>
              </tr>
              <tr>
                <th>新しいパスワード</th>
                <td>
                  <input type="password" class="c-form__input" name="passwd" placeholder="新しいパスワードを入力" value="<?php echo h($passwd) ?>">
                </td>
              </tr>
              <tr>
                <th>新しいパスワード（確認）</th>
                <td>
                  <input type="text" class="c-form__input">
                  <input type="password" class="c-form__input" name="passwd_con" placeholder="新しいパスワード(確認用)を入力" value="<?php echo h($passwd_con) ?>">
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <button class="c-form__button" type="submit">保存する</button>
                </td>
              </tr>
            </table>
          </form>
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