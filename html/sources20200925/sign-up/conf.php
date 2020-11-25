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
	// "pref" => "都道府県",
  "checkbox" => "利用規約"
);

// ライブラリ読み込み
require_once WEB_APP."public.php";

// データ取得
$requestData = getRequestData($arrParam);

// エラーチェック
$errMsg = actionValidate("user_regist_val", $requestData, $arrParam);
if (count($errMsg) > 0) {
	require_once 'index.php';
	exit;
}

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
          <table class="c-form__table">
              <tr>
                 <th class="u-w12em">メールアドレス</th>
                 <td><?php echo h($email) ?></td>
              </tr>
              <tr>
                <th>パスワード</th>
                <td><?php echo secretStr($passwd) ?></td>
              </tr>
              <tr>
                <th class="u-w4em">お名前</th>
                <td><?php echo h($name1) ?> <?php echo h($name2) ?></td>
              </tr>
              <tr>
                <th>フリガナ</th>
                <td><?php echo h($furi1) ?> <?php echo h($furi2) ?></td>
              </tr>
          <br>
<!--           <br>
          都道府県 <?php echo h($PREFECTURE_CODE[$pref]) ?> -->
          <br>
        </table>
		<form action="complete" method="POST">
			<input type="submit" value="登録する" class="c-form__button">
			<input type="hidden" name="<?php echo h(CRYPT_PARAM) ?>" value="<?php echo h(encryptParam($requestData)) ?>">
		</form>
		<form action="index" method="POST">
			<input type="submit" value="修正する" class="c-form__button">
			<input type="hidden" name="<?php echo h(CRYPT_PARAM) ?>" value="<?php echo h(encryptParam($requestData)) ?>">
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