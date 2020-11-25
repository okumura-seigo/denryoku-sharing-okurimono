<?php

// ライブラリ読み込み
require_once WEB_APP."user.php";
require_once WEB_APP."mypage.php";

$my_page = new MyPage();

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
            <span>お問い合わせ</span>
          </li>
        </ol>
      </div>
      <section class="l-contents__section">
        <div class="l-contents__inner">
          <h2 class="c-title__page">お問い合わせ</h2>
          <div class="l-contents__item">
          <p>
            ご不明点につきましては、お問合せフォームより送信ください。
            お問い合わせの内容によってはお時間を頂戴する場合がございます。
            予めご了承頂ますようお願い申し上げます。
          </p>
          </div>
          <div class="l-contents__item">
            <form action="send" class="c-form">
              <table class="c-form__table">
                <tr>
                  <th>お問い合わせ件名</th>
                  <td>
                    <input type="text" class="c-form__input">
                  </td>
                </tr>
                <tr>
                  <th>お問い合わせ内容</th>
                  <td>
                    <textarea name="" id="" class="c-form__textarea"></textarea>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <p><a href="<?php echo h(HOME_URL) ?>privacy-policy/">プライバシーポリシー</a>に同意いただける場合は「同意する」にチェックを入れ、「送信する」をクリックしてください。</p>
                    <div class="u-mt1em u-center">
                      <div class="c-form__checkbox">
                        <input type="checkbox" id="checkbox1" name="checkbox">
                        <label for="checkbox1">同意する</label>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <button class="c-form__button" type="submit">送信する</button>
                  </td>
                </tr>
              </table>
            </form>
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