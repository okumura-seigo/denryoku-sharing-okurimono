<?php

# パラメータ設定
$arrParam = array(
  "loginid" => "ログインID",
  "passwd" => "パスワード",
  "save" => "ログイン保持",
);

// ライブラリ読み込み
require_once WEB_APP."public.php";

// データ取得
$requestData = getRequestData($arrParam);
if (count($requestData) == 0) $requestData['save'] = 1;

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
            <a href="/">電力シェアリング TOP</a>
          </li>
          <li class="l-breadcrumbs__item">
            <span>マイページログイン</span>
          </li>
        </ol>
      </div>
      <section class="l-contents__section">
        <div class="l-contents__inner">
          <h2 class="c-title__page">マイページログイン</h2>
<?php if (count($errMsg) > 0) { ?>
            <div style="padding:10px; color:#FF0000;">
<?php foreach ($errMsg as $val) { ?>
  <?php echo h($val) ?><br>
<?php } ?>
            </div>
          <?php } ?>
          <form action="login_exe" class="c-form" method="POST">
            <table class="c-form__table">
              <tr>
                <th class="u-w12em">メールアドレス</th>
                <td>
                  <input type="email" class="c-form__input" name="loginid" placeholder="メールアドレス" value="<?php echo h($loginid) ?>" >
                </td>
              </tr>
              <tr>
                <th>パスワード</th>
                <td>
                  <input type="password" class="c-form__input" name="passwd" placeholder="パスワード">
                </td>
              </tr>
            </table>
              <table class="c-form__table">
              <tr>
                <td>
                  <div class="u-mt1em u-center">
                    <div class="c-form__checkbox">
                      <input type="checkbox" id="checkbox1"  name="save"  value="1" <?php if ($save == 1) echo "checked"; ?>>
                      <label for="checkbox1">ログイン状態を保存する</label>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <button class="c-form__button" type="submit">ログイン</button>
                  <input type="hidden" name="<?php echo h(CRYPT_PARAM) ?>" value="<?php echo h(encryptParam(array("action" => "login", "time" => date("YmdHis"),))) ?>">
                </td>
              </tr>
            </table>
            <div class="u-mt1em u-center">
              <a href="<?php echo h(HOME_URL) ?>sign-in/mail.html">
                <i class="fas fa-angle-right"></i>
                ログイン出来ない方はこちら
              </a>
            </div>
          </form>

          <h3 class="c-title__l">会員登録がお済みでない方</h3>
          <div class="c-button__wrap">
            <a href="<?php echo h(HOME_URL) ?>sign-up/" class="c-button">新規会員登録</a>
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
  <script src="../js/common.js"></script>
</body>

</html>