<?php

# パラメータ設定
$arrParam = array(
  "email" => "メールアドレス",
  "passwd" => "パスワード",
  "passwd_con" => "パスワード(確認)",
  "name1" => "お名前(姓)",
  "name2" => "お名前(名)",
  "furi1" => "フリガナ(セイ)",
  "furi2" => "フリガナ(メイ)",
  "pref" => "都道府県",
  "checkbox" => "利用規約"
);

// ライブラリ読み込み
require_once WEB_APP."public.php";

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
            <span>新規会員登録</span>
          </li>
        </ol>
      </div>
      <section class="l-contents__section">
        <div class="l-contents__inner">
          <h2 class="c-title__page">新規会員登録</h2>
<?php if (count($errMsg) > 0) { ?>
            <div style="padding:10px; color:#FF0000;">
<?php foreach ($errMsg as $val) { ?>
  <?php echo h($val) ?><br>
<?php } ?>
            </div>
<?php } ?>
          <form action="conf" class="c-form" method="POST">
            <table class="c-form__table">
              <tr>
                <th class="u-w12em">メールアドレス<span class="required">【必須】</span></th>
                <td>
                  <input type="email" class="c-form__input" name="email" placeholder="メールアドレスを入力" value="<?php echo h($email) ?>" >
                </td>
              </tr>
              <tr>
                <th></th>
                <td>
                  <p class="u-mt05em">
                    ご入力いただいたメールアドレスに確認メールを送付しますのでご登録手続きの前に「○○○@○○○.○○○」からのメールを必ず受信可能に設定してください。<br>
                    迷惑メール設定などをされている場合は、会員登録前に設定の解除をお願いいたします。
                  </p>
                </td>
              </tr>
              <tr>
                <th>パスワード<span class="required">【必須】</span></th>
                <td>
                  <input type="password" class="c-form__input" name="passwd" placeholder="パスワードを入力" value="<?php echo h($passwd) ?>" >
                </td>
              </tr>
              <tr>
                <th>パスワード（確認）<br><span class="required">【必須】</span></th>
                <td>
                  <input type="password" class="c-form__input" name="passwd_con" placeholder="パスワードを入力" value="<?php echo h($passwd_con) ?>" >
                </td>
              </tr>
            </table>
            <table class="c-form__table">
              <tr>
                <!-- <th class="u-w6em">姓<span class="required">【必須】</span></th> -->
                <th class="u-w7em">姓<span class="required">【必須】</span></th>
                <td>
                  <input type="text" class="c-form__input" name="name1" placeholder="姓を入力" value="<?php echo h($name1) ?>" >
                </td>
                <!-- <th class="u-w6em">名<span class="required">【必須】</span></th> -->
                <th class="u-w10em">名<span class="required">【必須】</span></th>
                <td>
                  <input type="text" class="c-form__input" name="name2" placeholder="名を入力" value="<?php echo h($name2) ?>" >
                </td>
              </tr>
              <tr>
                <th>セイ<span class="required">【必須】</span></th>
                <td>
                  <input type="text" class="c-form__input" name="furi1" placeholder="セイを入力" value="<?php echo h($furi1) ?>" >
                </td>
                <th>メイ<span class="required">【必須】</span></th>
                <td>
                  <input type="text" class="c-form__input" name="furi2" placeholder="メイを入力" value="<?php echo h($furi2) ?>" >
                </td>
              </tr>
            </table>
            <table class="c-form__table">
              <tr>
                <th class="u-w12em">都道府県<span class="required">【必須】</span></th>
                <td>
                  <div class="c-form__float-item">
                    <div class="c-form__select">
                      <select name="pref" id="">
                        <option value="">選択してください</option>
<?php foreach ($PREFECTURE_CODE as $key => $val) { ?>
                        <option value="<?php echo h($val) ?>" <?php if ($pref == $val) echo "selected"; ?>><?php echo h($val) ?></option>
<?php } ?>
                      </select>
                    </div>
                  </div>
                </td>
              </tr>
            </table>
            <table class="c-form__table">
              <tr>
                <td>
                  <p><a href="<?php echo h(HOME_URL) ?>terms/">利用規約</a>および<a href="<?php echo h(HOME_URL) ?>privacy-policy/">プライバシーポリシー</a>をご確認の上「同意する」にチェックを入れ、「登録する」をクリックしてください。</p>
                  <div class="u-mt1em u-center">
                    <div class="c-form__checkbox">
                      <input type="checkbox" id="checkbox1" name="checkbox" value="1" <?php if ($checkbox == 1) echo "checked"; ?>>
                      <label for="checkbox1">同意する</label>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <button class="c-form__button" type="submit">登録する</button>
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
  <script src="../js/common.js"></script>
</body>

</html>