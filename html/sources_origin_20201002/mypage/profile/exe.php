<?php

// ライブラリ読み込み
require_once WEB_APP."user.php";
require_once WEB_APP."mypage.php";
$EditProfile = new EditProfile();

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
if (count($errMsg) == 0) {
	$user_datas['birthday'] = $user_datas['birthday_y']."-".$user_datas['birthday_m']."-".$user_datas['birthday_d'];
	$objDB->updateData('user', $user_datas, $infoLoginUser['user_id']);
	redirectUrl("exe");
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
          <p>会員情報変更は正常に終了いたしました。</p>
          <div style="margin-top:40px;">
            <a href="./">戻る</a>
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