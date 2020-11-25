<?php

# パラメータ設定
$arrParam = array(
	"passwd_now" => "現在のパスワード",
	"passwd" => "新しいパスワード",
	"passwd_con" => "新しいパスワード（確認用）",
);

// ライブラリ読み込み
require_once WEB_APP."user.php";
// モジュール読み込み
loadLibrary("mail");

// データ取得
$requestData = getRequestData($arrParam);

// エラーチェック
$errMsg = actionValidate("user_password_val", $requestData, $arrParam);
if (count($errMsg) == 0) {
	$requestData['passwd'] = password_hash($requestData['passwd'], PASSWORD_DEFAULT);
	$objDB->updateData("user", array("passwd" => $requestData['passwd']), $infoLoginUser['user_id']);
	redirectUrl("password_exe");
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
            <a href="<?php echo h(HOME_URL) ?>mypage/profile/">会員情報の照会・変更</a>
          </li>
          <li class="l-breadcrumbs__item">
            <span>パスワードの変更</span>
          </li>
        </ol>
      </div>
      <section class="l-contents__section">
        <div class="l-contents__inner">
          <h2 class="c-title__page">パスワードの変更</h2>
          <p>
          	<!-- <i class="fa fa-check-circle" aria-hidden="true" style="font-size:10.0rem; color:limegreen; margin-bottom: 10px;"></i> --><br>
          </p>
			<p style="margin-bottom:40px;">パスワードを変更しました</p>
			<a href="<?php echo h(HOME_URL) ?>mypage/">マイページへ</a>
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