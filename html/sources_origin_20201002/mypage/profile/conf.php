<?php

// ライブラリ読み込み
require_once WEB_APP."mypage.php";
$user_datas = $_POST;

// エラーチェック
$errMsg = array();
if (!$user_datas['name1']) $errMsg[] = "姓が正しく入力されておりません";
if (!$user_datas['name2']) $errMsg[] = "名が正しく入力されておりません";
if (!checkData($user_datas['furi1'], 'kata')) $errMsg[] = "セイが正しく入力されておりません";
if (!checkData($user_datas['furi2'], 'kata')) $errMsg[] = "メイが正しく入力されておりません";
if (!checkData($user_datas['email'], 'email')) $errMsg[] = "メールアドレスが正しく入力されておりません";
if (!is_numeric($user_datas['sex'])) $errMsg[] = "性別が正しく入力されておりません";
if (!checkdate($user_datas['birthday_m'], $user_datas['birthday_d'], $user_datas['birthday_y'])) $errMsg[] = "生年月日が正しく入力されておりません";
if (!$user_datas['works']) $errMsg[] = "職業が正しく入力されておりません";
if (!$user_datas['post']) $errMsg[] = "郵便番号が正しく入力されておりません";
if (count($errMsg) > 0) {
	require_once BOOT_PHP_DIR.'mypage/profile/index.php';
	exit;
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
            <span>会員情報の照会・変更</span>
          </li>
        </ol>
      </div>
      <section class="l-contents__section">
        <div class="l-contents__inner">
          <h2 class="c-title__page">会員情報の照会・変更</h2>
          <table class="c-form__table">
            <tr>
              <th>お名前</th>
              <td>
                <?php echo h($user_datas['name1']) ?> <?php echo h($user_datas['name2']) ?>
              </td>
            </tr>
            <tr>
              <th>フリガナ</th>
              <td>
                <?php echo h($user_datas['furi1']) ?> <?php echo h($user_datas['furi2']) ?>
              </td>
            </tr>
            <tr>
              <th>メールアドレス</th>
              <td>
                <?php echo h($user_datas['email']) ?>
              </td>
            </tr>
            <tr>
              <th>性別</th>
              <td>
<?php if($user_datas['sex'] == '1') { ?>
                    <label>男性</label>
<?php } elseif($edit_dates['sex'] == '2') { ?>
                    <label>女性</label>
<?php } else { ?>
                    <label>その他</label>
<?php } ?>
              </td>
            </tr>
            <tr>
              <th>生年月日</th>
              <td>
                <?php echo h($user_datas['birthday_y']) ?>年<?php echo h($user_datas['birthday_m']) ?>月<?php echo h($user_datas['birthday_d']) ?>日
              </td>
            </tr>
            <tr>
              <th>職業</th>
              <td>
                <?php echo h($user_datas['works']) ?>
              </td>
            </tr>
            <tr>
              <th>郵便番号</th>
              <td>
                <?php echo h($user_datas['post']) ?>
              </td>
            </tr>
            <!-- <tr>
              <th>checkbox</th>
              <td>
                <div class="c-form__float">
                  <div class="c-form__float-item">
                    <div class="c-form__checkbox">
                      <input type="checkbox" id="checkbox1" name="checkbox">
                      <label for="checkbox1">checkbox1</label>
                    </div>
                  </div>
                  <div class="c-form__float-item">
                    <div class="c-form__checkbox">
                      <input type="checkbox" id="checkbox2" name="checkbox">
                      <label for="checkbox2">checkbox2</label>
                    </div>
                  </div>
                  <div class="c-form__float-item">
                    <div class="c-form__checkbox">
                      <input type="checkbox" id="checkbox3" name="checkbox">
                      <label for="checkbox3">checkbox3</label>
                    </div>
                  </div>
                </div>
              </td>
            </tr> -->
              <tr>
<form action="exe" method="post">
                <td colspan="2">
                  <button class="c-form__button" type="submit">保存する</button>
<?php foreach ($user_datas as $key => $val) { ?>
                <input type="hidden" name="<?php echo h($key) ?>" value="<?php echo h($val) ?>">
<?php } ?>
                </td>
</form>
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